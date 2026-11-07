<?php
// Start session first
session_start();

// Determine the base path dynamically
$base_path = '/';
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    $base_path = '/haventra/';
}
define('BASE_PATH', $base_path);

// Enable output buffering for faster rendering
ob_start();

// Set timezone
date_default_timezone_set('UTC');

// Define paths
define('APP_PATH', dirname(__FILE__) . '/');
define('API_PATH', APP_PATH . 'api/');
define('INCLUDES_PATH', APP_PATH . 'includes/');

// Database configuration (MySQL via XAMPP)
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'haventra');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Handle API requests
if (isset($_GET['page']) && strpos($_GET['page'], 'api/') === 0) {
    $apiPath = str_replace('api/', '', $_GET['page']);
    $apiFile = API_PATH . $apiPath . '.php';
    if (file_exists($apiFile)) {
        require $apiFile;
        exit;
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'API endpoint not found']);
        exit;
    }
}

$page = $_GET['page'] ?? 'home';

// Sanitize page parameter
$page = preg_replace('/[^a-zA-Z0-9_\-]/', '', $page);

// Handle logout before any output
if ($page === 'logout') {
    session_destroy();
    header('Location: ' . BASE_PATH);
    exit;
}

// Pages that require authentication
$protected_pages = ['admin', 'profile', 'payments', 'messages'];

// Check if user is logged in for protected pages
if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_PATH . 'login');
    exit;
}

// Route to page
$pageMap = [
    'home' => 'home.php',
    'house' => 'house.php',
    'houses' => 'houses.php',
    'houses-for-rent' => 'rentals.php',
    'rentals' => 'rentals.php',
    'moving_services' => 'moving_services.php',
    'about' => 'about.php',
    'profile' => 'profile.php',
    'login' => 'login.php',
    'register' => 'register.php',
    'reset_password' => 'reset_password.php',
    'payments' => 'payment.php',
    'amenities' => 'amenities.php',
    'restaurant' => 'restaurant.php',
    'rooms' => 'rooms.php',
    'offers' => 'offers.php',
    'gallery' => 'gallery.php',
    'contact' => 'contact-us.php',
    'contact-us' => 'contact-us.php',
    'events' => 'events.php',
    'agencies' => 'agencies.php',
    'search' => 'search.php',
    'messages' => 'messages.php',
];

$viewFile = $pageMap[$page] ?? 'home.php';
$viewPath = APP_PATH . $viewFile;

if (file_exists($viewPath)) {
    require APP_PATH . 'nav.php';
    require $viewPath;
    require APP_PATH . 'contact-us.php';
    require APP_PATH . 'footer.php';
} else {
    http_response_code(404);
    require APP_PATH . 'nav.php';
    require APP_PATH . 'contact-us.php';
    require APP_PATH . '404.php';
    require APP_PATH . 'footer.php';
}

// Flush output buffer for faster rendering
ob_end_flush();
