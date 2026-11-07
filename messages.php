<?php
// messages.php - Admin page to view contact and chat messages
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false ? '/aora-backend/' : '/') . 'login');
    exit;
}

$page_title = 'Messages | HAVENTRA Admin';
$page_description = 'View and manage contact form submissions and live chat messages.';
$page_canonical = 'https://haventra.com/messages/';
$page_breadcrumbs = [
    ['name' => 'Home', 'url' => 'https://haventra.com/'],
    ['name' => 'Messages', 'url' => 'https://haventra.com/messages/'],
];
?>
<?php
require __DIR__ . '/includes/db.php';

// Fetch contact messages
$contactMessages = [];
try {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 100");
    $contactMessages = $stmt->fetchAll();
} catch (PDOException $e) {
    $contactMessages = [];
}

// Fetch chat messages
$chatMessages = [];
try {
    $stmt = $pdo->query("SELECT * FROM chat_messages ORDER BY created_at DESC LIMIT 200");
    $chatMessages = $stmt->fetchAll();
} catch (PDOException $e) {
    $chatMessages = [];
}
?>

<!-- ===== MESSAGES ADMIN PAGE ===== -->
<section class="py-12 px-4 md:px-6 bg-[#f5efe8] min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
            <div>
                <h1 class="font-['Cormorant_Garamond'] text-4xl md:text-5xl text-[#3f352e] font-semibold mb-2">Messages</h1>
                <p class="text-[#1e4d40] text-sm">Manage contact form submissions and live chat messages from customers.</p>
            </div>
            <div class="flex gap-3">
                <a href="/messages/?tab=contact" class="px-5 py-2.5 bg-[#4a90a0] text-white text-sm font-medium rounded-lg hover:bg-[#2d5a4a] transition-colors <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'contact') ? 'bg-[#2d5a4a] shadow-lg' : ''; ?>">
                    Contact Form
                </a>
                <a href="/messages/?tab=chat" class="px-5 py-2.5 bg-white text-[#3f352e] text-sm font-medium rounded-lg border border-[#4a90a0]/20 hover:bg-[#4a90a0]/10 transition-colors <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'chat') ? 'bg-[#4a90a0] text-white shadow-lg' : ''; ?>">
                    Live Chat
                </a>
            </div>
        </div>

        <?php $tab = $_GET['tab'] ?? 'contact'; ?>

        <!-- ===== CONTACT MESSAGES ===== -->
        <?php if ($tab === 'contact'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#4a90a0]/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-[#4a90a0]/10 flex items-center justify-between">
                <h2 class="font-['Cormorant_Garamond'] text-2xl text-[#3f352e]">Contact Form Messages</h2>
                <span class="text-xs font-bold text-[#4a90a0] bg-[#4a90a0]/10 px-3 py-1 rounded-full"><?php echo count($contactMessages); ?> messages</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#4a90a0]/10 bg-[#f5efe8]">
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Date</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Name</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Email</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Subject</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Message</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contactMessages)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-[#6b7280] text-sm">No contact messages yet.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($contactMessages as $msg): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 text-xs text-[#6b7280]"><?php echo date('M j, Y g:i A', strtotime($msg['created_at'])); ?></td>
                            <td class="px-6 py-3 text-sm text-[#3f352e] font-medium"><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td class="px-6 py-3 text-xs text-[#6b7280]"><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td class="px-6 py-3 text-xs font-medium text-[#4a90a0]"><?php echo htmlspecialchars($msg['subject']); ?></td>
                            <td class="px-6 py-3 text-xs text-[#1e4d40] max-w-[300px] truncate"><?php echo htmlspecialchars(substr($msg['message'], 0, 120)); ?><?php echo strlen($msg['message']) > 120 ? '…' : ''; ?></td>
                            <td class="px-6 py-3 text-xs text-[#6b7280]"><?php echo htmlspecialchars($msg['ip_address']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- ===== CHAT MESSAGES ===== -->
        <?php if ($tab === 'chat'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-[#4a90a0]/10 overflow-hidden">
            <div class="px-6 py-5 border-b border-[#4a90a0]/10 flex items-center justify-between">
                <h2 class="font-['Cormorant_Garamond'] text-2xl text-[#3f352e]">Live Chat Messages</h2>
                <span class="text-xs font-bold text-[#4a90a0] bg-[#4a90a0]/10 px-3 py-1 rounded-full"><?php echo count($chatMessages); ?> messages</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-[#4a90a0]/10 bg-[#f5efe8]">
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Date</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Sender</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Type</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Message</th>
                            <th class="px-6 py-3 text-[10px] md:text-xs font-bold text-[#1e4d40] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($chatMessages)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-[#6b7280] text-sm">No chat messages yet.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($chatMessages as $msg): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 text-xs text-[#6b7280]"><?php echo date('M j, Y g:i A', strtotime($msg['created_at'])); ?></td>
                            <td class="px-6 py-3 text-sm text-[#3f352e] font-medium"><?php echo htmlspecialchars($msg['sender_name']); ?></td>
                            <td class="px-6 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold <?php echo $msg['is_admin'] ? 'bg-emerald-50 text-emerald-700' : 'bg-[#4a90a0]/10 text-[#4a90a0]'; ?>">
                                    <?php echo $msg['is_admin'] ? 'Admin' : 'Visitor'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-xs text-[#1e4d40] max-w-[400px] truncate"><?php echo htmlspecialchars(substr($msg['message'], 0, 150)); ?><?php echo strlen($msg['message']) > 150 ? '…' : ''; ?></td>
                            <td class="px-6 py-3 text-xs">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold <?php echo $msg['is_read'] ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'; ?>">
                                    <?php echo $msg['is_read'] ? 'Read' : 'Unread'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>
