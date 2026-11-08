<?php
// profiles.php - Shared dashboard for clients, house owners, and companies/agencies.
// DB-connected; role separation via session later. All account/property/company data managed here.
if (session_status() === PHP_SESSION_NONE) session_start();

// Bootstrap (standalone-safe)
if (!defined('APP_PATH')) define('APP_PATH', __DIR__ . '/');
if (!defined('BASE_PATH')) {
    $base_path = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? '/haventra/' : '/';
    define('BASE_PATH', $base_path);
}
if (!isset($pdo) || !($pdo instanceof PDO)) {
    try { @require_once APP_PATH . 'includes/db.php'; } catch(Throwable $e){}
}
if (!isset($pdo) || !($pdo instanceof PDO)) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=haventra;charset=utf8mb4", 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Throwable $e) {
        $pdo = null;
    }
}


// --- Helpers ---
function h($v){ return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function post($k,$d=''){ return isset($_POST[$k]) ? trim((string)$_POST[$k]) : $d; }
function table_exists(PDO $pdo,$t){ try{$s=$pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?");$s->execute([$t]);return ((int)$s->fetchColumn())>0;}catch(Throwable $e){ try{$s=$pdo->prepare("SHOW TABLES LIKE ?");$s->execute([$t]);return (bool)$s->fetch(PDO::FETCH_NUM);}catch(Throwable $e2){return false;} } }
function column_exists(PDO $pdo,$t,$c){ try{$s=$pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");$s->execute([$t,$c]);return ((int)$s->fetchColumn())>0;}catch(Throwable $e){ try{$s=$pdo->prepare("SHOW COLUMNS FROM `$t` LIKE ?");$s->execute([$c]);return (bool)$s->fetch(PDO::FETCH_NUM);}catch(Throwable $e2){return false;} } }
function cols(PDO $pdo,$t){ try{ $s=$pdo->prepare("SELECT column_name FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? ORDER BY ordinal_position"); $s->execute([$t]); $out=[]; foreach($s->fetchAll(PDO::FETCH_COLUMN) as $c){$out[]=$c;} if($out) return $out; foreach($pdo->query("SHOW COLUMNS FROM `$t`") as $r){$out[]=$r['Field'];} return $out;}catch(Throwable $e){return [];} }
function jarr($v){ if(is_array($v)) return array_values(array_filter(array_map('trim',$v),fn($x)=>$x!=='')); $v=trim((string)$v); if($v==='') return []; $d=json_decode($v,true); if(is_array($d)) return array_values(array_filter(array_map(fn($x)=>trim((string)$x),$d),fn($x)=>$x!=='')); return array_values(array_filter(array_map('trim',preg_split('/[\r\n,]+/',$v)),fn($x)=>$x!=='')); }
function jenc($arr){ return json_encode(array_values($arr), JSON_UNESCAPED_UNICODE); }
function decj($v){ $d=json_decode((string)($v??''),true); return is_array($d)?$d:[]; }
function flash_set($t,$m){ $_SESSION['dash_flash']=['type'=>$t,'msg'=>$m]; }
function flash_get(){ $f=$_SESSION['dash_flash']??null; unset($_SESSION['dash_flash']); return $f; }
function redir_self($extra=''){ $url = BASE_PATH . '?page=profile' . ($extra?('&'.$extra):''); header('Location: '.$url); exit; }

// --- Session user context (roles separated later via session) ---
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? $_SESSION['account_type'] ?? 'client';
$session_name = $_SESSION['name'] ?? $_SESSION['full_name'] ?? trim(($_SESSION['first_name']??'').' '.($_SESSION['last_name']??'')) ?: 'Guest User';
$session_email = $_SESSION['email'] ?? $_SESSION['user_email'] ?? '';

// --- Ensure support tables/columns (safe/idempotent) ---
try { $pdo->exec("CREATE TABLE IF NOT EXISTS user_profiles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  email VARCHAR(190) NULL,
  full_name VARCHAR(190) NULL,
  phone VARCHAR(60) NULL,
  location VARCHAR(190) NULL,
  bio TEXT NULL,
  avatar_url VARCHAR(255) NULL,
  account_role VARCHAR(40) NULL,
  company_name VARCHAR(190) NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_profiles_email (email),
  KEY idx_user_profiles_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"); } catch(Throwable $e){}

foreach ([['properties','user_id',"ALTER TABLE properties ADD COLUMN user_id INT NULL"],
          ['properties','owner_email',"ALTER TABLE properties ADD COLUMN owner_email VARCHAR(190) NULL"],
          ['properties','parking',"ALTER TABLE properties ADD COLUMN parking INT NULL"],
          ['properties','furnished',"ALTER TABLE properties ADD COLUMN furnished TINYINT(1) NOT NULL DEFAULT 0"],
          ['properties','rating',"ALTER TABLE properties ADD COLUMN rating DECIMAL(3,2) NULL"],
          ['properties','reviews',"ALTER TABLE properties ADD COLUMN reviews INT NULL"],
          ['properties','agent_name',"ALTER TABLE properties ADD COLUMN agent_name VARCHAR(190) NULL"],
          ['properties','agent_initial',"ALTER TABLE properties ADD COLUMN agent_initial VARCHAR(10) NULL"],
          ['properties','video_url',"ALTER TABLE properties ADD COLUMN video_url VARCHAR(255) NULL"],
          ['agencies','user_id',"ALTER TABLE agencies ADD COLUMN user_id INT NULL"],
          ['agencies','owner_email',"ALTER TABLE agencies ADD COLUMN owner_email VARCHAR(190) NULL"]] as $a) {
  try { if(table_exists($pdo,$a[0]) && !column_exists($pdo,$a[0],$a[1])) $pdo->exec($a[2]); } catch(Throwable $e){}
}

function save_upload($key, $dirAbs, $dirWeb, $allowedExt, $maxBytes){
  if (!isset($_FILES[$key]) || !is_array($_FILES[$key])) return null;
  $f = $_FILES[$key];
  if (($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
  if (($f['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new RuntimeException('Upload failed for '.$key);
  $size = (int)($f['size'] ?? 0);
  if ($size <= 0 || $size > $maxBytes) throw new RuntimeException('File too large or invalid for '.$key);
  $orig = (string)($f['name'] ?? 'file');
  $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
  if (!in_array($ext, $allowedExt, true)) throw new RuntimeException('Invalid file type for '.$key);
  if (!is_dir($dirAbs)) @mkdir($dirAbs, 0775, true);
  $name = 'prop_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
  $dest = rtrim($dirAbs,'/\\') . DIRECTORY_SEPARATOR . $name;
  if (!move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException('Could not save upload '.$key);
  return rtrim($dirWeb,'/') . '/' . $name;
}


// --- POST handlers (PRG) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = post('action');
  if ($action === '' && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    flash_set('err','Upload failed: request was too large for the server (check PHP post_max_size/upload_max_filesize; video must be ≤ 5MB).');
    redir_self('tab=properties');
  }
  try {
    if ($action === 'save_profile') {
      $email = post('email',$session_email); $name = post('full_name',$session_name);
      $phone = post('phone'); $location = post('location'); $bio = post('bio'); $rolep = post('account_role',$role); $company = post('company_name');
      if ($email==='') $email = $session_email ?: null;
      $existing = null;
      if ($user_id>0) { $s=$pdo->prepare("SELECT id FROM user_profiles WHERE user_id=? LIMIT 1"); $s->execute([$user_id]); $existing=$s->fetchColumn(); }
      if (!$existing && $email) { $s=$pdo->prepare("SELECT id FROM user_profiles WHERE email=? LIMIT 1"); $s->execute([$email]); $existing=$s->fetchColumn(); }
      if ($existing) {
        $s=$pdo->prepare("UPDATE user_profiles SET user_id=?,email=?,full_name=?,phone=?,location=?,bio=?,account_role=?,company_name=? WHERE id=?");
        $s->execute([$user_id?:null,$email,$name,$phone,$location,$bio,$rolep,$company,$existing]);
      } else {
        $s=$pdo->prepare("INSERT INTO user_profiles (user_id,email,full_name,phone,location,bio,account_role,company_name) VALUES (?,?,?,?,?,?,?,?)");
        $s->execute([$user_id?:null,$email,$name,$phone,$location,$bio,$rolep,$company]);
      }
      $_SESSION['name']=$name; $_SESSION['email']=$email; $_SESSION['role']=$rolep;
      flash_set('ok','Profile saved to database.');
      redir_self('tab=profile');
    }

    if (($action === 'save_property' || $action === 'delete_property') && !($pdo instanceof PDO)) {
      flash_set('err','Property save failed: database connection is not available.');
      redir_self('tab=properties');
    }
    if (($action === 'save_property' || $action === 'delete_property')) {
      $pc = cols($pdo,'properties');
      if (!$pc && !table_exists($pdo,'properties')) {
        flash_set('err','Property save failed: `properties` table was not found in the database ('.h((string)$pdo->query('SELECT DATABASE()')->fetchColumn()).').');
        redir_self('tab=properties');
      }
      if (!$pc) { try { foreach($pdo->query("SHOW COLUMNS FROM properties") as $r){ $pc[]=$r['Field']; } } catch(Throwable $e){} }
      $id = (int)post('id');
      if ($action === 'delete_property') {
        if ($id>0 && in_array('id',$pc,true)) { $pdo->prepare("DELETE FROM properties WHERE id=?")->execute([$id]); flash_set('ok','Property deleted.'); }
        else { flash_set('err','Could not delete property: invalid record.'); }
        redir_self('tab=properties');
      }
      $uploadDirAbs = APP_PATH . 'uploads/properties';
      $uploadDirWeb = rtrim(BASE_PATH,'/') . '/uploads/properties';
      $uploadedImages = [];
      for ($i=1; $i<=6; $i++) {
        $u = save_upload('photo'.$i, $uploadDirAbs, $uploadDirWeb, ['jpg','jpeg','png','webp','gif'], 5*1024*1024);
        if ($u) $uploadedImages[] = $u;
      }
      $videoUrl = save_upload('video', $uploadDirAbs, $uploadDirWeb, ['mp4','webm','ogg','mov'], 5*1024*1024);
      $amen = jarr(post('amenities'));
      $furnishedPost = post('furnished')==='1' ? 1 : 0;
      $imgText = jarr(post('image_urls')); // optional fallback URLs
      $kept = isset($_POST['keep_images']) && is_array($_POST['keep_images']) ? array_values(array_filter(array_map('strval', $_POST['keep_images']))) : [];
      $allImages = array_values(array_unique(array_filter(array_merge($kept, $uploadedImages, $imgText))));
      if (count($allImages) > 6) $allImages = array_slice($allImages, 0, 6);
      $mainImage = !empty($allImages) ? $allImages[0] : '';
      $lat = post('lat'); $lng = post('lng');
      $beds = post('beds'); $beds = ($beds===''?0:(int)$beds);
      $size = trim((string)($beds > 0 ? ($beds . ' Bedroom' . ($beds===1?'':'s')) : 'Studio'));
      $dateAdded = post('date_added') ?: date('Y-m-d');
      $priceRaw = preg_replace('/[^0-9.]/','',post('price'));
      $data = [
        'title'=>post('title'), 'location'=>post('location'), 'price'=>($priceRaw===''?'0':$priceRaw),
        'price_suffix'=>post('price_suffix'), 'badge'=>(post('badge')!==''?post('badge'):(post('type')==='sale'?'For Sale':(post('type')==='booking'?'Short Stay':'For Rent'))),
        'beds'=>$beds, 'baths'=>(int)post('baths'),
        'size'=>$size, 'type'=>post('type','rent'), 'image_url'=>($mainImage!==''?$mainImage:''),
        'image_urls'=>jenc($allImages), 'amenities'=>jenc($amen),
        'description'=>post('description'), 'lat'=>($lat!==''?$lat:null), 'lng'=>($lng!==''?$lng:null),
        'date_added'=>$dateAdded, 'is_active'=>post('is_active','1')==='1'?1:0,
        'parking'=>post('parking')!==''?(int)post('parking'):null, 'furnished'=>$furnishedPost,
        'rating'=>post('rating')!==''?post('rating'):null, 'reviews'=>post('reviews')!==''?(int)post('reviews'):null,
        'agent_name'=>post('agent_name'), 'agent_initial'=>post('agent_initial'),
        'video_url'=>$videoUrl ?: post('existing_video_url'),
        'user_id'=>$user_id?:null, 'owner_email'=>$session_email?:null,
      ];
      $data = array_intersect_key($data, array_flip($pc));
      if (empty($data)) { flash_set('err','Property could not be saved: no matching database columns found in `properties`.'); redir_self('tab=properties'); }
      $savedCols = count($data);
      try {
        if ($id>0) {
          $sets=[]; $vals=[]; foreach($data as $k=>$v){ $sets[]="`$k`=?"; $vals[]=$v; } $vals[]=$id;
          $ok = $pdo->prepare("UPDATE properties SET ".implode(',',$sets)." WHERE id=?")->execute($vals);
          flash_set($ok?'ok':'err', $ok?('Property updated (ID '.$id.', '.$savedCols.' fields saved).'):'Property update failed.');
        } else {
          $keys=array_keys($data); $vals=array_values($data);
          $ok = $pdo->prepare("INSERT INTO properties (`".implode('`,`',$keys)."`) VALUES (".implode(',',array_fill(0,count($keys),'?')).")")->execute($vals);
          $newId = $ok ? (int)$pdo->lastInsertId() : 0;
          flash_set($ok?'ok':'err', $ok?('Property saved (ID '.$newId.', '.$savedCols.' fields saved, date '.$dateAdded.').'):'Property save failed.');
        }
      } catch (Throwable $se) {
        $detail = $se->getMessage();
        if ($se instanceof PDOException && !empty($se->errorInfo[2])) $detail .= ' | '.$se->errorInfo[2];
        flash_set('err', 'Property save failed: '.$detail.' (columns: '.implode(',', array_keys($data)).')');
      }
      redir_self('tab=properties');
    }

    if (($action === 'save_agency' || $action === 'delete_agency') && table_exists($pdo,'agencies')) {
      $ac = cols($pdo,'agencies');
      $id = (int)post('id');
      if ($action === 'delete_agency') {
        if ($id>0) { $pdo->prepare("DELETE FROM agencies WHERE id=?")->execute([$id]); flash_set('ok','Agency/company deleted.'); }
        redir_self('tab=agencies');
      }
      $logoUrl = post('logo');
      $uploadDirAbs = APP_PATH . 'uploads/agencies';
      $uploadDirWeb = rtrim(BASE_PATH,'/') . '/uploads/agencies';
      try {
        $u = save_upload('logo_file', $uploadDirAbs, $uploadDirWeb, ['jpg','jpeg','png','webp','gif','svg'], 5*1024*1024);
        if ($u) $logoUrl = $u;
      } catch(Throwable $ue) { /* keep existing/URL if upload fails */ }
      if ($logoUrl === '' && post('existing_logo') !== '') $logoUrl = post('existing_logo');
      $data = [
        'name'=>post('name'), 'category'=>post('category','agency'), 'category_label'=>post('category_label'),
        'location'=>post('location'), 'city'=>post('city'), 'rating'=>post('rating')!==''?post('rating'):null,
        'reviews'=>(int)post('reviews'), 'verified'=>post('verified')==='1'?1:0, 'years'=>(int)post('years'),
        'years_label'=>post('years_label'), 'clients'=>post('clients'), 'satisfaction'=>post('satisfaction'),
        'icon'=>post('icon','fa-building'), 'logo'=>$logoUrl, 'short_desc'=>post('short_desc'), 'about'=>post('about'),
        'services'=>jenc(jarr(post('services'))), 'achievements'=>jenc(jarr(post('achievements'))), 'tags'=>jenc(jarr(post('tags'))),
        'phone'=>post('phone'), 'email'=>post('email'), 'whatsapp'=>post('whatsapp'), 'website'=>post('website'),
        'color'=>post('color'), 'lat'=>post('lat')!==''?post('lat'):null, 'lng'=>post('lng')!==''?post('lng'):null,
        'is_active'=>post('is_active','1')==='1'?1:0, 'user_id'=>$user_id?:null, 'owner_email'=>$session_email?:null,
      ];
      $data = array_intersect_key($data, array_flip($ac));
      if ($id>0) {
        $sets=[]; $vals=[]; foreach($data as $k=>$v){ $sets[]="`$k`=?"; $vals[]=$v; } $vals[]=$id;
        $pdo->prepare("UPDATE agencies SET ".implode(',',$sets)." WHERE id=?")->execute($vals);
        flash_set('ok','Agency/company updated.');
      } else {
        $keys=array_keys($data); $vals=array_values($data);
        $pdo->prepare("INSERT INTO agencies (`".implode('`,`',$keys)."`) VALUES (".implode(',',array_fill(0,count($keys),'?')).")")->execute($vals);
        flash_set('ok','Agency/company saved to database.');
      }
      redir_self('tab=agencies');
    }
  } catch(Throwable $e) {
    $tab = post('tab', (strpos(post('action',''),'property')!==false ? 'properties' : (strpos(post('action',''),'agency')!==false ? 'agencies' : 'overview')));
    $msg = $e->getMessage();
    if ($e instanceof PDOException) {
      $info = $e->errorInfo ?? [];
      $msg .= ' [SQLSTATE ' . ($info[0] ?? $e->getCode()) . ($info[1] ? '/' . $info[1] : '') . ']';
      if (!empty($info[2]) && $info[2] !== $msg) $msg .= ': ' . $info[2];
    }
    flash_set('err', 'Save failed: ' . $msg);
    redir_self('tab=' . preg_replace('/[^a-z]/','',$tab));
  }
}

// --- Load data ---
$profile = ['full_name'=>$session_name,'email'=>$session_email,'phone'=>'','location'=>'','bio'=>'','account_role'=>$role,'company_name'=>''];
try {
  if (table_exists($pdo,'user_profiles')) {
    if ($user_id>0) { $s=$pdo->prepare("SELECT * FROM user_profiles WHERE user_id=? ORDER BY id DESC LIMIT 1"); $s->execute([$user_id]); $r=$s->fetch(); if($r) $profile=array_merge($profile,$r); }
    if (empty($profile['id']) && $session_email) { $s=$pdo->prepare("SELECT * FROM user_profiles WHERE email=? ORDER BY id DESC LIMIT 1"); $s->execute([$session_email]); $r=$s->fetch(); if($r) $profile=array_merge($profile,$r); }
  }
} catch(Throwable $e){}
$display_name = $profile['full_name'] ?: $session_name;
$display_email = $profile['email'] ?: $session_email;
$display_role = $profile['account_role'] ?: $role;
$initials = strtoupper(substr(preg_replace('/\s+/', ' ', trim($display_name)),0,1) . (preg_match('/\s(\S)/',$display_name,$m)?$m[1]:''));
if(strlen($initials)<2) $initials = strtoupper(substr($display_name,0,2) ?: 'GU');

$properties=[]; $agencies=[]; $propTotal=0;
if (!($pdo instanceof PDO)) { $flash = $flash ?: ['type'=>'err','msg'=>'Database connection failed. Start MySQL in XAMPP and confirm `haventra` database credentials.']; }
else try { if(table_exists($pdo,'properties')) {
  $pc=cols($pdo,'properties');
  try { $propTotal = (int)$pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn(); } catch(Throwable $e){}
  $w=[];$p=[]; if($user_id>0 && in_array('user_id',$pc,true)){ $w[]='user_id=?'; $p[]=$user_id; } if($display_email && in_array('owner_email',$pc,true)){ $w[]='owner_email=?'; $p[]=$display_email; }
  $sql="SELECT * FROM properties".($w?' WHERE '.implode(' OR ',$w):'')." ORDER BY ".(in_array('id',$pc,true)?'id':'1')." DESC LIMIT 200"; $s=$pdo->prepare($sql); $s->execute($p); $properties=$s->fetchAll();
  if(!$properties && $propTotal>0){ $s=$pdo->query("SELECT * FROM properties ORDER BY ".(in_array('id',$pc,true)?'id':'1')." DESC LIMIT 50"); $properties=$s?$s->fetchAll():[]; }
} } catch(Throwable $e){ $flash = $flash ?: ['type'=>'err','msg'=>'Could not load properties: '.$e->getMessage()]; }
try { if(table_exists($pdo,'agencies')) { $ac=cols($pdo,'agencies'); $w=[];$p=[]; if($user_id>0 && in_array('user_id',$ac,true)){ $w[]='user_id=?'; $p[]=$user_id; } if($display_email && in_array('owner_email',$ac,true)){ $w[]='owner_email=?'; $p[]=$display_email; } $sql="SELECT * FROM agencies".($w?' WHERE '.implode(' OR ',$w):'')." ORDER BY ".(in_array('id',$ac,true)?'id':'1')." DESC LIMIT 200"; $s=$pdo->prepare($sql); $s->execute($p); $agencies=$s->fetchAll(); } } catch(Throwable $e){}
$flash = flash_get();
$tab = preg_replace('/[^a-z]/','', $_GET['tab'] ?? 'overview') ?: 'overview';
$edit_prop = null; $edit_ag = null;
try { if(isset($_GET['edit_property']) && table_exists($pdo,'properties')){ $s=$pdo->prepare("SELECT * FROM properties WHERE id=? LIMIT 1"); $s->execute([(int)$_GET['edit_property']]); $edit_prop=$s->fetch() ?: null; $tab='properties'; }
      if(isset($_GET['edit_agency']) && table_exists($pdo,'agencies')){ $s=$pdo->prepare("SELECT * FROM agencies WHERE id=? LIMIT 1"); $s->execute([(int)$_GET['edit_agency']]); $edit_ag=$s->fetch() ?: null; $tab='agencies'; } } catch(Throwable $e){}
$propCount = count($properties); $agencyCount = count($agencies);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard — HAVENTRA</title>
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { navy:'#00215E', navyLight:'#001540', accentOrange:'#F59E0B', accentBlue:'#005BB5', dashGreen:'#059669' }, fontFamily: { sans:['Plus Jakarta Sans','sans-serif'], serif:['Cormorant Garamond','serif'] } } } }
    </script>
    <style>
        *{-webkit-tap-highlight-color:transparent} body{font-family:'Plus Jakarta Sans',sans-serif}
        .nav-link{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:500;color:#64748B;transition:.25s;cursor:pointer}
        .nav-link:hover{background:#F1F5F9;color:#00215E}
        .nav-link.active{background:linear-gradient(135deg,#00215E,#005BB5);color:#fff;box-shadow:0 6px 16px -4px rgba(0,91,181,.4)}
        .nav-link.active .nav-icon{color:#F59E0B}
        .nav-icon{font-size:14px;width:18px;text-align:center}
        .stat-card{transition:.35s}.stat-card:hover{transform:translateY(-4px);box-shadow:0 20px 30px -10px rgba(0,0,0,.1)}
        .dash-section{display:none}.dash-section.active{display:block;animation:fadeIn .35s ease both}
        @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        #sidebarOverlay{display:none}#sidebarOverlay.active{display:block}
        @media(max-width:1023px){#sidebar{transform:translateX(-100%);transition:transform .3s}#sidebar.open{transform:translateX(0)}}
        .role-btn{padding:6px 12px;border-radius:8px;font-size:10px;font-weight:600;text-transform:uppercase;cursor:pointer;transition:.25s}
        .role-btn.active{background:#fff;color:#00215E;box-shadow:0 2px 8px rgba(0,0,0,.15)} .role-btn:not(.active){color:rgba(255,255,255,.7)}
        .spinner{width:14px;height:14px;border:2px solid rgba(255,255,255,.45);border-top-color:#fff;border-radius:999px;display:inline-block;animation:spin .7s linear infinite;vertical-align:-2px;margin-right:6px}
        .btn-danger .spinner,.role-btn .spinner,.nav-link .spinner,a .spinner,button .spinner{border-color:rgba(0,33,94,.2);border-top-color:#00215E}
        .btn-primary .spinner{border-color:rgba(255,255,255,.45);border-top-color:#fff}
        .is-loading{position:relative;opacity:.82;pointer-events:none}
        .loc-wrap{position:relative}
        .input-spinner{position:absolute;right:132px;top:50%;transform:translateY(-50%);width:14px;height:14px;border:2px solid rgba(0,33,94,.15);border-top-color:#00215E;border-radius:999px;animation:spin .7s linear infinite;display:none}
        .loc-wrap.is-searching .input-spinner{display:block}
        @keyframes spin{to{transform:rotate(360deg)}}
        .thin-scroll::-webkit-scrollbar{width:5px;height:5px}.thin-scroll::-webkit-scrollbar-thumb{background:#CBD5E1;border-radius:3px}
        .input{width:100%;padding:10px 12px;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;font-size:12px;color:#334155;outline:none}
        .input:focus{border-color:#005BB5;box-shadow:0 0 0 3px rgba(0,91,181,.12);background:#fff}
        .label{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.04em;font-weight:700;color:#64748B;margin-bottom:6px}
        .btn-primary{background:#00215E;color:#fff;font-size:12px;font-weight:700;padding:10px 16px;border-radius:10px;transition:.2s}
        .btn-primary:hover{background:#005BB5}
        .btn-danger{border:1px solid #fecaca;color:#dc2626;font-size:11px;font-weight:600;padding:8px 12px;border-radius:10px}
        .btn-danger:hover{background:#fef2f2}
        .card{background:#fff;border:1px solid #f1f5f9;border-radius:16px}
        .spinner{width:14px;height:14px;border:2px solid rgba(255,255,255,.45);border-top-color:#fff;border-radius:999px;display:inline-block;animation:spin .7s linear infinite;vertical-align:-2px;margin-right:6px}
        .btn-danger .spinner,.role-btn .spinner,.nav-link .spinner,a .spinner,button .spinner{border-color:rgba(0,33,94,.2);border-top-color:#00215E}
        .btn-primary .spinner{border-color:rgba(255,255,255,.45);border-top-color:#fff}
        .is-loading{position:relative;opacity:.82;pointer-events:none}
        .loc-wrap{position:relative}
        .input-spinner{position:absolute;right:132px;top:50%;transform:translateY(-50%);width:14px;height:14px;border:2px solid rgba(0,33,94,.15);border-top-color:#00215E;border-radius:999px;animation:spin .7s linear infinite;display:none}
        .loc-wrap.is-searching .input-spinner{display:block}
        @keyframes spin{to{transform:rotate(360deg)}}
        .modal-backdrop{display:none;position:fixed;inset:0;background:rgba(2,6,23,.58);backdrop-filter:blur(2px);z-index:80;align-items:flex-start;justify-content:center;padding:18px;overflow:auto}
        .modal-backdrop.active{display:flex}
        .modal-panel{width:100%;max-width:980px;background:#fff;border-radius:18px;border:1px solid #e2e8f0;box-shadow:0 30px 80px rgba(2,6,23,.25);margin:12px auto;max-height:90vh;overflow-y:auto}
        @media(max-width:640px){.modal-backdrop{padding:8px}.modal-panel{border-radius:14px}}
    </style>
</head>
<body class="bg-[#F1F5F9] text-gray-800 antialiased">
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="closeSidebar()"></div>

<aside id="sidebar" class="fixed top-0 left-0 h-full w-[260px] bg-white z-50 lg:z-30 shadow-xl lg:shadow-none lg:border-r lg:border-slate-200 flex flex-col">
  <div class="px-5 py-5 border-b border-slate-100 flex items-center justify-between">
    <a href="<?= h(BASE_PATH) ?>" class="flex items-center gap-2">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-accentBlue flex items-center justify-center"><i class="fas fa-home text-white text-sm"></i></div>
      <div><p class="font-serif text-lg text-navy font-semibold leading-none">HAVENTRA</p><p class="text-[9px] text-slate-400 uppercase tracking-widest font-semibold">Dashboard</p></div>
    </a>
    <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100"><i class="fas fa-times text-sm"></i></button>
  </div>
  <div class="px-4 py-4 border-b border-slate-100">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-11 h-11 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-sm"><?= h($initials) ?></div>
      <div class="flex-1 min-w-0"><p class="text-sm font-bold text-navy truncate"><?= h($display_name) ?></p><p class="text-[10px] text-slate-400 truncate"><?= h($display_email ?: 'No email in session') ?></p></div>
    </div>
    <div class="bg-navy rounded-xl p-1 flex gap-1">
      <button onclick="switchRole('client', this)" class="role-btn <?= $display_role==='client'?'active':'' ?> flex-1" data-role="client">Client</button>
      <button onclick="switchRole('owner', this)" class="role-btn <?= $display_role==='owner'?'active':'' ?> flex-1" data-role="owner">Owner</button>
      <button onclick="switchRole('company', this)" class="role-btn <?= in_array($display_role,['company','agency'])?'active':'' ?> flex-1" data-role="company">Company</button>
    </div>
  </div>
  <nav class="flex-1 overflow-y-auto thin-scroll px-3 py-4">
    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 px-3 mb-2">Manage</p>
    <div class="space-y-1">
      <a onclick="showSection('overview', this)" class="nav-link <?= $tab==='overview'?'active':'' ?>"><i class="fas fa-th-large nav-icon"></i><span>Overview</span></a>
      <a onclick="showSection('properties', this)" class="nav-link <?= $tab==='properties'?'active':'' ?>"><i class="fas fa-building nav-icon"></i><span>My Properties</span><span class="ml-auto text-[9px] bg-accentOrange/15 text-accentOrange font-bold px-1.5 py-0.5 rounded-full"><?= (int)$propCount ?></span></a>
      <a onclick="showSection('agencies', this)" class="nav-link <?= $tab==='agencies'?'active':'' ?>"><i class="fas fa-handshake nav-icon"></i><span>My Companies/Agencies</span><span class="ml-auto text-[9px] bg-accentBlue/15 text-accentBlue font-bold px-1.5 py-0.5 rounded-full"><?= (int)$agencyCount ?></span></a>
      <a onclick="showSection('bookings', this)" class="nav-link"><i class="fas fa-calendar-check nav-icon"></i><span>Bookings</span></a>
      <a onclick="showSection('messages', this)" class="nav-link"><i class="fas fa-comments nav-icon"></i><span>Messages</span></a>
    </div>
    <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 px-3 mt-5 mb-2">Account</p>
    <div class="space-y-1">
      <a onclick="showSection('profile', this)" class="nav-link <?= $tab==='profile'?'active':'' ?>"><i class="fas fa-user-circle nav-icon"></i><span>Profile</span></a>
      <a onclick="showSection('help', this)" class="nav-link"><i class="fas fa-question-circle nav-icon"></i><span>Help</span></a>
    </div>
  </nav>
  <div class="px-4 py-3 border-t border-slate-100"><a href="<?= h(BASE_PATH) ?>?page=logout" class="w-full flex items-center gap-2 text-xs text-slate-500 hover:text-red-500 font-medium transition-colors"><i class="fas fa-sign-out-alt"></i><span>Sign Out</span></a></div>
</aside>

<div class="lg:ml-[260px] min-h-screen">
  <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="px-4 md:px-6 py-3 flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <button onclick="openSidebar()" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600"><i class="fas fa-bars text-sm"></i></button>
        <div><h1 id="pageTitle" class="font-serif text-lg md:text-xl text-navy font-semibold leading-tight">Dashboard</h1><p id="pageSubtitle" class="text-[10px] md:text-xs text-slate-400">Shared dashboard · roles separated by session later</p></div>
      </div>
      <div class="flex items-center gap-2"><a href="<?= h(BASE_PATH) ?>" class="text-xs font-semibold text-accentBlue hover:underline">Back to site</a><div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-[10px]"><?= h($initials) ?></div></div>
    </div>
  </header>
  <main class="p-4 md:p-6 pb-20">
    <?php if($flash): ?>
      <div id="dashFlash" class="mb-4 px-4 py-3 rounded-xl text-sm font-semibold flex items-start gap-2 <?= $flash['type']==='ok'?'bg-emerald-50 text-emerald-700 border border-emerald-200':'bg-red-50 text-red-700 border border-red-200' ?>"><i class="fas <?= $flash['type']==='ok'?'fa-check-circle':'fa-exclamation-circle' ?> mt-0.5"></i><span><?= h($flash['msg']) ?></span></div>
    <?php endif; ?>
    <?php if($user_id===0): ?>
      <div class="mb-4 px-4 py-3 rounded-xl text-sm bg-amber-50 text-amber-800 border border-amber-200">No active session user detected. Data loads/saves by session/email context where available; role separation will be enforced by session later.</div>
    <?php endif; ?>
    <section id="section-overview" class="dash-section <?= $tab==='overview'?'active':'' ?>">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-5">
        <div class="stat-card card p-4"><p class="text-[10px] text-slate-400 font-medium">My Properties</p><p class="text-2xl font-bold text-navy mt-1"><?= (int)$propCount ?></p></div>
        <div class="stat-card card p-4"><p class="text-[10px] text-slate-400 font-medium">Companies/Agencies</p><p class="text-2xl font-bold text-navy mt-1"><?= (int)$agencyCount ?></p></div>
        <div class="stat-card card p-4"><p class="text-[10px] text-slate-400 font-medium">Account Role</p><p class="text-lg font-bold text-navy mt-1 capitalize"><?= h($display_role) ?></p></div>
        <div class="stat-card card p-4"><p class="text-[10px] text-slate-400 font-medium">DB Status</p><p class="text-lg font-bold text-emerald-600 mt-1"><i class="fas fa-database mr-1"></i>Connected</p></div>
      </div>
      <div class="card p-5">
        <h3 class="font-serif text-lg text-navy font-semibold mb-2">Account Management</h3>
        <p class="text-sm text-slate-600">This dashboard is shared by clients, house owners, and companies/agencies. Use <b>My Properties</b> to manage house/property records and <b>My Companies/Agencies</b> to manage company profiles. All forms map to the database fields used by existing modals/listings. Role-based access will be enforced by session in a later step.</p>
        <div class="mt-4 flex flex-wrap gap-2">
          <button onclick="showSection('properties',null)" class="btn-primary"><i class="fas fa-plus mr-1"></i>Manage Properties</button>
          <button onclick="showSection('agencies',null)" class="btn-primary"><i class="fas fa-handshake mr-1"></i>Manage Companies/Agencies</button>
          <button onclick="showSection('profile',null)" class="btn-primary"><i class="fas fa-user mr-1"></i>Edit Profile</button>
        </div>
      </div>
    </section>
    <section id="section-properties" class="dash-section <?= $tab==='properties'?'active':'' ?>">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div><h2 class="font-serif text-2xl text-navy font-semibold">My Properties</h2><p class="text-xs text-slate-400 mt-0.5">Manage house/property listings in the database (all modal fields included).</p></div>
        <button onclick="openPropertyModal()" class="btn-primary self-start"><i class="fas fa-plus mr-1"></i>Add Property</button>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-5">
        <?php foreach($properties as $p): $img=h($p['image_url']??''); $st=(int)($p['is_active']??1); $pjson = h(json_encode($p, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)); ?>
        <div class="card overflow-hidden">
          <div class="relative h-36 bg-slate-100"><?php if($img): ?><img src="<?= $img ?>" alt="" class="w-full h-full object-cover"><?php endif; ?><span class="absolute top-2 left-2 text-[9px] font-bold px-2 py-0.5 rounded-full <?= $st?'bg-emerald-600 text-white':'bg-slate-500 text-white' ?>"><?= $st?'Active':'Inactive' ?></span></div>
          <div class="p-3">
            <h3 class="font-serif text-sm text-navy font-semibold truncate"><?= h($p['title']??'Untitled') ?></h3>
            <p class="text-[10px] text-slate-400 mt-0.5 truncate"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i><?= h($p['location']??'') ?></p>
            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100"><span class="text-sm font-bold text-navy">KSh <?= h($p['price']??'') ?> <span class="text-[9px] text-slate-400 font-normal"><?= h($p['price_suffix']??'') ?></span></span><span class="text-[9px] text-slate-400 capitalize"><?= h($p['type']??'') ?></span></div>
            <div class="flex gap-2 mt-2">
              <button type="button" class="flex-1 text-center text-[11px] font-bold px-3 py-2 rounded-lg bg-slate-100 text-navy hover:bg-slate-200" onclick='openPropertyModal(<?= $pjson ?>)'>Edit</button>
              <form method="post" onsubmit="return confirm('Delete this property?')"><input type="hidden" name="action" value="delete_property"><input type="hidden" name="id" value="<?= (int)($p['id']??0) ?>"><button class="btn-danger" type="submit">Delete</button></form>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(!$properties): ?><div class="card p-5 text-sm <?= $propTotal>0?'text-amber-700 bg-amber-50 border-amber-200':'text-slate-500' ?>">No properties found for this account context<?= $propTotal>0?(' ('.$propTotal.' total exist in `properties`; ownership/session filter may not match)').'':'' ?>. Add one below.</div><?php endif; ?>
      </div>

      <div id="propertyFormWrap" style="display:none"></div>
      <div id="propertyModal" class="modal-backdrop <?= $edit_prop?'active':'' ?>" onclick="if(event.target===this)closePropertyModal()">
        <div class="modal-panel p-5">
        <div class="flex items-center justify-between gap-2 mb-4"><h3 id="propertyModalTitle" class="font-serif text-lg text-navy font-semibold"><?= $edit_prop?'Edit Property':'Add Property' ?></h3><div class="flex items-center gap-2"><?php if($edit_prop): ?><a class="text-xs font-semibold text-accentBlue hover:underline" href="<?= h(BASE_PATH) ?>?page=profile&tab=properties">New property</a><?php endif; ?><button type="button" class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200" onclick="closePropertyModal()" aria-label="Close"><i class="fas fa-times"></i></button></div></div>
        <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4" id="propertyForm">
          <input type="hidden" name="action" value="save_property"><input type="hidden" name="tab" value="properties"><input type="hidden" name="id" value="<?= (int)($edit_prop['id']??0) ?>">
          <input type="hidden" name="lat" id="propLat" value="<?= h($edit_prop['lat']??'') ?>">
          <input type="hidden" name="lng" id="propLng" value="<?= h($edit_prop['lng']??'') ?>">
          <input type="hidden" name="existing_video_url" value="<?= h($edit_prop['video_url']??'') ?>">
          <input type="hidden" name="date_added" value="<?= h($edit_prop['date_added'] ?? date('Y-m-d')) ?>">
          <div class="md:col-span-2"><label class="label">Title</label><input class="input" name="title" required value="<?= h($edit_prop['title']??'') ?>"></div>

          <div class="md:col-span-2 relative">
            <label class="label">Location / Place / Apartment</label>
            <div class="flex gap-2 loc-wrap" id="locWrap">
              <input class="input" name="location" id="propLocation" autocomplete="off" placeholder="Start typing a place, area, or apartment..." value="<?= h($edit_prop['location']??'') ?>">
              <span class="input-spinner" aria-hidden="true"></span>
              <button type="button" id="btnUseLocation" class="shrink-0 text-[11px] font-bold px-3 py-2 rounded-lg bg-slate-100 text-navy hover:bg-slate-200 border border-slate-200" onclick="useCurrentLocation()"><i class="fas fa-location-crosshairs mr-1"></i>Use current</button>
            </div>
            <div id="locSuggestions" class="hidden absolute z-30 left-0 right-[120px] mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-auto"></div>
            <p class="text-[10px] text-slate-400 mt-1">Select a suggestion or use your current location. The selected place name, latitude, and longitude are saved to the database.</p>
          </div>

          <div><label class="label">Price</label><input class="input" name="price" value="<?= h($edit_prop['price']??'') ?>"></div>
          <div><label class="label">Price Suffix</label><input class="input" name="price_suffix" placeholder="/month, /night" value="<?= h($edit_prop['price_suffix']??'') ?>"></div>
          <div><label class="label">Type</label><select class="input" name="type"><?php foreach(['rent'=>'For Rent','booking'=>'Short Stay/Booking','sale'=>'For Sale'] as $k=>$v): ?><option value="<?= $k ?>" <?= (($edit_prop['type']??'rent')===$k)?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
          <div><label class="label">Badge</label><input class="input" name="badge" placeholder="e.g. Featured, New, Premium" value="<?= h($edit_prop['badge']??'') ?>"></div>
          <div><label class="label">Bedrooms</label><input class="input" type="number" min="0" name="beds" value="<?= h($edit_prop['beds']??'0') ?>"></div>
          <div><label class="label">Baths</label><input class="input" type="number" min="0" name="baths" value="<?= h($edit_prop['baths']??'0') ?>"></div>
          <div><label class="label">Parking</label><input class="input" type="number" min="0" name="parking" value="<?= h($edit_prop['parking']??'') ?>"></div>
          <input type="hidden" name="size" value="<?= h($edit_prop['size']??'') ?>">
          <div><label class="label">Furnished</label><select class="input" name="furnished"><option value="0" <?= ((int)($edit_prop['furnished']??0)===0)?'selected':'' ?>>No</option><option value="1" <?= ((int)($edit_prop['furnished']??0)===1)?'selected':'' ?>>Yes</option></select></div>
          <div><label class="label">Rating (optional)</label><input class="input" name="rating" step="0.1" min="0" max="5" value="<?= h($edit_prop['rating']??'') ?>"></div>
          <div><label class="label">Reviews (optional)</label><input class="input" type="number" min="0" name="reviews" value="<?= h($edit_prop['reviews']??'') ?>"></div>
          <div><label class="label">Agent Name (optional)</label><input class="input" name="agent_name" value="<?= h($edit_prop['agent_name']??'') ?>"></div>
          <div><label class="label">Agent Initial (optional)</label><input class="input" name="agent_initial" maxlength="10" value="<?= h($edit_prop['agent_initial']??'') ?>"></div>
          <div><label class="label">Active Status</label><select class="input" name="is_active"><option value="1" <?= ((int)($edit_prop['is_active']??1)===1)?'selected':'' ?>>Active / Published</option><option value="0" <?= ((int)($edit_prop['is_active']??1)===0)?'selected':'' ?>>Inactive / Draft</option></select></div>

          <div class="md:col-span-2 border-t border-slate-100 pt-3">
            <label class="label">Property Images (uploads, up to 6, optional)</label>
            <div id="propExistingImages" class="flex flex-wrap gap-2 mb-2"><?php $existingImgs = array_values(array_filter(decj($edit_prop['image_urls']??'[]'))); if(!$existingImgs && !empty($edit_prop['image_url'])) $existingImgs = [$edit_prop['image_url']]; ?><?php if($existingImgs): ?><?php foreach(array_slice($existingImgs,0,6) as $im): ?><label class="flex items-center gap-2 text-[10px] bg-slate-50 border border-slate-200 rounded-lg px-2 py-1"><input type="checkbox" name="keep_images[]" value="<?= h($im) ?>" checked><img src="<?= h($im) ?>" alt="" class="w-10 h-8 object-cover rounded"><?= h(basename((string)$im)) ?></label><?php endforeach; ?><?php endif; ?></div>
            <p class="text-[10px] text-slate-400 mb-2">Uncheck to remove an existing image. New uploads are added; maximum 6 images total are kept.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
              <?php for($i=1;$i<=6;$i++): ?><input class="input text-xs" type="file" name="photo<?= $i ?>" accept="image/jpeg,image/png,image/webp,image/gif"><?php endfor; ?>
            </div>
          </div>
          <div class="md:col-span-2">
            <label class="label">Image URLs fallback (optional)</label>
            <textarea class="input" name="image_urls" rows="2" placeholder="Optional: one image URL per line or comma-separated. Uploads are preferred."></textarea>
          </div>

          <div class="md:col-span-2 border-t border-slate-100 pt-3">
            <label class="label">Property Video (upload, optional, max 5MB)</label>
            <input class="input" type="file" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime">
            <div id="propExistingVideo"><?php if(!empty($edit_prop['video_url'])): ?><p class="text-[10px] text-slate-500 mt-1">Current video: <a class="text-accentBlue hover:underline" href="<?= h($edit_prop['video_url']) ?>" target="_blank" rel="noopener"><?= h($edit_prop['video_url']) ?></a></p><?php endif; ?></div>
            <p class="text-[10px] text-slate-400 mt-1">Accepted: MP4/WebM/Ogg/MOV. Maximum size: 5MB.</p>
          </div>

          <div class="md:col-span-2"><label class="label">Amenities (one per line or comma; saved to JSON)</label><textarea class="input" name="amenities" rows="3" placeholder="Wi-Fi, Parking, Security, Garden, Air Conditioning, Gym, Furnished, Water, Backup Generator"><?php $am=decj($edit_prop['amenities']??'[]'); echo h(implode("\n", array_map('strval',$am))); ?></textarea></div>
          <div class="md:col-span-2"><label class="label">Description</label><textarea class="input" name="description" rows="4"><?= h($edit_prop['description']??'') ?></textarea></div>
          <div class="md:col-span-2 flex gap-2 pt-1"><button class="btn-primary" type="submit"><i class="fas fa-save mr-1"></i><?= $edit_prop?'Update':'Save' ?> Property</button><button type="button" class="text-[12px] font-bold px-4 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200" onclick="closePropertyModal()">Cancel</button></div>
        </form>
        </div>
      </div>
    </section>
    <section id="section-agencies" class="dash-section <?= $tab==='agencies'?'active':'' ?>">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <div><h2 class="font-serif text-2xl text-navy font-semibold">My Companies & Agencies</h2><p class="text-xs text-slate-400 mt-0.5">Manage company/agency profiles using all fields from existing agency modals.</p></div>
        <button type="button" onclick="openAgencyModal()" class="btn-primary self-start"><i class="fas fa-plus mr-1"></i>Add Company</button>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
        <?php foreach($agencies as $a): $ajson = h(json_encode($a, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)); ?>
        <div class="card p-4">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden shrink-0"><?php if(!empty($a['logo'])): ?><img src="<?= h($a['logo']) ?>" alt="" class="w-full h-full object-cover"><?php else: ?><i class="fas <?= h($a['icon']??'fa-building') ?> text-navy"></i><?php endif; ?></div>
            <div class="min-w-0 flex-1">
              <h3 class="font-serif text-base text-navy font-semibold truncate"><?= h($a['name']??'Company') ?></h3>
              <p class="text-[10px] text-slate-400 truncate"><?= h($a['category_label']??$a['category']??'') ?> · <?= h($a['location']??$a['city']??'') ?></p>
              <p class="text-[10px] text-slate-500 mt-1 line-clamp-2"><?= h($a['short_desc']??'') ?></p>
            </div>
            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full <?= (int)($a['is_active']??1)?'bg-emerald-100 text-emerald-700':'bg-slate-200 text-slate-600' ?>"><?= (int)($a['is_active']??1)?'Active':'Inactive' ?></span>
          </div>
          <div class="flex gap-2 mt-3">
            <button type="button" class="flex-1 text-center text-[11px] font-bold px-3 py-2 rounded-lg bg-slate-100 text-navy hover:bg-slate-200" onclick='openAgencyModal(<?= $ajson ?>)'>Edit</button>
            <form method="post" onsubmit="return confirm('Delete this company/agency?')"><input type="hidden" name="action" value="delete_agency"><input type="hidden" name="id" value="<?= (int)($a['id']??0) ?>"><button class="btn-danger" type="submit">Delete</button></form>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if(!$agencies): ?><div class="card p-5 text-sm text-slate-500">No company/agency records found for this account context yet. Add one with the button above.</div><?php endif; ?>
      </div>

      <div id="agencyFormWrap" style="display:none"></div>
      <div id="agencyModal" class="modal-backdrop <?= $edit_ag?'active':'' ?>" onclick="if(event.target===this)closeAgencyModal()">
        <div class="modal-panel p-5">
          <div class="flex items-center justify-between gap-2 mb-4">
            <h3 id="agencyModalTitle" class="font-serif text-lg text-navy font-semibold"><?= $edit_ag?'Edit Company/Agency':'Add Company/Agency' ?></h3>
            <div class="flex items-center gap-2"><button type="button" class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200" onclick="closeAgencyModal()" aria-label="Close"><i class="fas fa-times"></i></button></div>
          </div>
          <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4" id="agencyForm">
            <input type="hidden" name="action" value="save_agency"><input type="hidden" name="tab" value="agencies"><input type="hidden" name="id" value="<?= (int)($edit_ag['id']??0) ?>">
            <input type="hidden" name="existing_logo" id="agencyExistingLogo" value="<?= h($edit_ag['logo']??'') ?>">
            <div class="md:col-span-2"><label class="label">Company/Agency Name</label><input class="input" name="name" required value="<?= h($edit_ag['name']??'') ?>"></div>
            <div><label class="label">Category (agency/management/cleaning)</label><select class="input" name="category"><?php foreach(['agency'=>'Real Estate Agency','management'=>'Property Management','cleaning'=>'Cleaning Company'] as $k=>$v): ?><option value="<?= $k ?>" <?= (($edit_ag['category']??'agency')===$k)?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
            <div><label class="label">Category Label</label><input class="input" name="category_label" value="<?= h($edit_ag['category_label']??'') ?>"></div>
            <div><label class="label">Location</label><input class="input" name="location" value="<?= h($edit_ag['location']??'') ?>" placeholder="Type address"></div>
            <div><label class="label">City</label><input class="input" name="city" value="<?= h($edit_ag['city']??'') ?>"></div>
            <div><label class="label">Rating</label><input class="input" name="rating" value="<?= h($edit_ag['rating']??'') ?>"></div>
            <div><label class="label">Reviews</label><input class="input" type="number" name="reviews" value="<?= h($edit_ag['reviews']??'0') ?>"></div>
            <div><label class="label">Years</label><input class="input" type="number" name="years" value="<?= h($edit_ag['years']??'0') ?>"></div>
            <div><label class="label">Years Label</label><input class="input" name="years_label" value="<?= h($edit_ag['years_label']??'') ?>"></div>
            <div><label class="label">Clients</label><input class="input" name="clients" value="<?= h($edit_ag['clients']??'') ?>"></div>
            <div><label class="label">Satisfaction</label><input class="input" name="satisfaction" value="<?= h($edit_ag['satisfaction']??'') ?>"></div>

            <div class="md:col-span-2 border-t border-slate-100 pt-3">
              <label class="label">Company Logo</label>
              <div class="flex flex-wrap items-start gap-3">
                <div class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                  <img id="agencyLogoPreview" src="<?= h($edit_ag['logo']??'') ?>" alt="Logo preview" class="w-full h-full object-cover <?= !empty($edit_ag['logo'])?'':'hidden' ?>" onerror="this.classList.add('hidden');document.getElementById('agencyLogoPlaceholder').classList.remove('hidden')">
                  <i id="agencyLogoPlaceholder" class="fas fa-building text-slate-300 text-2xl <?= !empty($edit_ag['logo'])?'hidden':'' ?>"></i>
                </div>
                <div class="flex-1 min-w-[220px] space-y-2">
                  <input class="input text-xs" type="file" name="logo_file" id="agencyLogoFile" accept="image/*" onchange="previewAgencyLogo(this)">
                  <input class="input" name="logo" id="agencyLogoUrl" value="<?= h($edit_ag['logo']??'') ?>" placeholder="Or paste logo image URL" oninput="previewAgencyLogoUrl(this.value)">
                  <p class="text-[10px] text-slate-400">Upload an image (max 5MB) or use a URL. The preview updates before saving.</p>
                </div>
              </div>
            </div>

            <div><label class="label">Color / Theme Key</label><input class="input" type="color" name="color" value="<?= h($edit_ag['color']?:'#4a90a0') ?>"></div>
            <input type="hidden" name="verified" value="1">
            <input type="hidden" name="icon" value="<?= h($edit_ag['icon']??'fa-building') ?>">
            <div><label class="label">Active Status (is_active)</label><select class="input" name="is_active"><option value="1" <?= ((int)($edit_ag['is_active']??1)===1)?'selected':'' ?>>Active</option><option value="0" <?= ((int)($edit_ag['is_active']??1)===0)?'selected':'' ?>>Inactive</option></select></div>
            <div class="md:col-span-2"><label class="label">Short Description (short_desc)</label><textarea class="input" name="short_desc" rows="2"><?= h($edit_ag['short_desc']??'') ?></textarea></div>
            <div class="md:col-span-2"><label class="label">About</label><textarea class="input" name="about" rows="4"><?= h($edit_ag['about']??'') ?></textarea></div>
            <div class="md:col-span-2"><label class="label">Services (one per line or comma; JSON)</label><textarea class="input" name="services" rows="3"><?php echo h(implode("\n", array_map('strval', decj($edit_ag['services']??'[]')))); ?></textarea></div>
            <div class="md:col-span-2"><label class="label">Achievements (one per line or comma; JSON)</label><textarea class="input" name="achievements" rows="3"><?php echo h(implode("\n", array_map('strval', decj($edit_ag['achievements']??'[]')))); ?></textarea></div>
            <div class="md:col-span-2"><label class="label">Tags (one per line or comma; JSON)</label><textarea class="input" name="tags" rows="2"><?php echo h(implode("\n", array_map('strval', decj($edit_ag['tags']??'[]')))); ?></textarea></div>
            <div><label class="label">Phone</label><input class="input" name="phone" value="<?= h($edit_ag['phone']??'') ?>"></div>
            <div><label class="label">Email</label><input class="input" name="email" value="<?= h($edit_ag['email']??'') ?>"></div>
            <div><label class="label">WhatsApp</label><input class="input" name="whatsapp" value="<?= h($edit_ag['whatsapp']??'') ?>"></div>
            <div><label class="label">Website</label><input class="input" name="website" value="<?= h($edit_ag['website']??'') ?>"></div>

            <div class="md:col-span-2 flex gap-2 pt-1">
              <button class="btn-primary" type="submit"><i class="fas fa-save mr-1"></i><?= $edit_ag?'Update':'Save' ?> Company/Agency</button>
              <button type="button" class="text-[12px] font-bold px-4 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200" onclick="closeAgencyModal()">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <section id="section-bookings" class="dash-section"><div class="card p-5"><h2 class="font-serif text-2xl text-navy font-semibold">Bookings</h2><p class="text-sm text-slate-500 mt-2">Bookings data can be connected to a bookings table in a follow-up. Account management for properties and companies/agencies is DB-connected in this dashboard.</p></div></section>
    <section id="section-messages" class="dash-section"><div class="card p-5"><h2 class="font-serif text-2xl text-navy font-semibold">Messages</h2><p class="text-sm text-slate-500 mt-2">For admin/contact messages see <a class="text-accentBlue font-semibold hover:underline" href="<?= h(BASE_PATH) ?>?page=messages">messages</a>. User inbox integration can be added with session roles.</p></div></section>

    <section id="section-profile" class="dash-section <?= $tab==='profile'?'active':'' ?>">
      <div class="mb-4"><h2 class="font-serif text-2xl text-navy font-semibold">Profile</h2><p class="text-xs text-slate-400 mt-0.5">Manage account details in the database.</p></div>
      <div class="card p-5">
        <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input type="hidden" name="action" value="save_profile"><input type="hidden" name="tab" value="profile">
          <div><label class="label">Full Name</label><input class="input" name="full_name" value="<?= h($profile['full_name']??'') ?>"></div>
          <div><label class="label">Email</label><input class="input" type="email" name="email" value="<?= h($profile['email']??'') ?>"></div>
          <div><label class="label">Phone</label><input class="input" name="phone" value="<?= h($profile['phone']??'') ?>"></div>
          <div><label class="label">Location</label><input class="input" name="location" value="<?= h($profile['location']??'') ?>"></div>
          <div><label class="label">Account Role (session will control later)</label><select class="input" name="account_role"><?php foreach(['client','owner','company','agency'] as $r): ?><option value="<?= $r ?>" <?= (($profile['account_role']??$display_role)===$r)?'selected':'' ?>><?= ucfirst($r) ?></option><?php endforeach; ?></select></div>
          <div><label class="label">Company Name (if company/agency)</label><input class="input" name="company_name" value="<?= h($profile['company_name']??'') ?>"></div>
          <div class="md:col-span-2"><label class="label">Bio</label><textarea class="input" name="bio" rows="4"><?= h($profile['bio']??'') ?></textarea></div>
          <div class="md:col-span-2"><button class="btn-primary" type="submit"><i class="fas fa-save mr-1"></i>Save Profile</button></div>
        </form>
      </div>
    </section>

    <section id="section-help" class="dash-section"><div class="card p-5"><h2 class="font-serif text-2xl text-navy font-semibold">Help</h2><p class="text-sm text-slate-500 mt-2">Use My Properties and My Companies/Agencies to create/update records in the same database tables used by marketplace and agency modals. JSON fields accept one item per line or comma-separated values.</p></div></section>
  </main>
</div>

<script>
function setBtnLoading(el, loading, text){
  if(!el) return;
  if(loading){
    if(el.dataset.loading==='1') return;
    el.dataset.loading='1'; el.dataset.orig = el.innerHTML; el.classList.add('is-loading');
    if(el.tagName==='BUTTON' || el.tagName==='A') el.innerHTML = '<span class="spinner" aria-hidden="true"></span>' + (text||'Loading...');
  } else {
    if(el.dataset.orig) el.innerHTML = el.dataset.orig;
    el.classList.remove('is-loading'); delete el.dataset.loading; delete el.dataset.orig;
  }
}
function withBtnLoader(el, text, delay){ setBtnLoading(el,true,text); setTimeout(()=>setBtnLoading(el,false), delay||1200); }
function openSidebar(){ document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebarOverlay').classList.add('active'); }
function closeSidebar(){ document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebarOverlay').classList.remove('active'); }
function showSection(name, navEl){
  if(navEl) withBtnLoader(navEl, 'Loading', 350);
  document.querySelectorAll('.dash-section').forEach(s=>s.classList.remove('active'));
  const t=document.getElementById('section-'+name); if(t) t.classList.add('active');
  if(navEl){ document.querySelectorAll('.nav-link').forEach(n=>n.classList.remove('active')); navEl.classList.add('active'); }
  if(window.innerWidth<1024) closeSidebar();
  try{ const url=new URL(window.location.href); url.searchParams.set('page','profile'); url.searchParams.set('tab',name); history.replaceState(null,'',url); }catch(e){}
}
function switchRole(role, el){ if(el) withBtnLoader(el, 'Switching', 450); document.querySelectorAll('.role-btn').forEach(b=>b.classList.remove('active')); if(el) el.classList.add('active'); showToast('UI role switched to '+role+' (session-enforced separation comes later)'); }
function showToast(msg){ const e=document.createElement('div'); e.className='fixed bottom-6 left-1/2 -translate-x-1/2 bg-navy text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-xl z-[5000]'; e.textContent=msg; document.body.appendChild(e); setTimeout(()=>e.remove(),2200); }
(function(){ const f=document.getElementById('dashFlash'); if(f){ try{ f.scrollIntoView({behavior:'smooth',block:'start'}); }catch(e){} showToast(f.innerText||'Done'); } })();
document.addEventListener('click', function(e){
  const el = e.target.closest('button, a.btn-primary, .btn-danger, .nav-link, .role-btn');
  if(!el || el.dataset.noLoader==='1') return;
  if(el.closest('.role-switcher') || el.classList.contains('nav-link')) return;
  if(el.matches('[onclick*="showSection"], [onclick*="switchRole"], [onclick*="openSidebar"], [onclick*="closeSidebar"]')) return;
  if(el.tagName==='A' && (el.getAttribute('target')==='_blank' || (el.getAttribute('href')||'').startsWith('#'))) { withBtnLoader(el,'Loading',700); return; }
  withBtnLoader(el,'Loading',1500);
}, true);
function resetPropertyForm(){
  const form = document.getElementById('propertyForm'); if(!form) return;
  form.reset();
  form.querySelector('[name="id"]').value='';
  form.querySelector('[name="lat"]').value='';
  form.querySelector('[name="lng"]').value='';
  form.querySelector('[name="existing_video_url"]').value='';
  form.querySelector('[name="date_added"]').value=new Date().toISOString().slice(0,10);
  form.querySelector('[name="size"]').value='';
  const keep = document.getElementById('propExistingImages'); if(keep) keep.innerHTML='';
  const vlink = document.getElementById('propExistingVideo'); if(vlink) vlink.innerHTML='';
  const title = document.getElementById('propertyModalTitle'); if(title) title.textContent='Add Property';
  const btn = form.querySelector('button[type="submit"]'); if(btn) btn.innerHTML='<i class="fas fa-save mr-1"></i>Save Property';
  if(locBox){ locBox.classList.add('hidden'); locBox.innerHTML=''; }
}
function arrText(v){ try{ const a = typeof v==='string' ? JSON.parse(v||'[]') : v; return Array.isArray(a)?a.map(x=>String(x)).filter(Boolean).join('\n'):''; }catch(e){ return String(v||''); } }
function arrVals(v){ try{ const a = typeof v==='string' ? JSON.parse(v||'[]') : v; return Array.isArray(a)?a.map(x=>String(x)).filter(Boolean):[]; }catch(e){ return []; } }
function openPropertyModal(p){
  resetPropertyForm();
  const form = document.getElementById('propertyForm');
  if(p && form){
    form.querySelector('[name="id"]').value = p.id || '';
    ['title','location','price','price_suffix','badge','beds','baths','parking','furnished','date_added','rating','reviews','agent_name','agent_initial','is_active','type','description','lat','lng','size','existing_video_url'].forEach(k=>{ const el=form.querySelector('[name="'+k+'"]'); if(el && p[k]!==undefined && p[k]!==null) el.value = String(p[k]); });
    if(form.querySelector('[name="amenities"]')) form.querySelector('[name="amenities"]').value = arrText(p.amenities);
    const imgs = arrVals(p.image_urls); if(!imgs.length && p.image_url) imgs.push(String(p.image_url));
    const keep = document.getElementById('propExistingImages');
    if(keep) keep.innerHTML = imgs.slice(0,6).map(im=>`<label class="flex items-center gap-2 text-[10px] bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 mb-1 mr-1"><input type="checkbox" name="keep_images[]" value="${esc(im)}" checked><img src="${esc(im)}" alt="" class="w-10 h-8 object-cover rounded">${esc(String(im).split('/').pop()||'image')}</label>`).join('') || '';
    if(p.video_url){ const v=document.getElementById('propExistingVideo'); if(v) v.innerHTML=`<p class="text-[10px] text-slate-500 mt-1">Current video: <a class="text-accentBlue hover:underline" href="${esc(p.video_url)}" target="_blank" rel="noopener">${esc(p.video_url)}</a></p>`; }
    const title = document.getElementById('propertyModalTitle'); if(title) title.textContent='Edit Property';
    const btn = form.querySelector('button[type="submit"]'); if(btn) btn.innerHTML='<i class="fas fa-save mr-1"></i>Update Property';
  }
  document.getElementById('propertyModal').classList.add('active');
  document.body.style.overflow='hidden';
}
function closePropertyModal(){
  const m=document.getElementById('propertyModal'); if(m) m.classList.remove('active');
  document.body.style.overflow='';
  try{ const url=new URL(window.location.href); url.searchParams.delete('edit_property'); history.replaceState(null,'',url); }catch(e){}
}
document.addEventListener('keydown', e=>{ if(e.key==='Escape'){ closePropertyModal(); closeAgencyModal(); } });

function previewAgencyLogo(input){
  const file = input && input.files && input.files[0];
  if(!file) return;
  const url = URL.createObjectURL(file);
  const img = document.getElementById('agencyLogoPreview');
  const ph = document.getElementById('agencyLogoPlaceholder');
  if(img){ img.src = url; img.classList.remove('hidden'); }
  if(ph) ph.classList.add('hidden');
}
function previewAgencyLogoUrl(url){
  const img = document.getElementById('agencyLogoPreview');
  const ph = document.getElementById('agencyLogoPlaceholder');
  url = String(url||'').trim();
  if(!img || !ph) return;
  if(!url){ img.src=''; img.classList.add('hidden'); ph.classList.remove('hidden'); return; }
  img.onload = ()=>{ img.classList.remove('hidden'); ph.classList.add('hidden'); };
  img.onerror = ()=>{ img.classList.add('hidden'); ph.classList.remove('hidden'); };
  img.src = url;
}
function resetAgencyForm(){
  const form = document.getElementById('agencyForm'); if(!form) return;
  form.reset();
  form.querySelector('[name="id"]').value='';
  form.querySelector('[name="existing_logo"]').value='';
  form.querySelector('[name="color"]').value='#4a90a0';
  form.querySelector('[name="verified"]').value='1';
  form.querySelector('[name="is_active"]').value='1';
  form.querySelector('[name="icon"]').value='fa-building';
  ['services','achievements','tags'].forEach(n=>{ const el=form.querySelector('[name="'+n+'"]'); if(el) el.value=''; });
  previewAgencyLogoUrl('');
  const title = document.getElementById('agencyModalTitle'); if(title) title.textContent='Add Company/Agency';
  const btn = form.querySelector('button[type="submit"]'); if(btn) btn.innerHTML='<i class="fas fa-save mr-1"></i>Save Company/Agency';
}
function openAgencyModal(a){
  resetAgencyForm();
  const form = document.getElementById('agencyForm');
  if(a && form){
    Object.keys(a).forEach(k=>{
      const el = form.querySelector('[name="'+k+'"]');
      if(!el) return;
      if(el.type === 'file') return;
      if(['services','achievements','tags'].includes(k)) el.value = arrText(a[k]);
      else el.value = (a[k]===null||a[k]===undefined) ? '' : String(a[k]);
    });
    form.querySelector('[name="id"]').value = a.id || '';
    form.querySelector('[name="existing_logo"]').value = a.logo || '';
    if(form.querySelector('[name="logo"]')) form.querySelector('[name="logo"]').value = a.logo || '';
    previewAgencyLogoUrl(a.logo || '');
    const title = document.getElementById('agencyModalTitle'); if(title) title.textContent='Edit Company/Agency';
    const btn = form.querySelector('button[type="submit"]'); if(btn) btn.innerHTML='<i class="fas fa-save mr-1"></i>Update Company/Agency';
  }
  document.getElementById('agencyModal').classList.add('active');
  document.body.style.overflow='hidden';
}
function closeAgencyModal(){
  const m=document.getElementById('agencyModal'); if(m) m.classList.remove('active');
  if(!document.getElementById('propertyModal')?.classList.contains('active')) document.body.style.overflow='';
  try{ const url=new URL(window.location.href); url.searchParams.delete('edit_agency'); history.replaceState(null,'',url); }catch(e){}
}
<?php if($edit_ag): ?>
document.addEventListener('DOMContentLoaded', function(){
  try{ openAgencyModal(<?= json_encode($edit_ag, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>); }catch(e){}
});
<?php endif; ?>

// --- Location autocomplete / current location (Nominatim, client-side) ---
const locInput = document.getElementById('propLocation');
const locBox = document.getElementById('locSuggestions');
const latInput = document.getElementById('propLat');
const lngInput = document.getElementById('propLng');
let locTimer = null;
function esc(s){ return String(s||'').replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
function placeLabel(it){
  if(!it) return '';
  const a = it.address || {};
  const clean = v => {
    const s = String(v==null?'':v).trim();
    return (!s || s.toLowerCase()==='undefined' || s.toLowerCase()==='null') ? '' : s;
  };
  const primary = clean(it.name) || clean(a.amenity) || clean(a.building) || clean(a.house_name) || clean(a.office) || clean(a.shop) || clean(a.tourism) || clean(a.leisure) || clean(a.apartment) || clean(a.residential) || (clean(a.house_number) && clean(a.road) ? (clean(a.house_number)+' '+clean(a.road)) : clean(a.road)) || clean(a.neighbourhood) || clean(a.suburb) || clean(a.village) || clean(a.town) || clean(a.city_district) || clean(a.city) || clean(a.county);
  const parts = [primary, clean(a.road)!==primary?clean(a.road):'', clean(a.neighbourhood)||clean(a.suburb), clean(a.city)||clean(a.town)||clean(a.village)||clean(a.municipality), clean(a.state)||clean(a.county)].map(clean).filter(Boolean);
  const seen = new Set();
  const uniq = parts.filter(p=>{ const k=p.toLowerCase(); if(seen.has(k)) return false; seen.add(k); return true; });
  if(uniq.length) return uniq.slice(0,4).join(', ');
  const dn = String(it.display_name||'').split(',').map(s=>clean(s)).filter(Boolean);
  return dn.slice(0,3).join(', ') || clean(it.display_name);
}
function placeContext(it){
  if(!it) return '';
  const a = it.address || {};
  const clean = v => { const s=String(v==null?'':v).trim(); return (!s||s.toLowerCase()==='undefined'||s.toLowerCase()==='null')?'':s; };
  return [clean(a.road), clean(a.suburb)||clean(a.neighbourhood), clean(a.city)||clean(a.town)||clean(a.village), clean(a.state)||clean(a.county)].filter(Boolean).filter((v,i,arr)=>arr.indexOf(v)===i).slice(0,3).join(' · ');
}
function chooseLocation(name, lat, lng){
  if(locInput) locInput.value = name || '';
  if(latInput) latInput.value = (lat===undefined||lat===null)?'':lat;
  if(lngInput) lngInput.value = (lng===undefined||lng===null)?'':lng;
  if(locBox){ locBox.classList.add('hidden'); locBox.innerHTML=''; }
}
function renderSuggestions(items){
  if(!locBox) return;
  if(!items || !items.length){ locBox.innerHTML='<div class="px-3 py-2 text-[11px] text-slate-400">No suggestions found. Type a building/apartment name or use current location.</div>'; locBox.classList.remove('hidden'); return; }
  locBox.innerHTML = items.map(it=>{
    const name = placeLabel(it);
    const ctx = placeContext(it);
    const lat = it.lat || '';
    const lon = it.lon || '';
    return `<button type="button" class="w-full text-left px-3 py-2 hover:bg-slate-50 border-b border-slate-100 last:border-b-0" data-name="${esc(name)}" data-lat="${esc(lat)}" data-lon="${esc(lon)}"><div class="text-xs font-semibold text-navy line-clamp-1">${esc(name)}</div><div class="text-[10px] text-slate-400 line-clamp-1">${esc(ctx || (it.display_name||''))}</div></button>`;
  }).join('');
  locBox.querySelectorAll('button[data-name]').forEach(btn=>btn.addEventListener('click',()=>chooseLocation(btn.dataset.name, btn.dataset.lat, btn.dataset.lon)));
  locBox.classList.remove('hidden');
}
async function nominatimSearch(q){
  const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&namedetails=1&limit=7&q=' + encodeURIComponent(q);
  const res = await fetch(url, { headers: { 'Accept':'application/json' } });
  if(!res.ok) throw new Error('Search failed');
  return await res.json();
}
async function nominatimReverse(lat, lon){
  const url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&addressdetails=1&namedetails=1&zoom=18&lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lon);
  const res = await fetch(url, { headers: { 'Accept':'application/json' } });
  if(!res.ok) throw new Error('Reverse lookup failed');
  return await res.json();
}
const locWrap = document.getElementById('locWrap');
const btnUseLocation = document.getElementById('btnUseLocation');
function setLocSearching(on){ if(locWrap) locWrap.classList.toggle('is-searching', !!on); if(btnUseLocation) setBtnLoading(btnUseLocation, !!on, 'Finding...'); }
if(locInput){
  locInput.addEventListener('input', ()=>{
    const q = locInput.value.trim();
    if(latInput) latInput.value=''; if(lngInput) lngInput.value='';
    clearTimeout(locTimer);
    if(q.length < 2){ if(locBox){locBox.classList.add('hidden'); locBox.innerHTML='';} setLocSearching(false); return; }
    setLocSearching(true);
    locTimer = setTimeout(async ()=>{
      try{ renderSuggestions(await nominatimSearch(q)); }
      catch(e){ if(locBox){ locBox.innerHTML='<div class="px-3 py-2 text-[11px] text-red-500">Location search unavailable. Try again or type manually.</div>'; locBox.classList.remove('hidden'); } }
      finally{ setLocSearching(false); }
    }, 300);
  });
  document.addEventListener('click', (e)=>{ if(locBox && !locBox.contains(e.target) && e.target!==locInput) locBox.classList.add('hidden'); });
}
async function useCurrentLocation(){
  if(!navigator.geolocation){ showToast('Geolocation is not supported by this browser'); return; }
  showToast('Finding nearby place/building...');
  setLocSearching(true);
  navigator.geolocation.getCurrentPosition(async (pos)=>{
    const lat = pos.coords.latitude, lon = pos.coords.longitude;
    try{
      const place = await nominatimReverse(lat, lon);
      const opts = [place];
      try{
        const nearby = await nominatimSearch((placeLabel(place) || place.display_name || '').split(',').slice(0,2).join(' '));
        (nearby||[]).forEach(n=>{ if(!opts.find(o=>o && o.place_id===n.place_id)) opts.push(n); });
      }catch(_e){}
      if(opts.length > 1){ renderSuggestions(opts.filter(Boolean)); showToast('Select the exact place/building from suggestions'); }
      chooseLocation(placeLabel(place) || (place && place.display_name) || (lat.toFixed(6)+', '+lon.toFixed(6)), lat, lon);
      showToast('Location found — you can pick a more exact suggestion');
    }catch(e){ chooseLocation(lat.toFixed(6)+', '+lon.toFixed(6), lat, lon); showToast('Coordinates saved; place name lookup unavailable'); }
    finally{ setLocSearching(false); }
  }, (err)=>{ setLocSearching(false); showToast('Could not get location: ' + (err && err.message ? err.message : 'permission denied')); }, { enableHighAccuracy:true, timeout:12000, maximumAge:60000 });
}

document.addEventListener('submit', function(e){
  const form = e.target; if(form.dataset.noLoader==='1') return;
  const btn = form.querySelector('button[type="submit"], button:not([type])');
  if(btn) setBtnLoading(btn,true,'Saving...');
}, true);
</script>
</body>
</html>
