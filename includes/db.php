<?php
// includes/db.php - Reusable PDO database connection
if (!defined('APP_PATH')) {
    die('Direct access not allowed');
}

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=haventra;charset=utf8mb4",
        'root',
        '',
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
