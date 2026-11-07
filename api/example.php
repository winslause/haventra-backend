<?php
// api/example.php - Sample API endpoint
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query("SELECT 1 AS status");
        echo json_encode(['success' => true, 'data' => $stmt->fetch()]);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
