<?php
// sitemap.php - Dynamic XML sitemap for search engines
define('APP_PATH', __DIR__ . '/');
require __DIR__ . '/includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

$baseUrl = 'https://haventra.com';
$basePath = '/haventra/';
$today = date('Y-m-d');

// Static pages
$staticPages = [
    ['loc' => '/', 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '1.0'],
    ['loc' => '/houses/', 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => '/houses-for-rent/', 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => '/apartments-for-sale/', 'lastmod' => $today, 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => '/moving_services/', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => '/airbnb/', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => '/agencies/', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
    ['loc' => '/search/', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.7'],
    ['loc' => '/about/', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.6'],
    ['loc' => '/contact/', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.6'],
];

foreach ($staticPages as $page) {
    echo '  <url>' . "\n";
    echo '    <loc>' . htmlspecialchars($baseUrl . $page['loc']) . '</loc>' . "\n";
    echo '    <lastmod>' . htmlspecialchars($page['lastmod']) . '</lastmod>' . "\n";
    echo '    <changefreq>' . htmlspecialchars($page['changefreq']) . '</changefreq>' . "\n";
    echo '    <priority>' . htmlspecialchars($page['priority']) . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

// Dynamic property pages from database
try {
    $stmt = $pdo->query("SELECT id, title, created_at FROM properties WHERE is_active = 1 ORDER BY created_at DESC");
    while ($row = $stmt->fetch()) {
        $lastmod = $row['created_at'] ? date('Y-m-d', strtotime($row['created_at'])) : $today;
        echo '  <url>' . "\n";
        echo '    <loc>' . htmlspecialchars($baseUrl . '/house/' . $row['id'] . '/') . '</loc>' . "\n";
        echo '    <lastmod>' . htmlspecialchars($lastmod) . '</lastmod>' . "\n";
        echo '    <changefreq>weekly</changefreq>' . "\n";
        echo '    <priority>0.8</priority>' . "\n";
        echo '  </url>' . "\n";
    }
} catch (PDOException $e) {
    // Silently skip if table doesn't exist yet
}

// Dynamic agency pages from database
try {
    $stmt = $pdo->query("SELECT id, name FROM agencies WHERE is_active = 1 ORDER BY name");
    while ($row = $stmt->fetch()) {
        echo '  <url>' . "\n";
        echo '    <loc>' . htmlspecialchars($baseUrl . '/agency/' . $row['id'] . '/') . '</loc>' . "\n";
        echo '    <lastmod>' . htmlspecialchars($today) . '</lastmod>' . "\n";
        echo '    <changefreq>monthly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
} catch (PDOException $e) {
    // Silently skip if table doesn't exist yet
}

echo '</urlset>';
