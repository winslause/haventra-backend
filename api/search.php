<?php
// api/search.php - Unified fast search for properties and agencies (browser-safe JSON)
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$q = trim((string)($_GET['q'] ?? ''));
if ($q === '') { echo json_encode(['success'=>true,'query'=>'','results'=>[],'total'=>0]); exit; }
if (mb_strlen($q) > 120) $q = mb_substr($q, 0, 120);

try {
    if (!isset($pdo)) {
        if (!defined('APP_PATH')) define('APP_PATH', dirname(__DIR__) . '/');
        if (is_file(__DIR__ . '/../index.php')) {
            // connect directly to avoid booting full app
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=haventra;charset=utf8mb4','root','',[
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES=>false,
            ]);
        } else {
            require_once __DIR__ . '/../includes/db.php';
        }
    }

    $like = '%' . $q . '%';
    $prefix = $q . '%';
    $terms = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY) ?: [$q];
    $bool = implode(' ', array_map(fn($t)=> '+' . preg_replace('/[+\-><()~*"@]+/', '', $t) . '*', array_slice($terms,0,6)));

    $results = [];

    // Properties (fast indexed-friendly prefix/LIKE)
    $stmt = $pdo->prepare("SELECT id,title,location,price,price_suffix,badge,type,image_url,lat,lng,description FROM properties WHERE is_active=1 AND (title LIKE ? OR location LIKE ? OR description LIKE ? OR title LIKE ?) ORDER BY (title LIKE ?) DESC, created_at DESC LIMIT 20");
    $stmt->execute([$like,$like,$like,$prefix,$prefix]);
    while ($row = $stmt->fetch()) {
        $results[] = [
            'type'=>'property',
            'id'=>'prop-'.$row['id'],
            'title'=>$row['title'],
            'location'=>$row['location'],
            'price'=>'KSh '.$row['price'].' '.$row['price_suffix'],
            'badge'=>$row['badge'],
            'category'=>ucfirst((string)$row['type']),
            'image'=>$row['image_url'],
            'lat'=>$row['lat']!==null?(float)$row['lat']:null,
            'lng'=>$row['lng']!==null?(float)$row['lng']:null,
            'description'=>$row['description'],
            'url'=>($row['type']==='booking'?'/haventra/airbnb/':($row['type']==='sale'?'/haventra/houses-for-sale/':'/haventra/houses-for-rent/')),
        ];
    }

    // Agencies: FULLTEXT first (fast, ranked), fallback to LIKE for short/edge queries
    $agRows = [];
    try {
        $stmt = $pdo->prepare("SELECT id,name,category,category_label,location,city,rating,icon,short_desc,about,services,phone,email,whatsapp,website,lat,lng, MATCH(name,location,city,category_label,short_desc,about,phone,email,website) AGAINST (? IN BOOLEAN MODE) AS score FROM agencies WHERE is_active=1 AND MATCH(name,location,city,category_label,short_desc,about,phone,email,website) AGAINST (? IN BOOLEAN MODE) ORDER BY score DESC, verified DESC, rating DESC, name ASC LIMIT 20");
        $stmt->execute([$bool,$bool]);
        $agRows = $stmt->fetchAll();
    } catch (Throwable $e) { $agRows = []; }
    if (!$agRows) {
        $stmt = $pdo->prepare("SELECT id,name,category,category_label,location,city,rating,icon,short_desc,about,services,phone,email,whatsapp,website,lat,lng, 0 score FROM agencies WHERE is_active=1 AND (name LIKE ? OR location LIKE ? OR city LIKE ? OR category_label LIKE ? OR short_desc LIKE ? OR about LIKE ? OR phone LIKE ? OR email LIKE ? OR website LIKE ?) ORDER BY (name LIKE ?) DESC, verified DESC, rating DESC, name ASC LIMIT 20");
        $stmt->execute([$like,$like,$like,$like,$like,$like,$like,$like,$like,$prefix]);
        $agRows = $stmt->fetchAll();
    }
    foreach ($agRows as $row) {
        $cat = (string)$row['category'];
        $url = '/haventra/agencies/?q='.rawurlencode($q).'&category='.rawurlencode($cat);
        $results[] = [
            'type'=>'agency',
            'id'=>'agency-'.$row['id'],
            'title'=>$row['name'],
            'location'=>$row['location'],
            'city'=>$row['city'],
            'category'=>$row['category_label'],
            'badge'=>$row['category_label'],
            'image'=>null,
            'lat'=>$row['lat']!==null?(float)$row['lat']:null,
            'lng'=>$row['lng']!==null?(float)$row['lng']:null,
            'description'=>$row['short_desc'],
            'rating'=>(float)$row['rating'],
            'icon'=>$row['icon'] ?: 'fa-building',
            'phone'=>$row['phone'],
            'email'=>$row['email'],
            'whatsapp'=>$row['whatsapp'],
            'website'=>$row['website'],
            'url'=>$url,
        ];
    }

    usort($results, function($a,$b){ $oa=$a['type']==='agency'?0:1; $ob=$b['type']==='agency'?0:1; if($oa!==$ob) return $oa-$ob; return ($b['rating']??0)<=>($a['rating']??0); });
    // prefer mixed but keep relevance; agencies are high-value for this request
    echo json_encode(['success'=>true,'query'=>$q,'results'=>$results,'total'=>count($results)], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'error'=>'Search failed']);
}
