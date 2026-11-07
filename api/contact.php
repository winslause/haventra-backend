<?php
// api/contact.php - Contact form handler: save to DB + send email notification
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__ . '/../');
}
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Mailer.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// SMTP credentials (Gmail)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'wenbusale383@gmail.com');
define('SMTP_PASS', 'chqj uzdx dbev lpaa');
define('SMTP_FROM_NAME', 'HAVENTRA');
define('SMTP_FROM_EMAIL', 'wenbusale383@gmail.com');
define('ADMIN_EMAIL', 'wenbusale383@gmail.com');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // List contact messages (for admin)
    try {
        $limit = intval($_GET['limit'] ?? 50);
        $offset = intval($_GET['offset'] ?? 0);
        $stmt = $pdo->prepare("
            SELECT id, name, email, phone, subject, message, ip_address, created_at
            FROM contact_messages
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        $rows = $stmt->fetchAll();
        echo json_encode(['success' => true, 'messages' => $rows]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to fetch messages']);
    }
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// ---- POST: submit contact message ----
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$phone = trim($input['phone'] ?? '');
$subject = trim($input['subject'] ?? 'General Inquiry');
$message = trim($input['message'] ?? '');

if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Name, email, and message are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email address']);
    exit;

}

try {
    // Save to database
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message, ip_address)
        VALUES (:name, :email, :phone, :subject, :message, :ip)
    ");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':subject' => $subject,
        ':message' => $message,
        ':ip' => $ip,
    ]);

    // Send email notification to admin
    $mailer = new Mailer(SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, SMTP_FROM_NAME, SMTP_FROM_EMAIL);

    $phoneDisplay = $phone ?: 'Not provided';

    $subjectLine = "[HAVENTRA] New Contact Message: {$subject}";
    $htmlBody = "
    <html>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px;'>
            <h2 style='color: #00215E; border-bottom: 2px solid #005BB5; padding-bottom: 10px;'>
                New Contact Message
            </h2>
            <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                <tr><td style='padding: 8px; font-weight: bold; width: 120px; color: #005BB5;'>Name:</td><td style='padding: 8px;'>{$name}</td></tr>
                <tr><td style='padding: 8px; font-weight: bold; color: #005BB5;'>Email:</td><td style='padding: 8px;'>{$email}</td></tr>
                <tr><td style='padding: 8px; font-weight: bold; color: #005BB5;'>Phone:</td><td style='padding: 8px;'>{$phoneDisplay}</td></tr>
                <tr><td style='padding: 8px; font-weight: bold; color: #005BB5;'>Subject:</td><td style='padding: 8px;'>{$subject}</td></tr>
                <tr><td style='padding: 8px; font-weight: bold; color: #005BB5;'>IP Address:</td><td style='padding: 8px;'>{$ip}</td></tr>
            </table>
            <div style='background: #f8fafc; padding: 15px; border-radius: 6px; border-left: 4px solid #005BB5;'>
                <strong style='color: #005BB5;'>Message:</strong><br>
                <pre style='margin: 10px 0; white-space: pre-wrap; font-family: inherit;'>{$message}</pre>
            </div>
            <p style='color: #6b7280; font-size: 14px; margin-top: 20px;'>
                Reply directly to this email to respond to {$name}.
            </p>
        </div>
    </body>
    </html>";

    $textBody = "
New Contact Message - HAVENTRA
================================

Name: {$name}
Email: {$email}
Phone: {$phoneDisplay}
Subject: {$subject}
IP Address: {$ip}

Message:
{$message}

---
Reply directly to this email to respond to {$name}.
";

    $mailSent = $mailer->send(ADMIN_EMAIL, $subjectLine, $htmlBody, $textBody, $email, $name);

    echo json_encode([
        'success' => true,
        'message' => 'Your message has been sent successfully.',
        'email_sent' => $mailSent,
        'email_error' => $mailSent ? null : $mailer->getError(),
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Saved to DB but email failed - still success for user
    echo json_encode([
        'success' => true,
        'message' => 'Your message has been saved. Email notification failed.',
        'email_sent' => false,
        'email_error' => $e->getMessage(),
    ]);
}
