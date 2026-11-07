<?php
// api/chat.php - Chat messages handler: load history + send new message
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/../');
}
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Load chat messages (latest 100)
    try {
        $limit = intval($_GET['limit'] ?? 100);
        $stmt = $pdo->prepare("
            SELECT id, sender_name, sender_email, message, is_admin, created_at, is_read
            FROM chat_messages
            ORDER BY created_at ASC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        $rows = $stmt->fetchAll();
        echo json_encode(['success' => true, 'messages' => $rows]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to load messages']);
    }
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// POST: send a chat message
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$senderName = trim($input['sender_name'] ?? 'Visitor');
$senderEmail = trim($input['sender_email'] ?? '');
$message = trim($input['message'] ?? '');
$isAdmin = intval($input['is_admin'] ?? 0);
$replyTo = intval($input['reply_to'] ?? 0);

if (!$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO chat_messages (sender_name, sender_email, message, is_admin, admin_reply_to)
        VALUES (:sender_name, :sender_email, :message, :is_admin, :reply_to)
    ");
    $stmt->execute([
        ':sender_name' => $senderName,
        ':sender_email' => $senderEmail,
        ':message' => $message,
        ':is_admin' => $isAdmin,
        ':reply_to' => $replyTo ?: null,
    ]);
    $id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'id' => (int)$id,
        'message' => 'Message sent',
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to send message']);
}
