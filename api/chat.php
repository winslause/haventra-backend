<?php
// api/chat.php - Live chat handler: load history + send new message (saves to contact_messages for admin panel integration)
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

// Generate or get session ID for anonymous chat visitors
session_start();
if (!isset($_SESSION['chat_visitor_id'])) {
    $_SESSION['chat_visitor_id'] = 'visitor_' . bin2hex(random_bytes(8));
}
$visitorId = $_SESSION['chat_visitor_id'];
$visitorEmail = "chat_{$visitorId}@haventra.local";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Load chat messages for this visitor from contact_messages
    try {
        $limit = intval($_GET['limit'] ?? 100);
        $stmt = $pdo->prepare("
            SELECT id, name, email, message, created_at, name as sender_name
            FROM contact_messages
            WHERE email = ?
            ORDER BY created_at ASC
            LIMIT ?
        ");
        $stmt->execute([$visitorEmail, $limit]);
        $rows = $stmt->fetchAll();
        // Add is_admin flag based on sender name
        foreach ($rows as &$r) {
            $r['is_admin'] = (stripos($r['sender_name'], 'Admin') !== false) ? 1 : 0;
        }
        echo json_encode(['success' => true, 'messages' => $rows, 'visitor_email' => $visitorEmail]);
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
$senderEmail = trim($input['sender_email'] ?? $visitorEmail);
$message = trim($input['message'] ?? '');
$isAdmin = intval($input['is_admin'] ?? 0);

// For admin replies, the sender_email must be provided in the request
if ($isAdmin && !$senderEmail) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'sender_email required for admin replies']);
    exit;
}

if (!$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Message is required']);
    exit;
}

// If admin is sending, use "Admin (You)" as name
if ($isAdmin) {
    $senderName = 'Admin (You)';
}

try {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $subject = $isAdmin ? 'Admin Reply' : 'Live Chat Message';
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message, ip_address)
        VALUES (:name, :email, '', :subject, :message, :ip)
    ");
    $stmt->execute([
        ':name' => $senderName,
        ':email' => $senderEmail,
        ':subject' => $subject,
        ':message' => $message,
        ':ip' => $ip,
    ]);
    $id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'id' => (int)$id,
        'message' => 'Message sent',
        'visitor_email' => $visitorEmail,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to send message']);
}