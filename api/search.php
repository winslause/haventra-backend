<?php
// api/search.php - Unified search for properties and agencies
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$q = trim($q);

if (empty($q)) {
    echo json_encode(['success' => true, 'query' => '', 'results' => [], 'total' => 0]);
    exit;
}

$like = '%' . $pdo->quote($q) . '%';
// Remove the quotes added by quote() for LIKE pattern
$like = str_replace("'", '', $like);

try {
    // Search properties
    $props = [];
    $stmt = $pdo->prepare("
        SELECT id, title, location, price, price_suffix, badge, type, image_url, lat, lng, description
        FROM properties 
        WHERE is_active = 1 
        AND (title LIKE ? OR location LIKE ? OR description LIKE ?)
        ORDER BY created_at DESC
        LIMIT 20
    ");
    $stmt->execute([$like, $like, $like]);
    while ($row = $stmt->fetch()) {
        $props[] = [
            'type' => 'property',
            'id' => 'prop-' . $row['id'],
            'title' => $row['title'],
            'location' => $row['location'],
            'price' => 'KSh ' . $row['price'] . ' ' . $row['price_suffix'],
            'badge' => $row['badge'],
            'category' => ucfirst($row['type']),
            'image' => $row['image_url'],
            'lat' => (float)$row['lat'],
            'lng' => (float)$row['lng'],
            'description' => $row['description'],
        ];
    }

    // Search agencies
    $agencies = [];
    $stmt = $pdo->prepare("
        SELECT id, name, category, category_label, location, city, rating, icon, short_desc, about, services, phone, email, whatsapp, website, lat, lng
        FROM agencies 
        WHERE is_active = 1 
        AND (name LIKE ? OR location LIKE ? OR city LIKE ? OR category_label LIKE ? OR about LIKE ? OR short_desc LIKE ?)
        ORDER BY rating DESC, name ASC
        LIMIT 20
    ");
    $stmt->execute([$like, $like, $like, $like, $like, $like]);
    while ($row = $stmt->fetch()) {
        $agencies[] = [
            'type' => 'agency',
            'id' => 'agency-' . $row['id'],
            'title' => $row['name'],
            'location' => $row['location'],
            'category' => $row['category_label'],
            'badge' => $row['category_label'],
            'image' => null,
            'lat' => (float)$row['lat'],
            'lng' => (float)$row['lng'],
            'description' => $row['short_desc'],
            'rating' => (int)$row['rating'],
            'icon' => $row['icon'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'whatsapp' => $row['whatsapp'],
            'website' => $row['website'],
        ];
    }

    $results = array_merge($props, $agencies);
    usort($results, function($a, $b) {
        // Properties first, then agencies; within each, by relevance (title match first)
        $order = ['property' => 0, 'agency' => 1];
        $oa = $order[$a['type']] ?? 99;
        $ob = $order[$b['type']] ?? 99;
        if ($oa !== $ob) return $oa - $ob;
        return 0;
    });

    echo json_encode([
        'success' => true,
        'query' => $q,
        'results' => $results,
        'total' => count($results),
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Search failed']);
}
