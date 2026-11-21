<?php
require_once __DIR__ . '/includes/db.php';

$adminProperties = [];
$adminUsers = [];
$adminAgencies = [];
$adminBookings = [];
$totalUsers = 0;
$roleCounts = ['client' => 0, 'owner' => 0, 'company' => 0, 'agency' => 0];
$suspendedCount = 0;
$totalProperties = 0;
$totalAgencies = 0;
$propStatusCounts = ['all' => 0, 'active' => 0, 'pending' => 0, 'draft' => 0, 'suspended' => 0, 'reported' => 0];
$agencyCategoryCounts = ['agency' => 0, 'property_mgmt' => 0, 'cleaning' => 0];
$adminMessage = '';
$adminMessageType = 'success';

/* ===== AJAX GET ENDPOINTS ===== */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    $ajaxAction = $_GET['ajax'] ?? '';
    try {
        if ($ajaxAction === 'support_conversations') {
            $search = $_GET['search'] ?? '';
            $sql = "SELECT DISTINCT cm.*, 
                        (SELECT message FROM contact_messages cm2 WHERE cm2.email = cm.email ORDER BY cm2.created_at DESC LIMIT 1) as last_message,
                        (SELECT created_at FROM contact_messages cm2 WHERE cm2.email = cm.email ORDER BY cm2.created_at DESC LIMIT 1) as last_message_time
                    FROM contact_messages cm";
            $params = [];
            if ($search) {
                $sql .= " WHERE cm.name LIKE ? OR cm.email LIKE ?";
                $like = '%' . $search . '%';
                $params = [$like, $like];
            }
            $sql .= " GROUP BY cm.email ORDER BY cm.created_at DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $conversations = $stmt->fetchAll();
            $formatted = [];
            foreach ($conversations as $c) {
                $colors = ['3B82F6', 'EF4444', '10B981', 'F59E0B', '8B5CF6', 'EC4899', '6366F1', '14B8A6'];
                $colorIndex = crc32($c['email']) % count($colors);
                $unread = 0;
                $formatted[] = [
                    'id' => $c['id'],
                    'name' => $c['name'],
                    'email' => $c['email'],
                    'avatar_color' => $colors[$colorIndex],
                    'last_message' => $c['last_message'] ?: $c['message'],
                    'time' => date('h:i A', strtotime($c['last_message_time'] ?: $c['created_at'])),
                    'unread' => $unread,
                ];
            }
            echo json_encode(['success' => true, 'conversations' => $formatted]);
            exit;
        } elseif ($ajaxAction === 'support_messages') {
            $contactId = (int)($_GET['contact_id'] ?? 0);
            $email = $_GET['email'] ?? '';
            if (!$email && $contactId) {
                $stmt = $pdo->prepare("SELECT email FROM contact_messages WHERE id = ? LIMIT 1");
                $stmt->execute([$contactId]);
                $row = $stmt->fetch();
                $email = $row['email'] ?: '';
            }
            if ($email) {
                $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE email = ? ORDER BY created_at ASC");
                $stmt->execute([$email]);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ? ORDER BY created_at ASC");
                $stmt->execute([$contactId]);
            }
            $messages = $stmt->fetchAll();
            $formatted = [];
            foreach ($messages as $m) {
                $formatted[] = [
                    'is_admin' => 0,
                    'message' => $m['message'],
                    'time' => date('h:i A', strtotime($m['created_at'])),
                ];
            }
            echo json_encode(['success' => true, 'messages' => $formatted]);
            exit;
        } elseif ($ajaxAction === 'support_stats') {
            echo json_encode([
                'success' => true,
                'totalConversations' => (int)$pdo->query("SELECT COUNT(DISTINCT email) FROM contact_messages")->fetchColumn(),
                'totalMessages' => (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn(),
            ]);
            exit;
        } elseif ($ajaxAction === 'support_bubble') {
            $stmt = $pdo->query("SELECT DISTINCT email, name FROM contact_messages ORDER BY name");
            $contacts = $stmt->fetchAll();
            $bubbles = [];
            $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6'];
            $ci = 0;
            $rowIndex = 0;
            foreach ($contacts as $c) {
                $email = $c['email'];
                $stmt2 = $pdo->prepare("SELECT name, COUNT(*) as cnt FROM contact_messages WHERE email = ? GROUP BY name");
                $stmt2->execute([$email]);
                $rows = $stmt2->fetchAll();
                $senderCnt = 0;
                $adminCnt = 0;
                foreach ($rows as $r) {
                    if (stripos($r['name'], 'Admin') !== false) {
                        $adminCnt = (int)$r['cnt'];
                    } else {
                        $senderCnt = (int)$r['cnt'];
                    }
                }
                $color = $colors[$ci % count($colors)];
                $ci++;
                if ($senderCnt > 0) {
                    $bubbles[] = [
                        'x' => -1,
                        'y' => $rowIndex,
                        'r' => min(20, 6 + $senderCnt * 1.5),
                        'label' => htmlspecialchars($c['name'] ?: $email) . ' (sender)',
                        'count' => $senderCnt,
                        'color' => $color,
                    ];
                    $rowIndex++;
                }
                if ($adminCnt > 0) {
                    $bubbles[] = [
                        'x' => 1,
                        'y' => $rowIndex,
                        'r' => min(20, 6 + $adminCnt * 1.5),
                        'label' => htmlspecialchars($c['name'] ?: $email) . ' (admin)',
                        'count' => $adminCnt,
                        'color' => $color,
                    ];
                    $rowIndex++;
                }
            }
            echo json_encode(['success' => true, 'bubbles' => $bubbles]);
            exit;
        } elseif ($ajaxAction === 'booking_connections') {
            $connections = [];
            $stmt = $pdo->query("
                SELECT DISTINCT cp.user_id, cp.owner_email,
                    u.first_name, u.last_name, u.email as client_email,
                    up.full_name as client_full_name, up.location as client_location,
                    ou.first_name as owner_first_name, ou.last_name as owner_last_name, ou.company_name as owner_company
                FROM company_projects cp
                LEFT JOIN users u ON cp.user_id = u.id
                LEFT JOIN user_profiles up ON (cp.user_id = up.user_id OR cp.owner_email = up.email)
                LEFT JOIN users ou ON cp.owner_email = ou.email
                WHERE cp.user_id IS NOT NULL AND cp.owner_email IS NOT NULL AND cp.owner_email != ''
                ORDER BY cp.created_at DESC
            ");
            $rows = $stmt->fetchAll();
            foreach ($rows as $r) {
                $clientEmail = $r['client_email'] ?: '';
                $ownerEmail = $r['owner_email'] ?: '';
                // Count messages between this client and owner
                $msgCnt = 0;
                if ($clientEmail && $ownerEmail) {
                    $stmt2 = $pdo->prepare("
                        SELECT COUNT(*) FROM chat_messages 
                        WHERE (sender_email = ? OR sender_email = ?)
                    ");
                    $stmt2->execute([$clientEmail, $ownerEmail]);
                    $msgCnt = (int)$stmt2->fetchColumn();
                }
                $clientName = $r['client_full_name'] ?: ($r['first_name'] . ' ' . $r['last_name']);
                $clientName = trim($clientName) ?: $clientEmail;
                $ownerName = $r['owner_company'] ?: ($r['owner_first_name'] . ' ' . $r['owner_last_name']);
                $ownerName = trim($ownerName) ?: $ownerEmail;
                $connections[] = [
                    'client_name' => htmlspecialchars($clientName),
                    'client_email' => htmlspecialchars($clientEmail),
                    'owner_name' => htmlspecialchars($ownerName),
                    'owner_email' => htmlspecialchars($ownerEmail),
                    'message_count' => $msgCnt,
                ];
            }
            echo json_encode(['success' => true, 'connections' => $connections]);
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Unknown AJAX action.']);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
}

function refreshCounts($pdo) {
    global $totalUsers, $roleCounts, $suspendedCount, $totalProperties, $totalAgencies, $propStatusCounts, $agencyCategoryCounts, $totalBookings, $bookingStatusCounts;
    $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")->fetchColumn();
    $roleCounts = ['client' => 0, 'owner' => 0, 'company' => 0, 'agency' => 0];
    $rows = $pdo->query("SELECT role FROM users WHERE role != 'admin'")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($rows as $r) {
        if (array_key_exists($r, $roleCounts)) {
            $roleCounts[$r]++;
        }
    }
    $suspendedCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin' AND first_login = 1")->fetchColumn();
    $totalProperties = (int)$pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
    $propStatusCounts['all'] = $totalProperties;
    $propStatusCounts['active'] = (int)$pdo->query("SELECT COUNT(*) FROM properties WHERE is_active = 1")->fetchColumn();
    $propStatusCounts['pending'] = (int)$pdo->query("SELECT COUNT(*) FROM properties WHERE is_active = 0")->fetchColumn();
    $propStatusCounts['draft'] = 0;
    $propStatusCounts['suspended'] = 0;
    $propStatusCounts['reported'] = 0;
    $totalAgencies = (int)$pdo->query("SELECT COUNT(*) FROM agencies")->fetchColumn();
    $agencyCategoryCounts = ['agency' => 0, 'property_mgmt' => 0, 'cleaning' => 0];
    $catRows = $pdo->query("SELECT category FROM agencies")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($catRows as $c) {
        if (array_key_exists($c, $agencyCategoryCounts)) {
            $agencyCategoryCounts[$c]++;
        }
    }
    $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM company_projects")->fetchColumn();
    $bookingStatusCounts = ['total' => $totalBookings, 'confirmed' => 0, 'pending' => 0, 'upcoming' => 0, 'active' => 0, 'completed' => 0, 'cancelled' => 0];
    $activeBookings = (int)$pdo->query("SELECT COUNT(*) FROM company_projects WHERE is_active = 1")->fetchColumn();
    $bookingStatusCounts['active'] = $activeBookings;
    $bookingStatusCounts['confirmed'] = $activeBookings;
    $bookingStatusCounts['cancelled'] = $totalBookings - $activeBookings;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    $userId = (int)($_POST['user_id'] ?? 0);
    $propId = (int)($_POST['prop_id'] ?? 0);
    $bookingId = (int)($_POST['booking_id'] ?? 0);
    $result = ['success' => false, 'message' => ''];
    try {
        if ($action === 'activate' && $userId) {
            $pdo->prepare("UPDATE users SET first_login = 0 WHERE id = ? AND role != 'admin'")->execute([$userId]);
            $result['success'] = true;
            $result['message'] = 'User activated successfully.';
        } elseif ($action === 'suspend' && $userId) {
            $pdo->prepare("UPDATE users SET first_login = 1 WHERE id = ? AND role != 'admin'")->execute([$userId]);
            $result['success'] = true;
            $result['message'] = 'User suspended successfully.';
        } elseif ($action === 'delete' && $userId) {
            $pdo->prepare("DELETE FROM user_profiles WHERE user_id = ?")->execute([$userId]);
            $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'")->execute([$userId]);
            $result['success'] = true;
            $result['message'] = 'User deleted successfully.';
        } elseif ($action === 'update_role' && $userId) {
            $role = $_POST['role'] ?? '';
            if (in_array($role, ['client', 'owner', 'company', 'agency'])) {
                $pdo->prepare("UPDATE users SET role = ? WHERE id = ? AND role != 'admin'")->execute([$role, $userId]);
                $result['success'] = true;
                $result['message'] = 'User role updated successfully.';
            } else {
                $result['message'] = 'Invalid role.';
            }
        } elseif ($action === 'create') {
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'client';
            $phone = $_POST['phone'] ?? '';
            $fullName = $_POST['full_name'] ?? '';
            $nameParts = explode(' ', trim($fullName), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result['message'] = 'Invalid email address.';
            } elseif (!in_array($role, ['client', 'owner', 'company', 'agency'])) {
                $result['message'] = 'Invalid role.';
            } else {
                try {
                    $pdo->prepare("INSERT INTO users (email, password_hash, first_name, last_name, phone, role, is_verified, first_login, created_at) VALUES (?, '', ?, ?, ?, ?, 0, 1, NOW())")->execute([$email, $firstName, $lastName, $phone, $role]);
                    $newId = (int)$pdo->lastInsertId();
                    $pdo->prepare("INSERT INTO user_profiles (user_id, email, full_name, phone, account_role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())")->execute([$newId, $email, $fullName, $phone, $role]);
                    $result['success'] = true;
                    $result['message'] = 'User created successfully.';
                } catch (PDOException $e) {
                    $result['message'] = 'Could not create user (email may already exist).';
                }
            }
        } elseif ($action === 'approve' && $propId) {
            $pdo->prepare("UPDATE properties SET is_active = 1 WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Property approved successfully.';
        } elseif ($action === 'reject' && $propId) {
            $pdo->prepare("UPDATE properties SET is_active = 0 WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Property rejected successfully.';
        } elseif ($action === 'delete_prop' && $propId) {
            $pdo->prepare("DELETE FROM properties WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Property deleted successfully.';
        } elseif ($action === 'update_prop' && $propId) {
            $title = $_POST['title'] ?? '';
            $location = $_POST['location'] ?? '';
            $price = $_POST['price'] ?? '';
            $type = $_POST['type'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            if (!in_array($type, ['rent', 'sale', 'booking'])) {
                $type = 'rent';
            }
            $pdo->prepare("UPDATE properties SET title = ?, location = ?, price = ?, type = ?, is_active = ? WHERE id = ?")->execute([$title, $location, $price, $type, $is_active, $propId]);
            $result['success'] = true;
            $result['message'] = 'Property updated successfully.';
        } elseif ($action === 'create_prop') {
            $title = $_POST['title'] ?? '';
            $location = $_POST['location'] ?? '';
            $price = $_POST['price'] ?? '';
            $type = $_POST['type'] ?? 'rent';
            if (!in_array($type, ['rent', 'sale', 'booking'])) {
                $type = 'rent';
            }
            $pdo->prepare("INSERT INTO properties (title, location, price, price_suffix, type, is_active, created_at, date_added) VALUES (?, ?, ?, 'KSh', ?, 1, NOW(), NOW())")->execute([$title, $location, $price, $type]);
            $result['success'] = true;
            $result['message'] = 'Property created successfully.';
        } elseif ($action === 'verify' && $propId) {
            $pdo->prepare("UPDATE agencies SET verified = 1 WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Agency verified successfully.';
        } elseif ($action === 'unverify' && $propId) {
            $pdo->prepare("UPDATE agencies SET verified = 0 WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Agency unverified successfully.';
        } elseif ($action === 'activate_agency' && $propId) {
            $pdo->prepare("UPDATE agencies SET is_active = 1 WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Agency activated successfully.';
        } elseif ($action === 'suspend_agency' && $propId) {
            $pdo->prepare("UPDATE agencies SET is_active = 0 WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Agency suspended successfully.';
        } elseif ($action === 'delete_agency' && $propId) {
            $pdo->prepare("DELETE FROM agencies WHERE id = ?")->execute([$propId]);
            $result['success'] = true;
            $result['message'] = 'Agency deleted successfully.';
        } elseif ($action === 'update_agency' && $propId) {
            $name = $_POST['name'] ?? '';
            $location = $_POST['location'] ?? '';
            $category = $_POST['category'] ?? '';
            $rating = $_POST['rating'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $verified = isset($_POST['verified']) ? 1 : 0;
            if (!in_array($category, ['agency', 'property_mgmt', 'cleaning'])) {
                $category = 'agency';
            }
            $pdo->prepare("UPDATE agencies SET name = ?, location = ?, category = ?, rating = ?, phone = ?, email = ?, is_active = ?, verified = ? WHERE id = ?")->execute([$name, $location, $category, $rating, $phone, $email, $is_active, $verified, $propId]);
            $result['success'] = true;
            $result['message'] = 'Agency updated successfully.';
        } elseif ($action === 'create_agency') {
            $name = $_POST['name'] ?? '';
            $location = $_POST['location'] ?? '';
            $category = $_POST['category'] ?? 'agency';
            $rating = $_POST['rating'] ?? '0';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $logo = $_POST['logo'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $verified = isset($_POST['verified']) ? 0 : 0;
            if (!in_array($category, ['agency', 'property_mgmt', 'cleaning'])) {
                $category = 'agency';
            }
            $categoryLabels = ['agency' => 'Real Estate Agency', 'property_mgmt' => 'Property Management', 'cleaning' => 'Cleaning'];
            $categoryIcons = ['agency' => 'fa-handshake', 'property_mgmt' => 'fa-shield-alt', 'cleaning' => 'fa-spray-can'];
            $categoryColors = ['agency' => 'purple', 'property_mgmt' => 'blue', 'cleaning' => 'green'];
            $icon = $categoryIcons[$category] ?? $categoryIcons['agency'];
            $pdo->prepare("INSERT INTO agencies (name, category, category_label, location, rating, reviews, verified, years, years_label, clients, satisfaction, icon, logo, color, short_desc, about, phone, email, whatsapp, website, is_active, created_at, date_added) VALUES (?, ?, ?, ?, ?, 0, ?, 0, '', '', '', ?, ?, ?, '', '', ?, ?, ?, ?, ?, NOW(), NOW())")->execute([$name, $category, $categoryLabels[$category] ?? $categoryLabels['agency'], $location, $rating, $verified, $icon, $logo, $categoryColors[$category] ?? 'purple', $phone, $email, $phone, $email, $is_active]);
            $result['success'] = true;
            $result['message'] = 'Agency created successfully.';
        } elseif ($action === 'send_support_message') {
            $contactId = (int)($_POST['contact_id'] ?? 0);
            $message = $_POST['message'] ?? '';
            $contactEmail = $_POST['contact_email'] ?? '';
            if (trim($message) === '') {
                $result['message'] = 'Message cannot be empty.';
            } else {
                // Get contact email if not provided
                if (!$contactEmail && $contactId) {
                    $stmt = $pdo->prepare("SELECT email, name FROM contact_messages WHERE id = ?");
                    $stmt->execute([$contactId]);
                    $contact = $stmt->fetch();
                    $contactEmail = $contact['email'] ?: '';
                    $contactName = $contact['name'] ?: 'User';
                } elseif ($contactEmail) {
                    $stmt = $pdo->prepare("SELECT name FROM contact_messages WHERE email = ? LIMIT 1");
                    $stmt->execute([$contactEmail]);
                    $contact = $stmt->fetch();
                    $contactName = $contact['name'] ?: 'User';
                } else {
                    $contactName = 'User';
                }
                // Insert admin reply as a chat message
                $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())")->execute(['Admin (You)', $contactEmail, $message]);
                $result['success'] = true;
                $result['message'] = 'Message sent.';
            }
        } elseif ($action === 'delete_booking' && $bookingId) {
            $pdo->prepare("DELETE FROM company_projects WHERE id = ?")->execute([$bookingId]);
            $result['success'] = true;
            $result['message'] = 'Booking deleted successfully.';
        } else {
            $result['message'] = 'Unknown action.';
        }
    } catch (PDOException $e) {
        $result['message'] = 'Database error: ' . $e->getMessage();
    }
    if ($result['success']) {
        refreshCounts($pdo);
        $result['counts'] = [
            'totalUsers' => $totalUsers,
            'roleCounts' => $roleCounts,
            'suspendedCount' => $suspendedCount,
            'totalProperties' => $totalProperties,
            'propStatusCounts' => $propStatusCounts,
            'totalAgencies' => $totalAgencies,
            'agencyCategoryCounts' => $agencyCategoryCounts,
            'totalBookings' => $totalBookings,
            'bookingStatusCounts' => $bookingStatusCounts,
        ];
    }
    echo json_encode($result);
    exit;
}

try {
    $adminUsers = $pdo->query("SELECT u.*, up.location, up.phone as profile_phone, up.avatar_url, up.full_name,
        (SELECT COUNT(*) FROM properties p WHERE p.user_id = u.id OR p.owner_email = u.email) AS listings_count
        FROM users u
        LEFT JOIN user_profiles up ON (u.id = up.user_id OR u.email = up.email)
        WHERE u.role != 'admin'
        ORDER BY u.created_at DESC")->fetchAll();
    $totalUsers = count($adminUsers);
    foreach ($adminUsers as $u) {
        $r = $u['role'] ?? 'client';
        if (array_key_exists($r, $roleCounts)) {
            $roleCounts[$r]++;
        }
    }
    $suspendedCount = 0;
    $totalProperties = (int)$pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
    $propStatusCounts['all'] = $totalProperties;
    $propStatusCounts['active'] = (int)$pdo->query("SELECT COUNT(*) FROM properties WHERE is_active = 1")->fetchColumn();
    $propStatusCounts['pending'] = (int)$pdo->query("SELECT COUNT(*) FROM properties WHERE is_active = 0")->fetchColumn();
    $propStatusCounts['draft'] = 0;
    $propStatusCounts['suspended'] = 0;
    $propStatusCounts['reported'] = 0;
    $adminProperties = $pdo->query("SELECT p.*, u.first_name, u.last_name, u.email, u.company_name, up.location as profile_location
        FROM properties p
        LEFT JOIN users u ON (p.user_id = u.id OR p.owner_email = u.email)
        LEFT JOIN user_profiles up ON (u.id = up.user_id OR u.email = up.email)
        ORDER BY p.created_at DESC")->fetchAll();
    $totalAgencies = (int)$pdo->query("SELECT COUNT(*) FROM agencies")->fetchColumn();
    $adminAgencies = $pdo->query("SELECT * FROM agencies ORDER BY created_at DESC")->fetchAll();
    $adminBookings = $pdo->query("
        SELECT cp.*, 
            u.first_name as client_first_name, u.last_name as client_last_name, u.email as client_email, u.role as client_role,
            up.full_name as client_full_name, up.location as client_location, up.phone as client_phone,
            ou.first_name as owner_first_name, ou.last_name as owner_last_name, ou.company_name as owner_company
        FROM company_projects cp
        LEFT JOIN users u ON cp.user_id = u.id
        LEFT JOIN user_profiles up ON (cp.user_id = up.user_id OR cp.owner_email = up.email)
        LEFT JOIN users ou ON cp.owner_email = ou.email
        ORDER BY cp.created_at DESC
    ")->fetchAll();
    $totalBookings = count($adminBookings);
    $bookingStatusCounts = ['total' => $totalBookings, 'confirmed' => 0, 'pending' => 0, 'upcoming' => 0, 'active' => 0, 'completed' => 0, 'cancelled' => 0];
    foreach ($adminBookings as $b) {
        if (!empty($b['is_active']) && $b['is_active'] == 1) {
            $bookingStatusCounts['active']++;
            $bookingStatusCounts['confirmed']++;
        } else {
            $bookingStatusCounts['cancelled']++;
        }
    }

    // Dashboard computed values
    $unverifiedUsers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin' AND is_verified = 0")->fetchColumn();
    $pendingProperties = $propStatusCounts['pending'];

    // Property distribution by type
    $propTypeCounts = ['rent' => 0, 'sale' => 0, 'booking' => 0];
    $typeRows = $pdo->query("SELECT type FROM properties")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($typeRows as $t) {
        if (array_key_exists($t, $propTypeCounts)) {
            $propTypeCounts[$t]++;
        }
    }

    // Estimated revenue from bookings (sum of property prices for active bookings)
    $revenueMTD = 0;
    $stmt = $pdo->query("SELECT p.price FROM properties p JOIN company_projects cp ON (cp.user_id = p.user_id OR cp.owner_email = p.owner_email) WHERE cp.is_active = 1");
    $prices = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($prices as $price) {
        $revenueMTD += (int)preg_replace('/[^0-9]/', '', $price);
    }

    // Property distribution by badge for chart
    $propBadgeCounts = ['active' => 0, 'For Sale' => 0, 'Short Stay' => 0];
    $badgeRows = $pdo->query("SELECT badge FROM properties")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($badgeRows as $b) {
        if (array_key_exists($b, $propBadgeCounts)) {
            $propBadgeCounts[$b]++;
        }
    }

} catch (PDOException $e) {
    $adminUsers = [];
    $adminProperties = [];
    $adminAgencies = [];
    $adminBookings = [];
    $unverifiedUsers = 0;
    $pendingProperties = 0;
    $propTypeCounts = ['rent' => 0, 'sale' => 0, 'booking' => 0];
    $revenueMTD = 0;
    $propBadgeCounts = ['active' => 0, 'For Sale' => 0, 'Short Stay' => 0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — HAVENTRA</title>
    <meta name="description" content="HAVENTRA administration panel — manage users, properties, bookings, agencies, and platform settings.">
    <meta name="robots" content="noindex, nofollow">
    
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#00215E',
                        navyLight: '#001540',
                        accentOrange: '#F59E0B',
                        accentBlue: '#005BB5',
                        adminRed: '#DC2626',
                        adminGreen: '#059669',
                        adminPurple: '#6D28D9',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Montserrat', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ===== SIDEBAR ===== */
        #adminSidebar {
            transition: transform 0.3s ease;
        }
        @media (max-width: 1023px) {
            #adminSidebar { transform: translateX(-100%); }
            #adminSidebar.open { transform: translateX(0); }
        }
        #sidebarOverlay { display: none; }
        #sidebarOverlay.active { display: block; }

        .admin-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #94A3B8;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .admin-nav:hover { background: rgba(255, 255, 255, 0.05); color: white; }
        .admin-nav.active {
            background: linear-gradient(135deg, #005BB5, #00215E);
            color: white;
            box-shadow: 0 6px 16px -4px rgba(0, 91, 181, 0.5);
        }
        .admin-nav.active .nav-icon { color: #F59E0B; }
        .nav-icon { font-size: 14px; width: 18px; text-align: center; }

        /* ===== CARDS ===== */
        .stat-card { transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1); }

        /* ===== TAB PILLS ===== */
        .admin-tab {
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            background: white;
            border: 1px solid #E2E8F0;
            transition: all 0.25s ease;
            cursor: pointer;
            white-space: nowrap;
        }
        .admin-tab:hover { border-color: #005BB5; color: #005BB5; }
        .admin-tab.active {
            background: #00215E;
            color: white;
            border-color: #00215E;
            box-shadow: 0 4px 12px rgba(0, 33, 94, 0.25);
        }

        /* ===== TABLE ===== */
        .admin-table th {
            text-align: left;
            padding: 10px 14px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748B;
            border-bottom: 1px solid #E2E8F0;
            background: #F8FAFC;
            white-space: nowrap;
        }
        .admin-table td {
            padding: 12px 14px;
            font-size: 12px;
            color: #1E293B;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
        }
        .admin-table tbody tr:hover { background: #F8FAFC; }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }
        .status-active { background: #DCFCE7; color: #166534; }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-suspended { background: #FEE2E2; color: #991B1B; }
        .status-draft { background: #E2E8F0; color: #475569; }
        .status-confirmed { background: #DBEAFE; color: #1E40AF; }
        .status-completed { background: #D1FAE5; color: #065F46; }
        .status-cancelled { background: #FEE2E2; color: #991B1B; }

        /* ===== MINI CHART BARS ===== */
        .mini-chart { display: flex; align-items: flex-end; gap: 3px; height: 32px; }
        .mini-bar { width: 6px; border-radius: 3px 3px 0 0; background: #CBD5E1; transition: background 0.2s; }
        .mini-bar.blue { background: #005BB5; }
        .mini-bar.orange { background: #F59E0B; }
        .mini-bar.green { background: #059669; }
        .mini-bar.purple { background: #6D28D9; }

        /* ===== SCROLLBARS ===== */
        .thin-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .thin-scroll::-webkit-scrollbar-track { background: transparent; }
        .thin-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ===== MODAL ===== */
        .admin-modal { opacity: 0; transition: opacity 0.25s ease; pointer-events: none; }
        .admin-modal.show { opacity: 1; pointer-events: auto; }
        .admin-modal .modal-inner {
            transform: scale(0.95) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
            max-height: 90vh;
            overflow-y: auto;
        }
        .admin-modal.show .modal-inner { transform: scale(1) translateY(0); }
        .admin-modal .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .admin-modal .modal-close:hover { background: #E2E8F0; color: #DC2626; }

        /* ===== FADE IN ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-in { animation: fadeIn 0.35s ease both; }

        /* ===== PULSE ===== */
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .pulse-dot { animation: pulseDot 2s infinite; }

        /* ===== SECTIONS ===== */
        .admin-section { display: none; }
        .admin-section.active { display: block; animation: fadeIn 0.35s ease both; }

        /* ===== CHECKBOX ===== */
        .admin-checkbox {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid #CBD5E1;
            cursor: pointer;
            transition: all 0.2s;
        }
        .admin-checkbox:checked {
            background: #005BB5;
            border-color: #005BB5;
        }

        /* ===== SEARCH INPUT ===== */
        .admin-search {
            background: #F1F5F9;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .admin-search:focus-within {
            background: white;
            box-shadow: 0 0 0 2px rgba(0, 91, 181, 0.3);
        }
    </style>
</head>
<body class="bg-[#F1F5F9] text-gray-800 antialiased">


<!-- ================= MOBILE OVERLAY ================= -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="closeSidebar()"></div>


<!-- ================= SIDEBAR ================= -->
<aside id="adminSidebar" class="fixed top-0 left-0 h-full w-[260px] bg-navyLight z-50 flex flex-col">

    <!-- Brand -->
    <div class="px-5 py-5 border-b border-white/10 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-accentBlue to-accentOrange flex items-center justify-center">
                <i class="fas fa-shield-alt text-white text-sm"></i>
            </div>
            <div>
                <p class="font-serif text-lg text-white font-semibold leading-none">HAVENTRA</p>
                <p class="text-[9px] text-accentOrange uppercase tracking-widest font-bold">Admin Panel</p>
            </div>
        </a>
        <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg text-white/60 hover:bg-white/10">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Admin info -->
    <div class="px-4 py-4 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accentOrange to-red-600 flex items-center justify-center text-white font-bold text-xs">
                    AD
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-navyLight rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">Admin User</p>
                <p class="text-[9px] text-white/50 truncate">admin@haventra.com</p>
            </div>
            <span class="text-[8px] font-bold text-accentOrange bg-accentOrange/15 px-1.5 py-0.5 rounded-full">SUPER</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto thin-scroll px-3 py-4">
        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mb-2">Main</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('dashboard', this)" class="admin-nav active">
                <i class="fas fa-th-large nav-icon"></i>
                <span>Dashboard</span>
            </a>
            <a onclick="showAdminSection('users', this)" class="admin-nav">
                <i class="fas fa-users nav-icon"></i>
                <span>Users</span>
                <span class="ml-auto text-[9px] bg-accentBlue/30 text-white font-bold px-1.5 py-0.5 rounded-full" id="sidebarUsersCount"><?= number_format($totalUsers) ?></span>
            </a>
            <a onclick="showAdminSection('properties', this)" class="admin-nav">
                <i class="fas fa-building nav-icon"></i>
                <span>Properties</span>
                <span class="ml-auto text-[9px] bg-accentOrange/30 text-white font-bold px-1.5 py-0.5 rounded-full" id="sidebarPropertiesCount"><?= number_format($totalProperties) ?></span>
            </a>
            <a onclick="showAdminSection('agencies', this)" class="admin-nav">
                <i class="fas fa-handshake nav-icon"></i>
                <span>Agencies</span>
                <span class="ml-auto text-[9px] bg-adminPurple/40 text-white font-bold px-1.5 py-0.5 rounded-full" id="sidebarAgenciesCount"><?= number_format($totalAgencies) ?></span>
            </a>
            <a onclick="showAdminSection('bookings', this)" class="admin-nav">
                <i class="fas fa-calendar-check nav-icon"></i>
                <span>Bookings</span>
            </a>
            <a onclick="showAdminSection('messages', this)" class="admin-nav">
                <i class="fas fa-comments nav-icon"></i>
                <span>Support</span>
                <span class="ml-auto text-[9px] bg-red-500 text-white font-bold px-1.5 py-0.5 rounded-full" id="sidebarSupportCount">12</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mt-5 mb-2">Finance</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('transactions', this)" class="admin-nav">
                <i class="fas fa-wallet nav-icon"></i>
                <span>Transactions</span>
            </a>
            <a onclick="showAdminSection('payouts', this)" class="admin-nav">
                <i class="fas fa-money-bill-wave nav-icon"></i>
                <span>Payouts</span>
                <span class="ml-auto text-[9px] bg-amber-500/30 text-white font-bold px-1.5 py-0.5 rounded-full" id="sidebarPayoutsCount">5</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mt-5 mb-2">Content</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('reviews', this)" class="admin-nav">
                <i class="fas fa-star nav-icon"></i>
                <span>Reviews</span>
            </a>
            <a onclick="showAdminSection('categories', this)" class="admin-nav">
                <i class="fas fa-tags nav-icon"></i>
                <span>Categories</span>
            </a>
            <a onclick="showAdminSection('locations', this)" class="admin-nav">
                <i class="fas fa-map-marker-alt nav-icon"></i>
                <span>Locations</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mt-5 mb-2">System</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('reports', this)" class="admin-nav">
                <i class="fas fa-chart-bar nav-icon"></i>
                <span>Reports</span>
            </a>
            <a onclick="showAdminSection('settings', this)" class="admin-nav">
                <i class="fas fa-cog nav-icon"></i>
                <span>Settings</span>
            </a>
            <a onclick="showAdminSection('activity', this)" class="admin-nav">
                <i class="fas fa-history nav-icon"></i>
                <span>Activity Log</span>
            </a>
        </div>
    </nav>

    <!-- Logout -->
    <div class="px-4 py-3 border-t border-white/10">
        <button onclick="adminLogout()" class="w-full flex items-center gap-2 text-xs text-white/60 hover:text-red-400 font-medium transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sign Out</span>
        </button>
    </div>
</aside>


<!-- ================= MAIN CONTENT ================= -->
<div class="lg:ml-[260px] min-h-screen">

    <!-- Top bar -->
    <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="px-4 md:px-6 py-3 flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3 flex-1">
                <button onclick="openSidebar()" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <div>
                    <h1 id="adminPageTitle" class="font-serif text-lg md:text-xl text-navy font-semibold leading-tight">Dashboard</h1>
                    <p id="adminPageSubtitle" class="text-[10px] md:text-xs text-slate-400">Platform overview & key metrics</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Global search -->
                <div class="hidden md:flex admin-search items-center gap-2 px-3 py-2 w-64">
                    <i class="fas fa-search text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Search users, properties, bookings…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                    <kbd class="text-[8px] font-bold text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200">⌘K</kbd>
                </div>

                <!-- Quick actions -->
                <button onclick="showToast('Quick actions panel')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-plus text-sm"></i>
                </button>

                <!-- Notifications -->
                <button onclick="showToast('You have 12 new notifications')" class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full pulse-dot"></span>
                </button>

                <!-- Messages -->
                <button onclick="showAdminSection('messages', document.querySelectorAll('.admin-nav')[5])" class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-envelope text-sm"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accentBlue rounded-full"></span>
                </button>

                <!-- Avatar -->
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentOrange to-red-600 flex items-center justify-center text-white font-bold text-[10px] cursor-pointer">
                    AD
                </div>
            </div>
        </div>
    </header>

    <!-- Content area -->
    <main class="p-4 md:p-6 pb-20">

        <!-- ================= DASHBOARD ================= -->
        <section id="admin-section-dashboard" class="admin-section active">

            <!-- Welcome banner -->
            <div class="bg-gradient-to-br from-navy via-navyLight to-accentBlue rounded-2xl p-5 md:p-7 text-white mb-5 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-accentOrange/10 rounded-full"></div>
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-white/60 font-bold mb-1">Admin Dashboard</p>
                        <h2 class="font-serif text-2xl md:text-3xl font-semibold mb-1">Welcome back, Admin 👋</h2>
                        <p class="text-white/70 text-xs md:text-sm">Platform is healthy. <span class="text-accentOrange font-bold"><?= number_format($totalProperties) ?> properties</span>, <span class="text-accentOrange font-bold"><?= number_format($totalUsers) ?> users</span>, <span class="text-accentOrange font-bold"><?= number_format($totalAgencies) ?> agencies</span>.</p>
                    </div>
                    <div class="flex gap-2 self-start md:self-center">
                        <button onclick="showAdminSection('reports', document.querySelectorAll('.admin-nav')[14])" class="bg-accentOrange text-navy text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-white transition-colors flex items-center gap-2">
                            <i class="fas fa-chart-bar"></i> View Reports
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-5">
                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center">
                            <i class="fas fa-users text-accentBlue text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+8.2%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy"><?= number_format($totalUsers) ?></p>
                    <p class="text-[10px] text-slate-400 font-medium">Total Users</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar blue" style="height: 40%"></div>
                        <div class="mini-bar blue" style="height: 55%"></div>
                        <div class="mini-bar blue" style="height: 45%"></div>
                        <div class="mini-bar blue" style="height: 70%"></div>
                        <div class="mini-bar blue" style="height: 60%"></div>
                        <div class="mini-bar blue" style="height: 85%"></div>
                        <div class="mini-bar blue" style="height: 100%"></div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-accentOrange/10 flex items-center justify-center">
                            <i class="fas fa-building text-accentOrange text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+12.5%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy"><?= number_format($totalProperties) ?></p>
                    <p class="text-[10px] text-slate-400 font-medium">Properties</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar orange" style="height: 50%"></div>
                        <div class="mini-bar orange" style="height: 65%"></div>
                        <div class="mini-bar orange" style="height: 55%"></div>
                        <div class="mini-bar orange" style="height: 80%"></div>
                        <div class="mini-bar orange" style="height: 70%"></div>
                        <div class="mini-bar orange" style="height: 90%"></div>
                        <div class="mini-bar orange" style="height: 100%"></div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-adminGreen/10 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-adminGreen text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+5.7%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy"><?= number_format($totalBookings) ?></p>
                    <p class="text-[10px] text-slate-400 font-medium">Bookings</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar green" style="height: 60%"></div>
                        <div class="mini-bar green" style="height: 45%"></div>
                        <div class="mini-bar green" style="height: 75%"></div>
                        <div class="mini-bar green" style="height: 65%"></div>
                        <div class="mini-bar green" style="height: 85%"></div>
                        <div class="mini-bar green" style="height: 95%"></div>
                        <div class="mini-bar green" style="height: 100%"></div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-adminPurple/10 flex items-center justify-center">
                            <i class="fas fa-wallet text-adminPurple text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+15.3%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">KSh <?= number_format($revenueMTD) ?></p>
                    <p class="text-[10px] text-slate-400 font-medium">Revenue (MTD)</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar purple" style="height: 35%"></div>
                        <div class="mini-bar purple" style="height: 55%"></div>
                        <div class="mini-bar purple" style="height: 70%"></div>
                        <div class="mini-bar purple" style="height: 60%"></div>
                        <div class="mini-bar purple" style="height: 85%"></div>
                        <div class="mini-bar purple" style="height: 90%"></div>
                        <div class="mini-bar purple" style="height: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- Charts row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5 mb-5">
                
                <!-- Revenue chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-serif text-lg text-navy font-semibold">Revenue Trend</h3>
                            <p class="text-[10px] text-slate-400">Last 12 months</p>
                        </div>
                        <div class="flex gap-1">
                            <button class="text-[9px] font-bold text-accentBlue bg-accentBlue/10 px-2 py-1 rounded-md">Monthly</button>
                            <button class="text-[9px] font-bold text-slate-400 px-2 py-1 rounded-md hover:bg-slate-50">Yearly</button>
                        </div>
                    </div>
                    <div class="flex items-end gap-2 h-40">
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 40%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 55%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 45%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 70%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 60%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 85%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 75%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 65%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 90%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 80%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 95%"></div>
                        <div class="flex-1 bg-accentBlue rounded-t" style="height: 100%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[8px] text-slate-400">
                        <span>Jan</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Nov</span><span>Dec</span>
                    </div>
                </div>

                <!-- Top categories -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-3">Property Distribution</h3>
                    <div class="space-y-3">
                        <?php
                        $maxBadge = max($propBadgeCounts);
                        $badgeOrder = ['Short Stay', 'For Rent', 'For Sale', 'active'];
                        $badgeLabels = ['Short Stay' => 'Airbnb / Short Stay', 'For Rent' => 'For Rent', 'For Sale' => 'For Sale', 'active' => 'Active'];
                        $badgeColors = ['Short Stay' => 'accentBlue', 'For Rent' => 'adminGreen', 'For Sale' => 'accentOrange', 'active' => 'adminPurple'];
                        foreach ($badgeOrder as $badge) {
                            $count = $propBadgeCounts[$badge] ?? 0;
                            $pct = $maxBadge > 0 ? round(($count / $maxBadge) * 100) : 0;
                            $label = $badgeLabels[$badge] ?? $badge;
                            $color = $badgeColors[$badge] ?? 'slate-400';
                        ?>
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[11px] font-semibold text-navy"><?= htmlspecialchars($label) ?></span>
                                    <span class="text-[10px] font-bold text-navy"><?= number_format($count) ?></span>
                                </div>
                                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-<?= $color ?> rounded-full" style="width: <?= $pct ?>%"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Pending actions + Recent activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5">
                
                <!-- Pending actions -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-3">Pending Actions</h3>
                    <div class="space-y-2">
                        <button onclick="showAdminSection('properties', document.querySelectorAll('.admin-nav')[2])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy"><?= number_format($pendingProperties) ?> pending properties</p>
                                <p class="text-[9px] text-slate-500">Awaiting approval</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                        <button onclick="showAdminSection('payouts', document.querySelectorAll('.admin-nav')[9])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-accentBlue flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy">0 payout requests</p>
                                <p class="text-[9px] text-slate-500">Payouts feature coming soon</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                        <button onclick="showAdminSection('users', document.querySelectorAll('.admin-nav')[1])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-check text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy"><?= number_format($unverifiedUsers) ?> KYC verifications</p>
                                <p class="text-[9px] text-slate-500">Identity documents</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                        <button onclick="showAdminSection('messages', document.querySelectorAll('.admin-nav')[5])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-red-50 hover:bg-red-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-flag text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy">0 reported listings</p>
                                <p class="text-[9px] text-slate-500">Reporting feature coming soon</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Recent activity -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif text-lg text-navy font-semibold">Recent Activity</h3>
                        <button onclick="showAdminSection('activity', document.querySelectorAll('.admin-nav')[16])" class="text-[10px] text-accentBlue font-semibold hover:underline">View All</button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-plus text-emerald-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New user registered</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">David Kariuki · david.k@email.com · Client</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">2m ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-accentBlue/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-accentBlue text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Property submitted for review</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Modern 3 Bed Villa · Kilimani · by Prestige Realty</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">8m ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-accentOrange/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-wallet text-accentOrange text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Payout processed</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">KSh 125,000 to Guardian Property Managers</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">15m ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-adminPurple/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-handshake text-adminPurple text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New agency verified</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Coastal Homes Ltd · Mombasa · Real Estate Agency</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">1h ago</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-flag text-red-500 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Listing reported</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Beachfront Villa · reported by 2 users · under review</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">2h ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Message Volume Bubble Chart -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Support Message Volume</h3>
                    <p class="text-[10px] text-slate-400">Senders left · Admins right · Bubble size = message count</p>
                </div>
                <div class="h-[400px]">
                    <canvas id="supportBubbleChart"></canvas>
                </div>
            </div>
        </section>


        <!-- ================= USERS ================= -->
        <section id="admin-section-users" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <button onclick="openUserModal('add')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </div>

            <?php if ($adminMessage): ?>
                <div class="mb-4 p-3 rounded-lg text-xs font-medium <?= $adminMessageType === 'error' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' ?>" role="alert">
                    <?= htmlspecialchars($adminMessage, ENT_QUOTES) ?>
                </div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (<?= number_format($totalUsers) ?>)</button>
                <button class="admin-tab">Clients (<?= number_format($roleCounts['client']) ?>)</button>
                <button class="admin-tab">Owners (<?= number_format($roleCounts['owner']) ?>)</button>
                <button class="admin-tab">Companies (<?= number_format($roleCounts['company']) ?>)</button>
                <button class="admin-tab">Suspended (<?= number_format($suspendedCount) ?>)</button>
            </div>

            <!-- Search & bulk actions -->
            <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-4">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="admin-search flex items-center gap-2 px-3 py-2.5 flex-1">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                        <input type="text" placeholder="Search by name, email, or phone…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                    </div>
                    <select class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        <option>All Roles</option>
                        <option>Client</option>
                        <option>Owner</option>
                        <option>Company</option>
                    </select>
                    <select class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Pending</option>
                        <option>Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Users table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10"><input type="checkbox" class="admin-checkbox"></th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Location</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Listings</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($adminUsers)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-slate-500 text-xs">No users found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($adminUsers as $user): 
                                    $name = $user['full_name'] ?? trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['email'];
                                    $initials = implode('', array_slice(array_map(fn($p) => strtoupper(substr($p,0,1)), explode(' ', $name)), 0, 2)) ?: 'U';
                                    $avatar = !empty($user['avatar_url']) ? '<img src="' . htmlspecialchars($user['avatar_url'], ENT_QUOTES) . '" alt="Avatar" class="w-8 h-8 rounded-full object-cover flex-shrink-0">' : '<div class="w-8 h-8 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">' . htmlspecialchars($initials) . '</div>';
                                    $role = $user['role'] ?? 'client';
                                    $roleLabels = ['client' => 'Client', 'owner' => 'Owner', 'company' => 'Company', 'agency' => 'Agency'];
                                    $roleColors = ['client' => 'text-slate-600 bg-slate-100', 'owner' => 'text-accentBlue bg-accentBlue/10', 'company' => 'text-adminPurple bg-adminPurple/10', 'agency' => 'text-accentOrange bg-accentOrange/10'];
                                    $roleClass = $roleColors[$role] ?? $roleColors['client'];
                                    $roleLabel = $roleLabels[$role] ?? ucfirst($role);
                                    $isPending = !empty($user['first_login']) && (int)$user['first_login'] === 1;
                                    $status = $isPending ? 'Pending' : 'Active';
                                    $badgeClass = $isPending ? 'status-pending' : 'status-active';
                                    $badgeIcon = $isPending ? 'fa-clock' : 'fa-check-circle';
                                    $badgeTitle = $isPending ? 'Pending' : 'Active';
                                    $joined = date('M d, Y', strtotime($user['created_at']));
                                    $location = $user['location'] ?: '—';
                                     $listings = (int)($user['listings_count'] ?? 0);
                                 ?>
                                    <tr data-user-id="<?= $user['id'] ?>">
                                        <td><input type="checkbox" class="admin-checkbox"></td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <?= $avatar ?>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-navy truncate"><?= htmlspecialchars($name, ENT_QUOTES) ?></p>
                                                    <p class="text-[9px] text-slate-400 truncate"><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-[10px] font-semibold <?= $roleClass ?> px-2 py-0.5 rounded-full"><?= htmlspecialchars($roleLabel) ?></span></td>
                                        <td class="text-slate-500"><?= htmlspecialchars($location) ?></td>
                                        <td class="text-slate-500"><?= htmlspecialchars($joined) ?></td>
                                        <td><span class="status-badge <?= $badgeClass ?>"><i class="fas <?= $badgeIcon ?> text-[7px]"></i><?= htmlspecialchars($badgeTitle) ?></span></td>
                                        <td class="font-bold"><?= number_format($listings) ?></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="openUserModal('view', <?= htmlspecialchars(json_encode($user), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                </button>
                                                <button onclick="openUserModal('edit', <?= htmlspecialchars(json_encode($user), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                </button>
                                                <?php if ($isPending): ?>
                                                    <button onclick="openUserModal('activate', <?= htmlspecialchars(json_encode(['id' => $user['id'], 'name' => $name, 'email' => $user['email']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Activate">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="openUserModal('suspend', <?= htmlspecialchars(json_encode(['id' => $user['id'], 'name' => $name, 'email' => $user['email']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Suspend">
                                                        <i class="fas fa-ban text-[10px]"></i>
                                                    </button>
                                                <?php endif ?>
                                                <button onclick="openUserModal('delete', <?= htmlspecialchars(json_encode(['id' => $user['id'], 'name' => $name, 'email' => $user['email']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                                                    <i class="fas fa-trash text-[10px]"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400">Showing <span class="font-bold text-navy">1-<?= min(5, $totalUsers) ?></span> of <span class="font-bold text-navy"><?= number_format($totalUsers) ?></span> users</p>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400 hover:border-accentBlue hover:text-accentBlue transition-colors"><i class="fas fa-chevron-left text-[9px]"></i></button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md bg-navy text-white text-[10px] font-bold">1</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold hover:border-accentBlue hover:text-accentBlue transition-colors">2</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold hover:border-accentBlue hover:text-accentBlue transition-colors">3</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400 hover:border-accentBlue hover:text-accentBlue transition-colors"><i class="fas fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= USER MODALS ================= -->
        <!-- View User Modal -->
        <div id="userModalView" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeUserModal('userModalView')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">View User</h3>
                    <button onclick="closeUserModal('userModalView')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-4" id="userViewContent">
                    <!-- populated by JS -->
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeUserModal('userModalView')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div id="userModalEdit" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeUserModal('userModalEdit')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Edit User</h3>
                    <button onclick="closeUserModal('userModalEdit')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="userEditForm" class="space-y-4" onsubmit="return saveUserEdit(event)">
                    <input type="hidden" name="user_id" id="editUserId">
                    <input type="hidden" name="action" value="update_role">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Full Name</label>
                        <input type="text" id="editUserName" name="full_name" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Email</label>
                        <input type="email" id="editUserEmail" name="email" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" readonly>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Role</label>
                        <select name="role" id="editUserRole" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                            <option value="client">Client</option>
                            <option value="owner">Owner</option>
                            <option value="company">Company</option>
                            <option value="agency">Agency</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <button type="button" onclick="closeUserModal('userModalEdit')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirm Action Modal (Activate / Suspend / Delete) -->
        <div id="userModalConfirm" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeUserModal('userModalConfirm')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="confirmModalTitle" class="font-serif text-lg text-navy font-semibold">Confirm Action</h3>
                    <button onclick="closeUserModal('userModalConfirm')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <p id="confirmModalMessage" class="text-slate-600 text-sm mb-6">Are you sure?</p>
                <input type="hidden" id="confirmUserId">
                <input type="hidden" id="confirmAction">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="closeUserModal('userModalConfirm')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                    <button id="confirmBtn" class="px-4 py-2 rounded-lg bg-accentBlue text-white text-xs font-medium hover:bg-accentOrange transition-colors">Confirm</button>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div id="userModalAdd" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeUserModal('userModalAdd')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Add User</h3>
                    <button onclick="closeUserModal('userModalAdd')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="userAddForm" class="space-y-4" onsubmit="return saveUserAdd(event)">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Full Name</label>
                        <input type="text" name="full_name" placeholder="e.g. Jane Mwangi" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Email</label>
                        <input type="email" name="email" placeholder="jane@example.com" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Phone</label>
                        <input type="text" name="phone" placeholder="+254 700 000 000" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Role</label>
                        <select name="role" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                            <option value="client">Client</option>
                            <option value="owner">Owner</option>
                            <option value="company">Company</option>
                            <option value="agency">Agency</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end pt-2">
                        <button type="button" onclick="closeUserModal('userModalAdd')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Create User</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openUserModal(type, user) {
                closeAllUserModals();
                if (type === 'view') {
                    const el = document.getElementById('userViewContent');
                    const first = user.first_name || '';
                    const last = user.last_name || '';
                    const name = user.full_name || (first + ' ' + last).trim() || user.email;
                    const roleLabels = { client: 'Client', owner: 'Owner', company: 'Company', agency: 'Agency' };
                    const role = user.role || 'client';
                    const isPending = user.first_login == 1;
                    const avatarHtml = user.avatar_url
                        ? '<img src="' + escapeHtml(user.avatar_url) + '" alt="Avatar" class="w-12 h-12 rounded-full object-cover">'
                        : '<div class="w-12 h-12 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-xs">' + escapeHtml((name.charAt(0) || 'U').toUpperCase()) + '</div>';
                    el.innerHTML =
                        '<div class="flex items-center gap-4 mb-2">' +
                        avatarHtml +
                        '<div class="flex-1 min-w-0"><p class="text-xs font-bold text-navy truncate">' + escapeHtml(name) + '</p><p class="text-[9px] text-slate-400 truncate">' + escapeHtml(user.email || '') + '</p></div>' +
                        '</div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Role</p><p class="font-semibold text-navy">' + escapeHtml(roleLabels[role] || role) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Status</p><p class="font-semibold ' + (isPending ? 'text-amber-600' : 'text-emerald-600') + '">' + (isPending ? 'Pending' : 'Active') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Phone</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(user.profile_phone || user.phone || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Location</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(user.location || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Company</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(user.company_name || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Listings</p><p class="font-semibold text-navy">' + parseInt(user.listings_count || 0) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Joined</p><p class="font-semibold text-slate-700">' + (user.created_at ? new Date(user.created_at).toLocaleDateString() : '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">ID</p><p class="font-semibold text-slate-700">#' + escapeHtml(user.id || '') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Verified</p><p class="font-semibold ' + (user.is_verified == 1 ? 'text-emerald-600' : 'text-slate-400') + '">' + (user.is_verified == 1 ? 'Yes' : 'No') + '</p></div>' +
                        '</div>';
                    document.getElementById('userModalView').classList.add('show');
                } else if (type === 'edit') {
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editUserName').value = user.full_name || ((user.first_name || '') + ' ' + (user.last_name || '')).trim() || user.email;
                    document.getElementById('editUserEmail').value = user.email || '';
                    document.getElementById('editUserRole').value = user.role || 'client';
                    document.getElementById('userModalEdit').classList.add('show');
                } else if (type === 'activate' || type === 'suspend' || type === 'delete') {
                    document.getElementById('confirmModalTitle').textContent = type === 'delete' ? 'Delete User' : (type === 'activate' ? 'Activate User' : 'Suspend User');
                    document.getElementById('confirmModalMessage').textContent = type === 'delete'
                        ? 'Are you sure you want to delete "' + escapeHtml(user.name) + '"? This cannot be undone.'
                        : 'Are you sure you want to ' + type + ' "' + escapeHtml(user.name) + '"?';
                    document.getElementById('confirmUserId').value = user.id;
                    document.getElementById('confirmAction').value = type;
                    const btn = document.getElementById('confirmBtn');
                    btn.className = 'px-4 py-2 rounded-lg text-xs font-medium';
                    if (type === 'delete') btn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
                    else if (type === 'activate') btn.classList.add('bg-emerald-600', 'text-white', 'hover:bg-emerald-700');
                    else btn.classList.add('bg-accentBlue', 'text-white', 'hover:bg-accentOrange');
                    document.getElementById('userModalConfirm').classList.add('show');
                } else if (type === 'add') {
                    document.getElementById('userModalAdd').classList.add('show');
                }
            }

            function closeUserModal(id) {
                document.getElementById(id).classList.remove('show');
            }

            function closeAllUserModals() {
                ['userModalView', 'userModalEdit', 'userModalConfirm', 'userModalAdd'].forEach(function(id) {
                    document.getElementById(id).classList.remove('show');
                });
            }

            function updateSidebarCounts(counts) {
                const el = document.getElementById('sidebarUsersCount');
                if (el) el.textContent = counts.totalUsers ? counts.totalUsers.toLocaleString() : el.textContent;
                const elP = document.getElementById('sidebarPropertiesCount');
                if (elP) elP.textContent = counts.totalProperties ? counts.totalProperties.toLocaleString() : elP.textContent;
                const elA = document.getElementById('sidebarAgenciesCount');
                if (elA) elA.textContent = counts.totalAgencies ? counts.totalAgencies.toLocaleString() : elA.textContent;
            }

            function handleApiResponse(result, rowIdAttr, rowIdValue) {
                if (result.success) {
                    if (result.counts) {
                        updateSidebarCounts(result.counts);
                    }
                    showToast(result.message || 'Done.');
                    if (rowIdAttr && rowIdValue) {
                        const row = document.querySelector('[' + rowIdAttr + '="' + rowIdValue + '"]');
                        if (row) {
                            row.style.opacity = '0';
                            setTimeout(function() { row.remove(); }, 300);
                        }
                    }
                } else {
                    showToast(result.message || 'Error occurred.');
            }
        };

            function saveUserEdit(e) {
                e.preventDefault();
                const form = document.getElementById('userEditForm');
                const formData = new FormData(form);
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(result) {
                    handleApiResponse(result, null, null);
                    if (result.success) closeUserModal('userModalEdit');
                });
                return false;
            }

            function saveUserAdd(e) {
                e.preventDefault();
                const form = document.getElementById('userAddForm');
                const formData = new FormData(form);
                formData.append('action', 'create');
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(result) {
                    handleApiResponse(result, null, null);
                    if (result.success) closeUserModal('userModalAdd');
                });
                return false;
            }

            function escapeHtml(text) {
                if (text == null) return '';
                return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
            }

            function openPropertyModal(type, prop) {
                closeAllPropertyModals();
                if (type === 'view') {
                    const el = document.getElementById('propViewContent');
                    const typeLabels = { rent: 'For Rent', sale: 'For Sale', booking: 'Short Stay' };
                    const type = prop.type || 'rent';
                    const isActive = prop.is_active == 1;
                    const status = isActive ? 'Active' : 'Pending';
                    const imageHtml = prop.image_url
                        ? '<img src="' + escapeHtml(prop.image_url) + '" alt="Property" class="w-16 h-16 rounded-xl object-cover">'
                        : '<div class="w-16 h-16 rounded-xl bg-gradient-to-br from-accentBlue to-accentOrange flex items-center justify-center text-white font-bold text-xs"><i class="fas fa-building"></i></div>';
                    el.innerHTML =
                        '<div class="flex items-center gap-4 mb-2">' +
                        imageHtml +
                        '<div class="flex-1 min-w-0"><p class="text-xs font-bold text-navy truncate">' + escapeHtml(prop.title) + '</p><p class="text-[9px] text-slate-400 truncate">' + escapeHtml(prop.location) + '</p></div>' +
                        '</div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Type</p><p class="font-semibold text-navy">' + escapeHtml(typeLabels[type] || type) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Status</p><p class="font-semibold ' + (isActive ? 'text-emerald-600' : 'text-amber-600') + '">' + escapeHtml(status) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Price</p><p class="font-semibold text-navy">KSh ' + escapeHtml(prop.price) + ' ' + escapeHtml(prop.price_suffix || '') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Owner</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(prop.company_name || prop.first_name || prop.email || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Beds</p><p class="font-semibold text-slate-700">' + escapeHtml(prop.beds || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Baths</p><p class="font-semibold text-slate-700">' + escapeHtml(prop.baths || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Size</p><p class="font-semibold text-slate-700">' + escapeHtml(prop.size || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Submitted</p><p class="font-semibold text-slate-700">' + (prop.created_at ? new Date(prop.created_at).toLocaleDateString() : '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">ID</p><p class="font-semibold text-slate-700">#' + escapeHtml(prop.id || '') + '</p></div>' +
                        '</div>' +
                        (prop.description ? '<div class="mt-4"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Description</p><p class="text-slate-600 text-xs leading-relaxed">' + escapeHtml(prop.description) + '</p></div>' : '');
                    document.getElementById('propModalView').classList.add('show');
                } else if (type === 'edit') {
                    document.getElementById('editPropId').value = prop.id;
                    document.getElementById('editPropTitle').value = prop.title || '';
                    document.getElementById('editPropLocation').value = prop.location || '';
                    document.getElementById('editPropPrice').value = prop.price || '';
                    document.getElementById('editPropType').value = prop.type || 'rent';
                    document.getElementById('editPropIsActive').checked = prop.is_active == 1;
                    document.getElementById('propModalEdit').classList.add('show');
                } else if (type === 'approve' || type === 'reject' || type === 'delete_prop') {
                    document.getElementById('propConfirmTitle').textContent = type === 'delete_prop' ? 'Delete Property' : (type === 'approve' ? 'Approve Property' : 'Reject Property');
                    document.getElementById('propConfirmMessage').textContent = type === 'delete_prop'
                        ? 'Are you sure you want to delete "' + escapeHtml(prop.title) + '"? This cannot be undone.'
                        : 'Are you sure you want to ' + (type === 'approve' ? 'approve' : 'reject') + ' "' + escapeHtml(prop.title) + '"?';
                    document.getElementById('propConfirmPropId').value = prop.id;
                    document.getElementById('propConfirmAction').value = type;
                    const btn = document.getElementById('propConfirmBtn');
                    btn.className = 'px-4 py-2 rounded-lg text-xs font-medium';
                    if (type === 'delete_prop') btn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
                    else if (type === 'approve') btn.classList.add('bg-emerald-600', 'text-white', 'hover:bg-emerald-700');
                    else btn.classList.add('bg-accentBlue', 'text-white', 'hover:bg-accentOrange');
                    document.getElementById('propModalConfirm').classList.add('show');
                } else if (type === 'add') {
                    document.getElementById('propModalAdd').classList.add('show');
                }
            }

            function closePropertyModal(id) {
                document.getElementById(id).classList.remove('show');
            }

            function closeAllPropertyModals() {
                ['propModalView', 'propModalEdit', 'propModalConfirm', 'propModalAdd'].forEach(function(id) {
                    document.getElementById(id).classList.remove('show');
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('confirmBtn').onclick = function() {
                    const action = document.getElementById('confirmAction').value;
                    const userId = document.getElementById('confirmUserId').value;
                    closeUserModal('userModalConfirm');
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'action=' + encodeURIComponent(action) + '&user_id=' + encodeURIComponent(userId)
                    }).then(function(r) { return r.json(); }).then(function(result) {
                        handleApiResponse(result, 'data-user-id', userId);
                    });
                };
                document.getElementById('propConfirmBtn').onclick = function() {
                    const action = document.getElementById('propConfirmAction').value;
                    const propId = document.getElementById('propConfirmPropId').value;
                    closePropertyModal('propModalConfirm');
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'action=' + encodeURIComponent(action) + '&prop_id=' + encodeURIComponent(propId)
                    }).then(function(r) { return r.json(); }).then(function(result) {
                        handleApiResponse(result, 'data-prop-id', propId);
                    }).catch(function(err) {
                        showToast('Request failed: ' + err.message);
                    });
                };
                document.getElementById('agencyConfirmBtn').onclick = function() {
                    const action = document.getElementById('agencyConfirmAction').value;
                    const agencyId = document.getElementById('agencyConfirmPropId').value;
                    closeAgencyModal('agencyModalConfirm');
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'action=' + encodeURIComponent(action) + '&prop_id=' + encodeURIComponent(agencyId)
                    }).then(function(r) { return r.json(); }).then(function(result) {
                        handleApiResponse(result, 'data-agency-id', agencyId);
                    });
                };
                document.getElementById('deleteBookingConfirmBtn').onclick = function() {
                    const bookingId = document.getElementById('deleteBookingId').value;
                    closeBookingModal('bookingModalDelete');
                    fetch('admin.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'action=delete_booking&booking_id=' + encodeURIComponent(bookingId)
                    }).then(function(r) { return r.json(); }).then(function(result) {
                        handleApiResponse(result, 'data-booking-id', bookingId);
                    }).catch(function(err) {
                        showToast('Request failed: ' + err.message);
                    });
                };
            });

            function savePropertyEdit(e) {
                e.preventDefault();
                const form = document.getElementById('propEditForm');
                const formData = new FormData(form);
                formData.append('action', 'update_prop');
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(result) {
                    handleApiResponse(result, null, null);
                    if (result.success) closePropertyModal('propModalEdit');
                });
                return false;
            }

            function savePropertyAdd(e) {
                e.preventDefault();
                const form = document.getElementById('propAddForm');
                const formData = new FormData(form);
                formData.append('action', 'create_prop');
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(result) {
                    handleApiResponse(result, null, null);
                    if (result.success) closePropertyModal('propModalAdd');
                });
                return false;
            }

            function openAgencyModal(type, agency) {
                closeAllAgencyModals();
                if (type === 'view') {
                    const el = document.getElementById('agencyViewContent');
                    const categoryLabels = { agency: 'Real Estate Agency', property_mgmt: 'Property Management', cleaning: 'Cleaning' };
                    const cat = agency.category || 'agency';
                    const isActive = agency.is_active == 1;
                    const isVerified = agency.verified == 1;
                    const status = isActive ? 'Active' : 'Suspended';
                    const logoHtml = agency.logo
                        ? '<img src="' + escapeHtml(agency.logo) + '" alt="Logo" class="w-16 h-16 rounded object-cover">'
                        : '<div class="w-16 h-16 rounded-lg bg-gradient-to-br from-adminPurple to-adminOrange flex items-center justify-center text-white font-bold text-xs"><i class="fas fa-building"></i></div>';
                    el.innerHTML =
                        '<div class="flex items-center gap-4 mb-2">' +
                        logoHtml +
                        '<div class="flex-1 min-w-0"><p class="text-xs font-bold text-navy truncate">' + escapeHtml(agency.name) + '</p><p class="text-[9px] text-slate-400 truncate">' + escapeHtml(agency.email) + '</p></div>' +
                        '</div>' +
                        '<div class="grid grid-cols-2 gap-3 text-xs">' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Category</p><p class="font-semibold text-navy">' + escapeHtml(categoryLabels[cat] || cat) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Status</p><p class="font-semibold ' + (isActive ? 'text-emerald-600' : 'text-red-600') + '">' + escapeHtml(status) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Verified</p><p class="font-semibold ' + (isVerified ? 'text-emerald-600' : 'text-amber-600') + '">' + (isVerified ? 'Yes' : 'No') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Location</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(agency.location) + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Rating</p><p class="font-semibold text-navy">' + escapeHtml(agency.rating) + ' <i class="fas fa-star"></i></p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Phone</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(agency.phone || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Website</p><p class="font-semibold text-slate-700 truncate">' + escapeHtml(agency.website || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Clients</p><p class="font-semibold text-slate-700">' + escapeHtml(agency.clients || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Satisfaction</p><p class="font-semibold text-slate-700">' + escapeHtml(agency.satisfaction || '—') + '</p></div>' +
                        '<div class="p-3 rounded-lg bg-slate-50"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">ID</p><p class="font-semibold text-slate-700">#' + escapeHtml(agency.id || '') + '</p></div>' +
                        '</div>' +
                        (agency.short_desc ? '<div class="mt-4"><p class="text-[9px] text-slate-400 uppercase font-bold mb-1">Description</p><p class="text-slate-600 text-xs leading-relaxed">' + escapeHtml(agency.short_desc) + '</p></div>' : '');
                    document.getElementById('agencyModalView').classList.add('show');
                } else if (type === 'edit') {
                    document.getElementById('editAgencyId').value = agency.id;
                    document.getElementById('editAgencyName').value = agency.name || '';
                    document.getElementById('editAgencyLocation').value = agency.location || '';
                    document.getElementById('editAgencyCategory').value = agency.category || 'agency';
                    document.getElementById('editAgencyRating').value = agency.rating || '0';
                    document.getElementById('editAgencyPhone').value = agency.phone || '';
                    document.getElementById('editAgencyEmail').value = agency.email || '';
                    document.getElementById('editAgencyLogo').value = agency.logo || '';
                    document.getElementById('editAgencyIsActive').checked = agency.is_active == 1;
                    document.getElementById('editAgencyVerified').checked = agency.verified == 1;
                    document.getElementById('agencyModalEdit').classList.add('show');
                } else if (type === 'verify' || type === 'unverify' || type === 'activate_agency' || type === 'suspend_agency' || type === 'delete_agency') {
                    const isVerify = type === 'verify' || type === 'unverify';
                    document.getElementById('agencyConfirmTitle').textContent = isVerify ? (type === 'verify' ? 'Verify Agency' : 'Unverify Agency') : (type === 'delete_agency' ? 'Delete Agency' : (type === 'activate_agency' ? 'Activate Agency' : 'Suspend Agency'));
                    document.getElementById('agencyConfirmMessage').textContent = type === 'delete_agency'
                        ? 'Are you sure you want to delete "' + escapeHtml(agency.name) + '"? This cannot be undone.'
                        : (isVerify ? 'Are you sure you want to ' + (type === 'verify' ? 'verify' : 'unverify') + ' "' + escapeHtml(agency.name) + '"?'
                        : 'Are you sure you want to ' + (type === 'activate_agency' ? 'activate' : 'suspend') + ' "' + escapeHtml(agency.name) + '"?');
                    document.getElementById('agencyConfirmPropId').value = agency.id;
                    document.getElementById('agencyConfirmAction').value = type;
                    const btn = document.getElementById('agencyConfirmBtn');
                    btn.className = 'px-4 py-2 rounded-lg text-xs font-medium';
                    if (type === 'delete_agency') btn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
                    else if (type === 'verify') btn.classList.add('bg-emerald-600', 'text-white', 'hover:bg-emerald-700');
                    else if (type === 'unverify') btn.classList.add('bg-red-600', 'text-white', 'hover:bg-red-700');
                    else if (type === 'activate_agency') btn.classList.add('bg-emerald-600', 'text-white', 'hover:bg-emerald-700');
                    else btn.classList.add('bg-accentBlue', 'text-white', 'hover:bg-accentOrange');
                    document.getElementById('agencyModalConfirm').classList.add('show');
                } else if (type === 'add') {
                    document.getElementById('agencyModalAdd').classList.add('show');
                }
            }

            function closeAgencyModal(id) {
                document.getElementById(id).classList.remove('show');
            }

            function closeAllAgencyModals() {
                ['agencyModalView', 'agencyModalEdit', 'agencyModalConfirm', 'agencyModalAdd'].forEach(function(id) {
                    document.getElementById(id).classList.remove('show');
                });
            }

            function saveAgencyEdit(e) {
                e.preventDefault();
                const form = document.getElementById('agencyEditForm');
                const formData = new FormData(form);
                formData.append('action', 'update_agency');
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(result) {
                    handleApiResponse(result, null, null);
                    if (result.success) closeAgencyModal('agencyModalEdit');
                });
                return false;
            }

            function saveAgencyAdd(e) {
                e.preventDefault();
                const form = document.getElementById('agencyAddForm');
                const formData = new FormData(form);
                formData.append('action', 'create_agency');
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                }).then(function(r) { return r.json(); }).then(function(result) {
                    handleApiResponse(result, null, null);
                    if (result.success) closeAgencyModal('agencyModalAdd');
                });
                return false;
            }

            /* ===== SUPPORT CHAT AJAX ===== */
            let currentSupportContactId = null;
            let supportMessagesContainer = null;

            function loadSupportConversations() {
                const search = document.getElementById('supportSearchInput') ? document.getElementById('supportSearchInput').value : '';
                fetch('admin.php?ajax=support_conversations&search=' + encodeURIComponent(search))
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        // Update sidebar badge
                        const sidebarBadge = document.getElementById('sidebarSupportCount');
                        if (sidebarBadge && data.success && data.conversations) {
                            sidebarBadge.textContent = data.conversations.length.toLocaleString();
                        }
                        const container = document.getElementById('supportConversationList');
                        if (!data.success || !data.conversations || data.conversations.length === 0) {
                            container.innerHTML = '<div class="p-6 text-center text-slate-400 text-xs">No conversations found.</div>';
                            return;
                        }
                        container.innerHTML = data.conversations.map(function(c) {
                            const initials = c.name.split(' ').map(function(n) { return n.charAt(0); }).join('').toUpperCase().slice(0, 2) || 'U';
                            const isActive = currentSupportContactId == c.id ? 'bg-[#005BB5]/10 border-[#005BB5]' : 'bg-slate-50 border-transparent';
                            return '<div class="p-2.5 rounded-lg border border-transparent ' + isActive + ' cursor-pointer hover:bg-[#F3F4F6] transition-colors" onclick="openSupportConversation(' + c.id + ', \'' + escapeHtml(c.name) + '\', \'' + escapeHtml(c.avatar_color) + '\')">' +
                                '<div class="flex items-center justify-between gap-2">' +
                                '<div class="flex items-center gap-2">' +
                                '<div class="w-8 h-8 rounded-full bg-[#' + c.avatar_color + '] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">' + initials + '</div>' +
                                '<div class="min-w-0 flex-1">' +
                                '<p class="text-xs font-bold text-navy truncate">' + escapeHtml(c.name) + '</p>' +
                                '<p class="text-[9px] text-slate-500 truncate line-clamp-1">' + escapeHtml(c.last_message) + '</p>' +
                                '</div>' +
                                '</div>' +
                                '<div class="text-right">' +
                                '<p class="text-[9px] text-slate-400 font-medium">' + c.time + '</p>' +
                                (c.unread > 0 ? '<span class="w-2 h-2 rounded-full bg-[#005BB5] inline-block mt-0.5"></span>' : '') +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        }).join('');
                    });
            }

            function openSupportConversation(id, name, avatarColor) {
                currentSupportContactId = id;
                document.getElementById('supportContactAvatar').textContent = name.split(' ').map(function(n) { return n.charAt(0); }).join('').toUpperCase().slice(0, 2) || 'U';
                document.getElementById('supportContactAvatar').parentElement.style.backgroundColor = '#' + avatarColor;
                document.getElementById('supportContactName').textContent = name;
                document.getElementById('supportChatEmpty').classList.add('hidden');
                document.getElementById('supportChatView').classList.remove('hidden');
                document.getElementById('supportMessagesContainer').innerHTML = '<div class="p-4 text-center text-slate-400 text-xs">Loading messages…</div>';
                loadSupportMessages(id);
                loadSupportConversations();
            }

            function goBackToConversations() {
                currentSupportContactId = null;
                document.getElementById('supportChatEmpty').classList.remove('hidden');
                document.getElementById('supportChatView').classList.add('hidden');
                loadSupportConversations();
            }

            function loadSupportMessages(contactId) {
                fetch('admin.php?ajax=support_messages&contact_id=' + contactId)
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        const container = document.getElementById('supportMessagesContainer');
                        if (!data.success || !data.messages || data.messages.length === 0) {
                            container.innerHTML = '<div class="p-6 text-center text-slate-400 text-xs">No messages yet.</div>';
                            return;
                        }
                        container.innerHTML = data.messages.map(function(m) {
                            const isAdmin = m.is_admin == 1;
                            return '<div class="flex ' + (isAdmin ? 'justify-end' : 'justify-start') + ' mb-1">' +
                                '<div class="max-w-[80%] ' + (isAdmin ? 'bg-[#005BB5] text-white rounded-2xl rounded-tl-sm px-3 py-2.5 shadow-sm' : 'bg-white rounded-2xl rounded-tr-sm px-3 py-2.5 shadow-sm border border-slate-100') + '">' +
                                '<p class="text-[11px] leading-relaxed">' + escapeHtml(m.message) + '</p>' +
                                '<span class="text-[8px] ' + (isAdmin ? 'text-[#0B3D91]' : 'text-slate-400') + ' mt-1 block ' + (isAdmin ? 'text-right' : '') + '">' + m.time + '</span>' +
                                '</div>' +
                                '</div>';
                        }).join('');
                        container.scrollTop = container.scrollHeight;
                    });
            }

            function sendSupportReply() {
                const input = document.getElementById('supportReplyInput');
                if (!input.value.trim() || !currentSupportContactId) return;
                const text = input.value.trim();
                input.value = '';
                fetch('admin.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=send_support_message&contact_id=' + encodeURIComponent(currentSupportContactId) + '&message=' + encodeURIComponent(text)
                }).then(function(r) { return r.json(); }).then(function(result) {
                    if (result.success) {
                        loadSupportMessages(currentSupportContactId);
                        loadSupportConversations();
                    }
                });
            }

            document.getElementById('supportSearchInput')?.addEventListener('input', function() {
                loadSupportConversations();
            });

            /* ===== BOOKING MODAL ===== */
            function openBookingModal(type, booking) {
                closeAllBookingModals();
                if (type === 'view') {
                    document.getElementById('viewBookingId').textContent = '#HV-' + String(booking.id).padStart(4, '0');
                    document.getElementById('viewBookingTitle').textContent = booking.title || '—';
                    document.getElementById('viewBookingLocation').textContent = booking.location || '—';
                    document.getElementById('viewBookingClientName').textContent = booking.client_name || '—';
                    document.getElementById('viewBookingClientEmail').textContent = booking.client_email || '—';
                    document.getElementById('viewBookingClientPhone').textContent = booking.client_phone || '—';
                    document.getElementById('viewBookingClientLocation').textContent = booking.client_location || '—';
                    document.getElementById('viewBookingOwnerName').textContent = booking.owner_name || '—';
                    document.getElementById('viewBookingOwnerEmail').textContent = booking.owner_email || '—';
                    document.getElementById('viewBookingPropertyType').textContent = booking.property_type || '—';
                    document.getElementById('viewBookingDescription').textContent = booking.description || '—';
                    document.getElementById('viewBookingStatus').textContent = booking.is_active == 1 ? 'Active' : 'Inactive';
                    document.getElementById('viewBookingCreated').textContent = booking.created_at ? new Date(booking.created_at).toLocaleDateString() : '—';
                    document.getElementById('bookingModalView').classList.add('show');
                } else if (type === 'edit') {
                    document.getElementById('editBookingId').value = booking.id;
                    document.getElementById('editBookingTitle').value = booking.title || '';
                    document.getElementById('bookingModalEdit').classList.add('show');
                } else if (type === 'delete') {
                    document.getElementById('deleteBookingTitle').textContent = 'Delete Booking';
                    document.getElementById('deleteBookingMessage').textContent = 'Are you sure you want to delete "' + escapeHtml(booking.title) + '"? This cannot be undone.';
                    document.getElementById('deleteBookingId').value = booking.id;
                    document.getElementById('bookingModalDelete').classList.add('show');
                }
            }

            function closeBookingModal(id) {
                document.getElementById(id).classList.remove('show');
            }

            function closeAllBookingModals() {
                ['bookingModalView', 'bookingModalEdit', 'bookingModalDelete'].forEach(function(id) {
                    document.getElementById(id).classList.remove('show');
                });
            }

            function loadBookingConnections() {
                fetch('admin.php?ajax=booking_connections')
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        const container = document.getElementById('connectionsTableBody');
                        if (!data.success || !data.connections || data.connections.length === 0) {
                            container.innerHTML = '<tr><td colspan="7" class="p-6 text-center text-slate-400 text-xs">No connections found.</td></tr>';
                            return;
                        }
                        container.innerHTML = data.connections.map(function(c) {
                            return '<tr>' +
                                '<td class="text-[10px] font-semibold text-navy">' + c.client_name + '</td>' +
                                '<td class="text-[10px] text-slate-500 truncate" title="' + c.client_email + '">' + c.client_email + '</td>' +
                                '<td class="text-[10px] font-semibold text-navy">' + c.owner_name + '</td>' +
                                '<td class="text-[10px] text-slate-500 truncate" title="' + c.owner_email + '">' + c.owner_email + '</td>' +
                                '<td class="text-[10px] font-bold text-accentBlue">' + c.message_count + '</td>' +
                                '<td class="text-[10px] text-slate-500">—</td>' +
                                '<td class="text-right"><button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View"><i class="fas fa-eye text-[10px]"></i></button></td>' +
                                '</tr>';
                        }).join('');
                    });
            }
        </script>

        <!-- ================= PROPERTY MODALS ================= -->
        <!-- View Property Modal -->
        <div id="propModalView" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePropertyModal('propModalView')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">View Property</h3>
                    <button onclick="closePropertyModal('propModalView')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-4" id="propViewContent"></div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closePropertyModal('propModalView')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit Property Modal -->
        <div id="propModalEdit" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePropertyModal('propModalEdit')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Edit Property</h3>
                    <button onclick="closePropertyModal('propModalEdit')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="propEditForm" class="space-y-4" onsubmit="return savePropertyEdit(event)">
                    <input type="hidden" name="prop_id" id="editPropId">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Title</label>
                        <input type="text" name="title" id="editPropTitle" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Location</label>
                        <input type="text" name="location" id="editPropLocation" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Price</label>
                        <input type="number" name="price" id="editPropPrice" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Type</label>
                        <select name="type" id="editPropType" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                            <option value="rent">For Rent</option>
                            <option value="sale">For Sale</option>
                            <option value="booking">Short Stay</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="editPropIsActive" class="admin-checkbox">
                        <label for="editPropIsActive" class="text-xs text-slate-600">Active / Published</label>
                    </div>
                    <div class="flex items-center justify-end pt-2">
                        <button type="button" onclick="closePropertyModal('propModalEdit')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirm Action Modal (Approve / Reject / Delete) -->
        <div id="propModalConfirm" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePropertyModal('propModalConfirm')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="propConfirmTitle" class="font-serif text-lg text-navy font-semibold">Confirm Action</h3>
                    <button onclick="closePropertyModal('propModalConfirm')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <p id="propConfirmMessage" class="text-slate-600 text-sm mb-6">Are you sure?</p>
                <input type="hidden" id="propConfirmPropId">
                <input type="hidden" id="propConfirmAction">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="closePropertyModal('propModalConfirm')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                    <button id="propConfirmBtn" class="px-4 py-2 rounded-lg bg-accentBlue text-white text-xs font-medium hover:bg-accentOrange transition-colors">Confirm</button>
                </div>
            </div>
        </div>

        <!-- Add Property Modal -->
        <div id="propModalAdd" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePropertyModal('propModalAdd')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Add Property</h3>
                    <button onclick="closePropertyModal('propModalAdd')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="propAddForm" class="space-y-4" onsubmit="return savePropertyAdd(event)">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Title</label>
                        <input type="text" name="title" placeholder="e.g. Modern 3 Bed Villa" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Location</label>
                        <input type="text" name="location" placeholder="e.g. Kilimani, Nairobi" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Price (KSh)</label>
                        <input type="number" name="price" placeholder="150000" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Type</label>
                        <select name="type" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                            <option value="rent">For Rent</option>
                            <option value="sale">For Sale</option>
                            <option value="booking">Short Stay</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end pt-2">
                        <button type="button" onclick="closePropertyModal('propModalAdd')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Create Property</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= PROPERTIES ================= -->
        <section id="admin-section-properties" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="flex gap-2">
                    <button onclick="showToast('Bulk actions opened')" class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-tasks"></i> Bulk Actions
                    </button>
                    <button onclick="openPropertyModal('add')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Property
                    </button>
            </div>
        </div>

        <?php if ($adminMessage): ?>
            <div class="mb-4 p-3 rounded-lg text-xs font-medium <?= $adminMessageType === 'error' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' ?>" role="alert">
                <?= htmlspecialchars($adminMessage, ENT_QUOTES) ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-wrap gap-2 mb-4">
            <button class="admin-tab active">All (<?= number_format($propStatusCounts['all']) ?>)</button>
            <button class="admin-tab">Pending (<?= number_format($propStatusCounts['pending']) ?>)</button>
            <button class="admin-tab">Active (<?= number_format($propStatusCounts['active']) ?>)</button>
            <button class="admin-tab">Draft (<?= number_format($propStatusCounts['draft']) ?>)</button>
            <button class="admin-tab">Suspended (<?= number_format($propStatusCounts['suspended']) ?>)</button>
            <button class="admin-tab">Reported (<?= number_format($propStatusCounts['reported']) ?>)</button>
        </div>

        <!-- Pending approval banner -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-navy"><?= number_format($propStatusCounts['pending']) ?> properties are awaiting your approval</p>
                <p class="text-[10px] text-slate-500 mt-0.5">New listings submitted by owners and agencies. Review them to publish on the platform.</p>
            </div>
            <button onclick="showToast('Reviewing pending properties')" class="bg-amber-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition-colors whitespace-nowrap">
                Review Now
            </button>
        </div>

            <!-- Properties table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10"><input type="checkbox" class="admin-checkbox"></th>
                                <th>Property</th>
                                <th>Category</th>
                                <th>Owner/Agency</th>
                                <th>Price</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($adminProperties)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-slate-500 text-xs">No properties found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($adminProperties as $prop): 
                                    $ownerName = $prop['company_name'] ?: (trim(($prop['first_name'] ?? '') . ' ' . ($prop['last_name'] ?? '')) ?: $prop['email']);
                                    $isActive = (int)($prop['is_active'] ?? 1);
                                    $status = $isActive ? 'Active' : 'Pending';
                                    $badgeClass = $isActive ? 'status-active' : 'status-pending';
                                    $badgeIcon = $isActive ? 'fa-check-circle' : 'fa-clock';
                                    $price = number_format((float)($prop['price'] ?? 0));
                                    $priceSuffix = $prop['price_suffix'] ?: '';
                                    $typeLabels = ['rent' => 'For Rent', 'sale' => 'For Sale', 'booking' => 'Short Stay'];
                                    $typeColors = ['rent' => 'text-accentBlue bg-accentBlue/10', 'sale' => 'text-adminPurple bg-adminPurple/10', 'booking' => 'text-accentOrange bg-accentOrange/10'];
                                    $type = $prop['type'] ?? 'rent';
                                    $typeClass = $typeColors[$type] ?? $typeColors['rent'];
                                    $typeLabel = $typeLabels[$type] ?? ucfirst($type);
                                    $submitted = date('M d, Y', strtotime($prop['created_at']));
                                     $imageUrl = !empty($prop['image_url']) ? htmlspecialchars($prop['image_url'], ENT_QUOTES) : 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=100&q=80';
                                 ?>
                                    <tr data-prop-id="<?= $prop['id'] ?>">
                                        <td><input type="checkbox" class="admin-checkbox"></td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <img src="<?= $imageUrl ?>" alt="Property" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-navy truncate"><?= htmlspecialchars($prop['title'], ENT_QUOTES) ?></p>
                                                    <p class="text-[9px] text-slate-400 truncate"><?= htmlspecialchars($prop['location'], ENT_QUOTES) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-[10px] font-semibold <?= $typeClass ?> px-2 py-0.5 rounded-full"><?= htmlspecialchars($typeLabel) ?></span></td>
                                        <td class="text-slate-500 text-[10px]"><?= htmlspecialchars($ownerName, ENT_QUOTES) ?></td>
                                        <td class="font-bold">KSh <?= $price ?><?= $priceSuffix ? ' ' . htmlspecialchars($priceSuffix, ENT_QUOTES) : '' ?></td>
                                        <td class="text-slate-500 text-[10px]"><?= htmlspecialchars($submitted) ?></td>
                                        <td><span class="status-badge <?= $badgeClass ?>"><i class="fas <?= $badgeIcon ?> text-[7px]"></i><?= htmlspecialchars($status) ?></span></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="openPropertyModal('view', <?= htmlspecialchars(json_encode($prop), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                </button>
                                                <button onclick="openPropertyModal('edit', <?= htmlspecialchars(json_encode($prop), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                </button>
                                                <?php if ($isActive): ?>
                                                    <button onclick="openPropertyModal('reject', <?= htmlspecialchars(json_encode(['id' => $prop['id'], 'title' => $prop['title']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Reject">
                                                        <i class="fas fa-times text-[10px]"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="openPropertyModal('approve', <?= htmlspecialchars(json_encode(['id' => $prop['id'], 'title' => $prop['title']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Approve">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    </button>
                                                <?php endif ?>
                                                <button onclick="openPropertyModal('delete_prop', <?= htmlspecialchars(json_encode(['id' => $prop['id'], 'title' => $prop['title']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                                                    <i class="fas fa-trash text-[10px]"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400">Showing <span class="font-bold text-navy">1-<?= min(5, count($adminProperties)) ?></span> of <span class="font-bold text-navy"><?= number_format($propStatusCounts['all']) ?></span> properties</p>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400"><i class="fas fa-chevron-left text-[9px]"></i></button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md bg-navy text-white text-[10px] font-bold">1</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold">2</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold">3</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400"><i class="fas fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= AGENCIES ================= -->
        <section id="admin-section-agencies" class="admin-section">

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (<?= number_format($totalAgencies) ?>)</button>
                <button class="admin-tab">Real Estate (<?= number_format($agencyCategoryCounts['agency']) ?>)</button>
                <button class="admin-tab">Property Mgmt (<?= number_format($agencyCategoryCounts['property_mgmt']) ?>)</button>
                <button class="admin-tab">Cleaning (<?= number_format($agencyCategoryCounts['cleaning']) ?>)</button>
                <button class="admin-tab">Pending (<?= number_format(0) ?>)</button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10"><input type="checkbox" class="admin-checkbox"></th>
                                <th>Agency</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Rating</th>
                                <th>Verified</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($adminAgencies)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-10 text-slate-500 text-xs">No agencies found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($adminAgencies as $agency): 
                                    $categoryLabels = ['agency' => 'Real Estate Agency', 'property_mgmt' => 'Property Mgmt', 'cleaning' => 'Cleaning'];
                                    $categoryColors = ['agency' => 'text-adminPurple bg-adminPurple/10', 'property_mgmt' => 'text-accentBlue bg-accentBlue/10', 'cleaning' => 'text-adminGreen bg-adminGreen/10'];
                                    $categoryIcons = ['agency' => 'fa-handshake', 'property_mgmt' => 'fa-shield-alt', 'cleaning' => 'fa-spray-can'];
                                    $cat = $agency['category'] ?? 'agency';
                                    $catClass = $categoryColors[$cat] ?? $categoryColors['agency'];
                                    $catLabel = $categoryLabels[$cat] ?? ucfirst($cat);
                                    $catIcon = $categoryIcons[$cat] ?? 'fa-building';
                                    $isActive = (int)($agency['is_active'] ?? 1);
                                    $isVerified = (int)($agency['verified'] ?? 0);
                                    $status = $isActive ? 'Active' : 'Suspended';
                                    $badgeClass = $isActive ? 'status-active' : 'status-suspended';
                                    $badgeIcon = $isActive ? 'fa-check-circle' : 'fa-ban';
                                    $rating = (float)($agency['rating'] ?? 0);
                                    $logo = !empty($agency['logo']) ? htmlspecialchars($agency['logo'], ENT_QUOTES) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=100&q=80';
                                ?>
                                    <tr data-agency-id="<?= $agency['id'] ?>">
                                        <td><input type="checkbox" class="admin-checkbox"></td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                    <img src="<?= $logo ?>" alt="Logo" class="w-8 h-8 rounded object-cover">
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-bold text-navy truncate"><?= htmlspecialchars($agency['name'], ENT_QUOTES) ?></p>
                                                    <p class="text-[9px] text-slate-400 truncate"><?= htmlspecialchars($agency['email'], ENT_QUOTES) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-[10px] font-semibold <?= $catClass ?> px-2 py-0.5 rounded-full"><i class="fas <?= $catIcon ?> mr-1"></i><?= htmlspecialchars($catLabel) ?></span></td>
                                        <td class="text-slate-500 text-[10px]"><?= htmlspecialchars($agency['location'], ENT_QUOTES) ?></td>
                                        <td><span class="text-[10px] font-bold text-accentOrange"><i class="fas fa-star mr-0.5"></i><?= htmlspecialchars($rating) ?></span></td>
                                        <td><span class="status-badge <?= $isVerified ? 'status-active' : 'status-pending' ?>"><i class="fas <?= $isVerified ? 'fa-certificate' : 'fa-clock' ?> text-[7px]"></i><?= $isVerified ? 'Verified' : 'Pending' ?></span></td>
                                        <td><span class="status-badge <?= $badgeClass ?>"><i class="fas <?= $badgeIcon ?> text-[7px]"></i><?= htmlspecialchars($status) ?></span></td>
                                        <td class="text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="openAgencyModal('view', <?= htmlspecialchars(json_encode($agency), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View">
                                                    <i class="fas fa-eye text-[10px]"></i>
                                                </button>
                                                <button onclick="openAgencyModal('edit', <?= htmlspecialchars(json_encode($agency), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit">
                                                    <i class="fas fa-pen text-[10px]"></i>
                                                </button>
                                                <?php if (!$isVerified): ?>
                                                    <button onclick="openAgencyModal('verify', <?= htmlspecialchars(json_encode(['id' => $agency['id'], 'name' => $agency['name']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Verify">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="openAgencyModal('unverify', <?= htmlspecialchars(json_encode(['id' => $agency['id'], 'name' => $agency['name']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Unverify">
                                                        <i class="fas fa-times text-[10px]"></i>
                                                    </button>
                                                <?php endif ?>
                                                <?php if ($isActive): ?>
                                                    <button onclick="openAgencyModal('suspend_agency', <?= htmlspecialchars(json_encode(['id' => $agency['id'], 'name' => $agency['name']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Suspend">
                                                        <i class="fas fa-ban text-[10px]"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button onclick="openAgencyModal('activate_agency', <?= htmlspecialchars(json_encode(['id' => $agency['id'], 'name' => $agency['name']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Activate">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                    </button>
                                                <?php endif ?>
                                                <button onclick="openAgencyModal('delete_agency', <?= htmlspecialchars(json_encode(['id' => $agency['id'], 'name' => $agency['name']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors" title="Delete">
                                                    <i class="fas fa-trash text-[10px]"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= AGENCY MODALS ================= -->
        <!-- View Agency Modal -->
        <div id="agencyModalView" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAgencyModal('agencyModalView')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">View Agency</h3>
                    <button onclick="closeAgencyModal('agencyModalView')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-4" id="agencyViewContent"></div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeAgencyModal('agencyModalView')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit Agency Modal -->
        <div id="agencyModalEdit" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAgencyModal('agencyModalEdit')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Edit Agency</h3>
                    <button onclick="closeAgencyModal('agencyModalEdit')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="agencyEditForm" class="space-y-4" onsubmit="return saveAgencyEdit(event)">
                    <input type="hidden" name="prop_id" id="editAgencyId">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Agency Name</label>
                        <input type="text" name="name" id="editAgencyName" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Location</label>
                        <input type="text" name="location" id="editAgencyLocation" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Category</label>
                        <select name="category" id="editAgencyCategory" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                            <option value="agency">Real Estate Agency</option>
                            <option value="property_mgmt">Property Management</option>
                            <option value="cleaning">Cleaning</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Rating</label>
                        <input type="number" name="rating" id="editAgencyRating" min="0" max="5" step="0.1" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Phone</label>
                        <input type="text" name="phone" id="editAgencyPhone" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Email</label>
                        <input type="email" name="email" id="editAgencyEmail" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Logo URL</label>
                        <input type="text" name="logo" id="editAgencyLogo" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="editAgencyIsActive" class="admin-checkbox">
                        <label for="editAgencyIsActive" class="text-xs text-slate-600">Active</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="verified" id="editAgencyVerified" class="admin-checkbox">
                        <label for="editAgencyVerified" class="text-xs text-slate-600">Verified</label>
                    </div>
                    <div class="flex items-center justify-end pt-2">
                        <button type="button" onclick="closeAgencyModal('agencyModalEdit')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirm Action Modal (Verify / Unverify / Activate / Suspend / Delete) -->
        <div id="agencyModalConfirm" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAgencyModal('agencyModalConfirm')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="agencyConfirmTitle" class="font-serif text-lg text-navy font-semibold">Confirm Action</h3>
                    <button onclick="closeAgencyModal('agencyModalConfirm')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <p id="agencyConfirmMessage" class="text-slate-600 text-sm mb-6">Are you sure?</p>
                <input type="hidden" id="agencyConfirmPropId">
                <input type="hidden" id="agencyConfirmAction">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="closeAgencyModal('agencyModalConfirm')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                    <button id="agencyConfirmBtn" class="px-4 py-2 rounded-lg bg-accentBlue text-white text-xs font-medium hover:bg-accentOrange transition-colors">Confirm</button>
                </div>
            </div>
        </div>

        <!-- Add Agency Modal -->
        <div id="agencyModalAdd" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeAgencyModal('agencyModalAdd')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Add Agency</h3>
                    <button onclick="closeAgencyModal('agencyModalAdd')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="agencyAddForm" class="space-y-4" onsubmit="return saveAgencyAdd(event)">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Agency Name</label>
                        <input type="text" name="name" placeholder="e.g. Prestige Realty Group" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Location</label>
                        <input type="text" name="location" placeholder="e.g. Westlands, Nairobi" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Category</label>
                        <select name="category" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                            <option value="agency">Real Estate Agency</option>
                            <option value="property_mgmt">Property Management</option>
                            <option value="cleaning">Cleaning</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Rating</label>
                        <input type="number" name="rating" placeholder="5" min="0" max="5" step="0.1" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Phone</label>
                        <input type="text" name="phone" placeholder="+254 700 000 000" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Email</label>
                        <input type="email" name="email" placeholder="info@agency.co.ke" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Logo URL</label>
                        <input type="text" name="logo" placeholder="https://..." class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                    </div>
                    <div class="flex items-center justify-end pt-2">
                        <button type="button" onclick="closeAgencyModal('agencyModalAdd')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Create Agency</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= BOOKINGS ================= -->
        <section id="admin-section-bookings" class="admin-section">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Total</p>
                    <p class="text-xl font-bold text-navy"><?= number_format($totalBookings) ?></p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Confirmed</p>
                    <p class="text-xl font-bold text-adminGreen"><?= number_format($bookingStatusCounts['confirmed'] ?? 0) ?></p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Pending</p>
                    <p class="text-xl font-bold text-accentOrange"><?= number_format($bookingStatusCounts['pending'] ?? 0) ?></p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Cancelled</p>
                    <p class="text-xl font-bold text-red-500"><?= number_format($bookingStatusCounts['cancelled'] ?? 0) ?></p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (<?= number_format($totalBookings) ?>)</button>
                <button class="admin-tab">Upcoming (<?= number_format($bookingStatusCounts['upcoming'] ?? 0) ?>)</button>
                <button class="admin-tab">Active (<?= number_format($bookingStatusCounts['active'] ?? 0) ?>)</button>
                <button class="admin-tab">Completed (<?= number_format($bookingStatusCounts['completed'] ?? 0) ?>)</button>
                <button class="admin-tab">Cancelled (<?= number_format($bookingStatusCounts['cancelled'] ?? 0) ?>)</button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Property</th>
                                <th>Client</th>
                                <th>Location</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($adminBookings)): ?>
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-slate-400 text-xs">No bookings found.</td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($adminBookings as $booking): 
                                $clientName = $booking['client_full_name'] ?: ($booking['client_first_name'] . ' ' . $booking['client_last_name']);
                                $clientName = trim($clientName) ?: $booking['client_email'];
                                $ownerName = $booking['owner_company'] ?: ($booking['owner_first_name'] . ' ' . $booking['owner_last_name']);
                                $ownerName = trim($ownerName) ?: $booking['owner_email'];
                                $bookingId = '#HV-' . str_pad($booking['id'], 4, '0', STR_PAD_LEFT);
                                $isActive = !empty($booking['is_active']) && $booking['is_active'] == 1;
                                $statusClass = $isActive ? 'status-confirmed' : 'status-pending';
                                $statusText = $isActive ? 'Confirmed' : 'Pending';
                                $statusIcon = $isActive ? 'fa-check-circle' : 'fa-clock';
                                $clientInitials = strtoupper(substr($booking['client_first_name'] ?: '', 0, 1) . substr($booking['client_last_name'] ?: '', 0, 1)) ?: 'U';
                                $ownerInitials = strtoupper(substr($booking['owner_first_name'] ?: '', 0, 1) . substr($booking['owner_last_name'] ?: '', 0, 1)) ?: 'O';
                            ?>
                                <tr data-booking-id="<?= $booking['id'] ?>">
                                    <td class="font-mono text-[10px] font-bold text-accentBlue"><?= htmlspecialchars($bookingId) ?></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-md bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-building text-slate-400 text-[10px]"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-navy truncate"><?= htmlspecialchars($booking['title']) ?></p>
                                                <p class="text-[9px] text-slate-400 truncate"><?= htmlspecialchars($booking['location'] ?? '—') ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-accentBlue flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0"><?= htmlspecialchars($clientInitials) ?></div>
                                            <span class="text-[10px] text-slate-600"><?= htmlspecialchars($clientName) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-[10px] text-slate-500"><?= htmlspecialchars($booking['client_location'] ?? '—') ?></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-adminPurple flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0"><?= htmlspecialchars($ownerInitials) ?></div>
                                            <span class="text-[10px] text-slate-600"><?= htmlspecialchars($ownerName) ?></span>
                                        </div>
                                    </td>
                                    <td><span class="status-badge <?= $statusClass ?>"><i class="fas <?= $statusIcon ?> text-[7px]"></i><?= htmlspecialchars($statusText) ?></span></td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button onclick="openBookingModal('view', <?= htmlspecialchars(json_encode([
                                                'id' => $booking['id'],
                                                'title' => $booking['title'],
                                                'location' => $booking['location'] ?? '',
                                                'client_name' => $clientName,
                                                'client_email' => $booking['client_email'] ?? '',
                                                'client_phone' => $booking['client_phone'] ?? '',
                                                'client_location' => $booking['client_location'] ?? '',
                                                'owner_name' => $ownerName,
                                                'owner_email' => $booking['owner_email'] ?? '',
                                                'property_type' => $booking['property_type'] ?? '',
                                                'description' => $booking['description'] ?? '',
                                                'is_active' => $booking['is_active'] ?? 1,
                                                'created_at' => $booking['created_at'] ?? '',
                                            ]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View">
                                                <i class="fas fa-eye text-[10px]"></i>
                                            </button>
                                            <button onclick="openBookingModal('edit', <?= htmlspecialchars(json_encode(['id' => $booking['id'], 'title' => $booking['title']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit">
                                                <i class="fas fa-pen text-[10px]"></i>
                                            </button>
                                            <button onclick="openBookingModal('delete', <?= htmlspecialchars(json_encode(['id' => $booking['id'], 'title' => $booking['title']]), ENT_QUOTES) ?>)" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Delete">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Client-Owner Connections -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Client ↔ Owner Connections</h3>
                    <p class="text-[10px] text-slate-400">Users and property owners who have contacted each other</p>
                </div>
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Client Email</th>
                                <th>Owner</th>
                                <th>Owner Email</th>
                                <th>Messages</th>
                                <th>Last Contact</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="connectionsTableBody">
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-400 text-xs">Loading connections…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= BOOKING MODALS ================= -->
        <!-- View Booking Modal -->
        <div id="bookingModalView" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBookingModal('bookingModalView')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">View Booking</h3>
                    <button onclick="closeBookingModal('bookingModalView')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Booking ID</label>
                            <p class="text-xs font-mono font-bold text-navy" id="viewBookingId">#HV-0000</p>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Status</label>
                            <span class="text-xs font-bold text-accentBlue" id="viewBookingStatus">—</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Property Title</label>
                        <p class="text-xs font-semibold text-navy" id="viewBookingTitle">—</p>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Property Location</label>
                        <p class="text-xs text-slate-600" id="viewBookingLocation">—</p>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Property Type</label>
                        <p class="text-xs text-slate-600" id="viewBookingPropertyType">—</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Client Name</label>
                            <p class="text-xs font-semibold text-navy" id="viewBookingClientName">—</p>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Client Email</label>
                            <p class="text-xs text-slate-600 truncate" id="viewBookingClientEmail">—</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Client Phone</label>
                        <p class="text-xs text-slate-600" id="viewBookingClientPhone">—</p>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Client Location</label>
                        <p class="text-xs text-slate-600" id="viewBookingClientLocation">—</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Owner Name</label>
                            <p class="text-xs font-semibold text-navy" id="viewBookingOwnerName">—</p>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Owner Email</label>
                            <p class="text-xs text-slate-600 truncate" id="viewBookingOwnerEmail">—</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Description</label>
                        <p class="text-xs text-slate-600 leading-relaxed" id="viewBookingDescription">—</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Created</label>
                            <p class="text-xs text-slate-600" id="viewBookingCreated">—</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeBookingModal('bookingModalView')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Close</button>
                </div>
            </div>
        </div>

        <!-- Edit Booking Modal -->
        <div id="bookingModalEdit" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBookingModal('bookingModalEdit')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Edit Booking</h3>
                    <button onclick="closeBookingModal('bookingModalEdit')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <form id="bookingEditForm" class="space-y-4" onsubmit="return saveBookingEdit(event)">
                    <input type="hidden" id="editBookingId">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Property Title</label>
                        <input type="text" id="editBookingTitle" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30" required>
                    </div>
                    <div class="flex items-center justify-end pt-2">
                        <button type="button" onclick="closeBookingModal('bookingModalEdit')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors mr-2">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-navy text-white text-xs font-medium hover:bg-accentBlue transition-colors">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Booking Modal -->
        <div id="bookingModalDelete" class="admin-modal fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBookingModal('bookingModalDelete')"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 w-full max-w-md p-6 shadow-2xl animate-in fade-in slide-in-from-top-2 duration-300" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold" id="deleteBookingTitle">Delete Booking</h3>
                    <button onclick="closeBookingModal('bookingModalDelete')" class="modal-close"><i class="fas fa-times text-xs"></i></button>
                </div>
                <p class="text-slate-600 text-sm mb-6" id="deleteBookingMessage">Are you sure?</p>
                <input type="hidden" id="deleteBookingId">
                <div class="flex items-center justify-end gap-2">
                    <button onclick="closeBookingModal('bookingModalDelete')" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                    <button id="deleteBookingConfirmBtn" class="px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition-colors">Delete</button>
                </div>
            </div>
        </div>

        <!-- ================= SUPPORT ================= -->
        <section id="admin-section-messages" class="admin-section">

            <div class="flex flex-col lg:flex-row gap-0 h-[700px]">
                <!-- Conversations list -->
                <div class="flex-1 lg:w-1/3 w-full border-r border-slate-100 bg-white rounded-2xl border-b-0 border-r-0 lg:border-b border-r flex flex-col h-full overflow-hidden">
                    <div class="p-3 border-b border-slate-100 flex items-center justify-between">
                        <div class="admin-search flex items-center gap-2 px-3 py-2 flex-1">
                            <i class="fas fa-search text-slate-400 text-[10px]"></i>
                            <input type="text" id="supportSearchInput" placeholder="Search conversations…" class="bg-transparent text-[11px] outline-none w-full text-slate-600 placeholder-slate-400">
                        </div>
                        <button onclick="loadSupportConversations()" class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors" title="Refresh">
                            <i class="fas fa-sync text-[10px]"></i>
                        </button>
                    </div>
                    <div id="supportConversationList" class="flex-1 overflow-y-auto thin-scroll">
                        <div class="p-6 text-center text-slate-400 text-xs">Loading conversations…</div>
                    </div>
                </div>

                <!-- Chat area -->
                <div class="flex-1 bg-white rounded-2xl border-b-0 lg:border-r border-r border-slate-100 flex flex-col h-full overflow-hidden">
                    <div id="supportChatEmpty" class="flex-1 flex flex-col items-center justify-center text-slate-400 text-xs px-6">
                        <i class="fas fa-comments text-4xl mb-3 opacity-20"></i>
                        <p>Select a conversation to start chatting</p>
                    </div>

                    <div id="supportChatView" class="hidden flex-col h-full overflow-hidden">
                        <!-- Header -->
                        <div class="p-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <div class="flex items-center gap-3 cursor-pointer" onclick="goBackToConversations()">
                                <div class="w-8 h-8 rounded-full bg-accentBlue flex items-center justify-center text-white font-bold text-[10px]">
                                    <span id="supportContactAvatar"></span>
                                </div>
                                <div>
                                    <p id="supportContactName" class="text-xs font-bold text-navy truncate">Contact</p>
                                    <p id="supportContactStatus" class="text-[9px] text-slate-400">Online</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200 transition-colors" title="Call"><i class="fas fa-phone text-[10px]"></i></button>
                                <button class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200 transition-colors" title="Video"><i class="fas fa-video text-[10px]"></i></button>
                                <button class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200 transition-colors" title="More"><i class="fas fa-ellipsis-v text-[10px]"></i></button>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div id="supportMessagesContainer" class="flex-1 overflow-y-auto thin-scroll p-4 space-y-3 bg-[#ECE5DD]">
                            <!-- messages injected here -->
                        </div>

                        <!-- Input -->
                        <div class="p-3 border-t border-slate-100 bg-white">
                            <div class="flex items-center gap-2">
                                <button class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-[#EAEAEA] transition-colors flex-shrink-0">
                                    <i class="fas fa-paperclip text-[10px]"></i>
                                </button>
                                <input type="text" id="supportReplyInput" placeholder="Type a message" class="flex-1 bg-[#EAEAEA] rounded-full px-4 py-2 text-[11px] outline-none text-slate-600 placeholder-slate-400">
                                <button onclick="sendSupportReply()" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#005BB5] text-white hover:bg-[#004A9A] transition-colors flex-shrink-0">
                                    <i class="fas fa-paper-plane text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= TRANSACTIONS ================= -->
        <section id="admin-section-transactions" class="admin-section">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">Total Revenue</p>
                    <p class="text-xl font-bold text-navy mt-1">KSh 8.4M</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">This Month</p>
                    <p class="text-xl font-bold text-adminGreen mt-1">KSh 1.2M</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">Pending Payouts</p>
                    <p class="text-xl font-bold text-accentOrange mt-1">KSh 425K</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">Transactions</p>
                    <p class="text-xl font-bold text-navy mt-1">4,521</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84521</td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Booking</span></td>
                                <td class="text-slate-500 text-[10px]">David Kariuki</td>
                                <td class="text-slate-500 text-[10px]">Jane Mwangi</td>
                                <td class="text-slate-500 text-[10px]">Dec 10, 2024</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 55,500</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84520</td>
                                <td><span class="text-[10px] font-semibold text-accentOrange bg-accentOrange/10 px-2 py-0.5 rounded-full">Payout</span></td>
                                <td class="text-slate-500 text-[10px]">HAVENTRA</td>
                                <td class="text-slate-500 text-[10px]">Guardian PM</td>
                                <td class="text-slate-500 text-[10px]">Dec 10, 2024</td>
                                <td><span class="status-badge status-completed">Processed</span></td>
                                <td class="text-right font-bold text-red-500">− KSh 125,000</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84519</td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Booking</span></td>
                                <td class="text-slate-500 text-[10px]">Sarah Wanjiku</td>
                                <td class="text-slate-500 text-[10px]">Prestige Realty</td>
                                <td class="text-slate-500 text-[10px]">Dec 9, 2024</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 45,000</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84518</td>
                                <td><span class="text-[10px] font-semibold text-adminPurple bg-adminPurple/10 px-2 py-0.5 rounded-full">Fee</span></td>
                                <td class="text-slate-500 text-[10px]">Coastal Homes</td>
                                <td class="text-slate-500 text-[10px]">HAVENTRA</td>
                                <td class="text-slate-500 text-[10px]">Dec 8, 2024</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 8,500</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84517</td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Booking</span></td>
                                <td class="text-slate-500 text-[10px]">James K.</td>
                                <td class="text-slate-500 text-[10px]">Jane Mwangi</td>
                                <td class="text-slate-500 text-[10px]">Dec 7, 2024</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 90,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= PAYOUTS ================= -->
        <section id="admin-section-payouts" class="admin-section">

            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-navy">5 payout requests pending</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Total amount: KSh 425,000. Review and process these requests.</p>
                </div>
                <button onclick="showToast('Processing all payouts')" class="bg-amber-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition-colors whitespace-nowrap">
                    Process All
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Recipient</th>
                                <th>Method</th>
                                <th>Requested</th>
                                <th>Status</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#PAY-1024</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-adminPurple flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">PR</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Prestige Realty</p>
                                            <p class="text-[9px] text-slate-400 truncate">Bank Transfer</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[10px]">M-Pesa</td>
                                <td class="text-slate-500 text-[10px]">2h ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold">KSh 125,000</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="showToast('Approving payout')" class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors" title="Approve"><i class="fas fa-check text-[10px]"></i></button>
                                        <button onclick="showToast('Rejecting payout')" class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors" title="Reject"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#PAY-1023</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accentBlue flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">GP</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Guardian PM</p>
                                            <p class="text-[9px] text-slate-400 truncate">Bank Transfer</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[10px]">Bank</td>
                                <td class="text-slate-500 text-[10px]">5h ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold">KSh 185,000</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#PAY-1022</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accentOrange flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">JM</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Jane Mwangi</p>
                                            <p class="text-[9px] text-slate-400 truncate">M-Pesa</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[10px]">M-Pesa</td>
                                <td class="text-slate-500 text-[10px]">1d ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold">KSh 115,000</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= REVIEWS ================= -->
        <section id="admin-section-reviews" class="admin-section">

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (1,842)</button>
                <button class="admin-tab">Published (1,798)</button>
                <button class="admin-tab">Flagged (24)</button>
                <button class="admin-tab">Pending (20)</button>
            </div>

            <div class="space-y-3">
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-accentOrange flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">DK</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-navy">David Kariuki</span>
                                <span class="text-[9px] text-accentOrange"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                <span class="text-[9px] text-slate-400">on Beachfront Villa</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">"Absolutely stunning property! The beach access was perfect and the host was incredibly responsive. Will definitely book again."</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[9px] text-slate-400">Dec 10, 2024</span>
                                <span class="status-badge status-active">Published</span>
                            </div>
                        </div>
                        <div class="flex gap-1 flex-shrink-0">
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit"><i class="fas fa-pen text-[10px]"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Delete"><i class="fas fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-red-200 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-red-500 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">FK</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-navy">Fred Kimani</span>
                                <span class="text-[9px] text-accentOrange"><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                <span class="text-[9px] text-slate-400">on Luxury Safari Lodge</span>
                                <span class="text-[9px] text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded"><i class="fas fa-flag mr-0.5"></i>Flagged</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">"This place was terrible. The photos are misleading and the staff was rude. I would NOT recommend this to anyone..."</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[9px] text-slate-400">Dec 9, 2024</span>
                                <span class="text-[9px] text-red-500">Reported 3 times</span>
                            </div>
                        </div>
                        <div class="flex gap-1 flex-shrink-0">
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors" title="Approve"><i class="fas fa-check text-[10px]"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors" title="Remove"><i class="fas fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-adminGreen flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">SW</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-navy">Sarah Wanjiku</span>
                                <span class="text-[9px] text-accentOrange"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                <span class="text-[9px] text-slate-400">on Prestige Realty</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">"Excellent service from start to finish. The agent was professional and found exactly what we were looking for."</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[9px] text-slate-400">Dec 8, 2024</span>
                                <span class="status-badge status-active">Published</span>
                            </div>
                        </div>
                        <div class="flex gap-1 flex-shrink-0">
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= CATEGORIES ================= -->
        <section id="admin-section-categories" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <button onclick="showToast('Add category form opened')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">For Rent</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Houses</span>
                            <span class="text-[10px] font-bold text-navy">245</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Apartments</span>
                            <span class="text-[10px] font-bold text-navy">386</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Bedsitters</span>
                            <span class="text-[10px] font-bold text-navy">178</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Townhouses</span>
                            <span class="text-[10px] font-bold text-navy">112</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Villas</span>
                            <span class="text-[10px] font-bold text-navy">65</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">For Sale</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Houses</span>
                            <span class="text-[10px] font-bold text-navy">198</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Apartments</span>
                            <span class="text-[10px] font-bold text-navy">156</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Land</span>
                            <span class="text-[10px] font-bold text-navy">389</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Townhouses</span>
                            <span class="text-[10px] font-bold text-navy">87</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Commercial</span>
                            <span class="text-[10px] font-bold text-navy">245</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">Short Stay / Airbnb</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Beachfront Villas</span>
                            <span class="text-[10px] font-bold text-navy">234</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">City Apartments</span>
                            <span class="text-[10px] font-bold text-navy">412</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Safari Lodges</span>
                            <span class="text-[10px] font-bold text-navy">87</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Holiday Homes</span>
                            <span class="text-[10px] font-bold text-navy">298</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Serviced Apartments</span>
                            <span class="text-[10px] font-bold text-navy">114</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">Commercial</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Offices</span>
                            <span class="text-[10px] font-bold text-navy">89</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Shops</span>
                            <span class="text-[10px] font-bold text-navy">76</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Warehouses</span>
                            <span class="text-[10px] font-bold text-navy">45</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Industrial</span>
                            <span class="text-[10px] font-bold text-navy">35</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= LOCATIONS ================= -->
        <section id="admin-section-locations" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <button onclick="showToast('Add location form opened')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add Location
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Neighborhoods</th>
                            <th>Properties</th>
                            <th>Agencies</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-bold">Nairobi</td>
                            <td class="text-slate-500 text-[10px]">Westlands, Kilimani, Karen, Kileleshwa, Lavington, Runda…</td>
                            <td class="font-bold">1,245</td>
                            <td class="font-bold">72</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">Mombasa</td>
                            <td class="text-slate-500 text-[10px]">Diani, Nyali, Bamburi, Mtwapa, Watamu…</td>
                            <td class="font-bold">567</td>
                            <td class="font-bold">28</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">Kisumu</td>
                            <td class="text-slate-500 text-[10px]">Milimani, Nyalenda, Riat Hills, Mamboleo…</td>
                            <td class="font-bold">234</td>
                            <td class="font-bold">12</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">Nakuru</td>
                            <td class="text-slate-500 text-[10px]">Milimani, Section 58, Kiamunyi, Lanet…</td>
                            <td class="font-bold">189</td>
                            <td class="font-bold">8</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>


        <!-- ================= REPORTS ================= -->
        <section id="admin-section-reports" class="admin-section">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">User Growth</h3>
                    <div class="flex items-end gap-2 h-32">
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 30%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 45%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 55%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 65%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 75%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 85%"></div>
                        <div class="flex-1 bg-accentBlue rounded-t" style="height: 100%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[8px] text-slate-400">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Revenue by Category</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Short Stay</span>
                                <span class="text-[10px] font-bold text-navy">KSh 3.8M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-accentBlue rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Rentals</span>
                                <span class="text-[10px] font-bold text-navy">KSh 2.4M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-adminGreen rounded-full" style="width: 55%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Sales</span>
                                <span class="text-[10px] font-bold text-navy">KSh 1.6M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-accentOrange rounded-full" style="width: 35%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Agency Fees</span>
                                <span class="text-[10px] font-bold text-navy">KSh 0.6M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-adminPurple rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Export Reports</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <button onclick="showToast('Exporting users report')" class="p-3 rounded-xl border border-slate-200 hover:border-accentBlue hover:bg-accentBlue/5 transition-colors text-left group">
                        <i class="fas fa-users text-accentBlue text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Users Report</p>
                        <p class="text-[9px] text-slate-400">CSV · Last 30 days</p>
                    </button>
                    <button onclick="showToast('Exporting properties report')" class="p-3 rounded-xl border border-slate-200 hover:border-accentOrange hover:bg-accentOrange/5 transition-colors text-left group">
                        <i class="fas fa-building text-accentOrange text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Properties Report</p>
                        <p class="text-[9px] text-slate-400">CSV · All listings</p>
                    </button>
                    <button onclick="showToast('Exporting revenue report')" class="p-3 rounded-xl border border-slate-200 hover:border-adminGreen hover:bg-adminGreen/5 transition-colors text-left group">
                        <i class="fas fa-chart-line text-adminGreen text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Revenue Report</p>
                        <p class="text-[9px] text-slate-400">CSV · This quarter</p>
                    </button>
                    <button onclick="showToast('Exporting bookings report')" class="p-3 rounded-xl border border-slate-200 hover:border-adminPurple hover:bg-adminPurple/5 transition-colors text-left group">
                        <i class="fas fa-calendar-check text-adminPurple text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Bookings Report</p>
                        <p class="text-[9px] text-slate-400">CSV · All time</p>
                    </button>
                </div>
            </div>
        </section>

        <script>
            var supportBubbleChart = null;
            function loadSupportBubbleChart() {
                fetch('admin.php?ajax=support_bubble')
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (!data.success || !data.bubbles) return;
                        var ctx = document.getElementById('supportBubbleChart');
                        if (!ctx) return;
                        if (supportBubbleChart) {
                            supportBubbleChart.destroy();
                        }
                        var maxY = 0;
                        data.bubbles.forEach(function(b) { if (b.y > maxY) maxY = b.y; });
                        supportBubbleChart = new Chart(ctx.getContext('2d'), {
                            type: 'bubble',
                            data: {
                                datasets: [{
                                    data: data.bubbles.map(function(b) { return { x: b.x, y: b.y, r: b.r }; }),
                                    backgroundColor: data.bubbles.map(function(b) { return b.color; }),
                                    borderColor: '#ffffff',
                                    borderWidth: 2,
                                    hoverBackgroundColor: data.bubbles.map(function(b) { return b.color; }),
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                var b = data.bubbles[context.dataIndex];
                                                return b.label + ': ' + b.count + ' messages';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        type: 'linear',
                                        position: 'bottom',
                                        min: -1.5,
                                        max: 1.5,
                                        ticks: {
                                            stepSize: 1,
                                            callback: function(value) {
                                                return value === -1 ? 'Senders' : (value === 1 ? 'Admins' : '');
                                            }
                                        },
                                        title: { display: true, text: 'Sender ← → Admin', font: { size: 11, weight: 'bold' }, color: '#64748b' }
                                    },
                                    y: {
                                        min: -0.5,
                                        max: maxY + 0.5,
                                        display: false
                                    }
                                },
                                animation: {
                                    duration: 1000,
                                    easing: 'easeOutQuart'
                                }
                            }
                        });
                    });
            }
            document.addEventListener('DOMContentLoaded', function() {
                loadSupportBubbleChart();
            });
        </script>
        <section id="admin-section-settings" class="admin-section">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- General settings -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">General</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Platform Name</label>
                            <input type="text" value="HAVENTRA" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Support Email</label>
                            <input type="email" value="support@haventra.com" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Default Currency</label>
                            <select class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                                <option>KES — Kenyan Shilling</option>
                                <option>USD — US Dollar</option>
                                <option>EUR — Euro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Platform Fee (%)</label>
                            <input type="number" value="5" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        </div>
                    </div>
                </div>

                <!-- Feature toggles -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Features</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center">
                                    <i class="fas fa-building text-accentBlue text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Property Listings</p>
                                    <p class="text-[10px] text-slate-400">Allow new property submissions</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-adminGreen/10 flex items-center justify-center">
                                    <i class="fas fa-handshake text-adminGreen text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Agency Registration</p>
                                    <p class="text-[10px] text-slate-400">Allow new agencies to sign up</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-accentOrange/10 flex items-center justify-center">
                                    <i class="fas fa-star text-accentOrange text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Reviews</p>
                                    <p class="text-[10px] text-slate-400">Allow users to leave reviews</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-adminPurple/10 flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-adminPurple text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Auto-approve Verified</p>
                                    <p class="text-[10px] text-slate-400">Auto-approve verified agency listings</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Maintenance mode -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Maintenance</h3>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-red-500 flex items-center justify-center">
                                <i class="fas fa-wrench text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-navy">Maintenance Mode</p>
                                <p class="text-[10px] text-slate-500">Temporarily disable the platform</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-red-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </label>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">When enabled, users will see a maintenance page.</p>
                </div>

                <!-- Security -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Security</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-xs font-semibold text-navy">Two-Factor Auth</p>
                                <p class="text-[10px] text-slate-400">Require 2FA for admin accounts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-xs font-semibold text-navy">Email Verification</p>
                                <p class="text-[10px] text-slate-400">Require email verification for users</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <button class="w-full mt-2 px-4 py-2.5 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fas fa-key mr-1.5"></i> Change Admin Password
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="showToast('Settings saved successfully')" class="bg-navy text-white text-xs font-bold px-6 py-2.5 rounded-lg hover:bg-accentBlue transition-colors">
                    <i class="fas fa-save mr-1.5"></i> Save All Settings
                </button>
            </div>
        </section>


        <!-- ================= ACTIVITY LOG ================= -->
        <section id="admin-section-activity" class="admin-section">

            <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="admin-search flex items-center gap-2 px-3 py-2 w-full max-w-md">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                        <input type="text" placeholder="Search activity…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                    </div>
                    <button onclick="showToast('Exporting activity log')" class="text-[10px] text-accentBlue font-semibold hover:underline hidden sm:block">Export Log</button>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-plus text-emerald-600 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">User registered</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">David Kariuki (david.k@email.com) created a new client account</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">2m ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-accentBlue/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-accentBlue text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Property approved</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">"Modern 3 Bed Villa" in Kilimani approved by admin</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">15m ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-accentOrange/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-wallet text-accentOrange text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Payout processed</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">KSh 125,000 paid to Prestige Realty Group via M-Pesa</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">1h ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-adminPurple/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-certificate text-adminPurple text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Agency verified</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Coastal Homes Ltd verified and approved as a real estate agency</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">2h ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-ban text-red-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">User suspended</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Fred Kimani suspended for violating platform terms</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">3h ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-flag text-amber-600 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Listing reported</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">"Prime 1/4 Acre Plot" reported by 2 users for incorrect information</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">5h ago</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cog text-slate-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Settings updated</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Platform fee changed from 4% to 5% by admin</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">1d ago</span>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>


<!-- ================= TOAST ================= -->
<div id="adminToast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-navy text-white text-xs font-semibold px-5 py-3 rounded-xl shadow-2xl z-[5000] hidden items-center gap-2">
    <i class="fas fa-check-circle text-accentOrange"></i>
    <span id="adminToastMsg">Action completed</span>
</div>


<script>
/* ============================================================
   SIDEBAR
   ============================================================ */
function openSidebar() {
    document.getElementById('adminSidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

/* ============================================================
   SECTION SWITCHING
   ============================================================ */
const adminSections = {
    dashboard: { title: 'Dashboard', subtitle: 'Platform overview & key metrics' },
    users: { title: 'User Management', subtitle: 'Manage all platform users' },
    properties: { title: 'Property Management', subtitle: 'Review, approve, and manage listings' },
    agencies: { title: 'Agency Management', subtitle: 'Manage agencies and companies' },
    bookings: { title: 'Bookings Management', subtitle: 'Manage all reservations' },
    messages: { title: 'Support Messages', subtitle: 'User inquiries and complaints' },
    transactions: { title: 'Transactions', subtitle: 'All financial transactions' },
    payouts: { title: 'Payouts', subtitle: 'Withdrawal requests' },
    reviews: { title: 'Reviews Moderation', subtitle: 'Review and moderate user reviews' },
    categories: { title: 'Categories', subtitle: 'Property categories and types' },
    locations: { title: 'Locations', subtitle: 'Cities, neighborhoods, and regions' },
    reports: { title: 'Reports & Analytics', subtitle: 'Platform performance and insights' },
    settings: { title: 'Platform Settings', subtitle: 'Configure platform-wide settings' },
    activity: { title: 'Activity Log', subtitle: 'Complete history of platform events' },
};

function showAdminSection(name, navEl) {
    // Hide all sections
    document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
    
    // Show target
    const target = document.getElementById('admin-section-' + name);
    if (target) target.classList.add('active');

    // Update nav active states
    document.querySelectorAll('.admin-nav').forEach(n => n.classList.remove('active'));
    if (navEl) navEl.classList.add('active');

    // Update page title
    const info = adminSections[name] || { title: name, subtitle: '' };
    document.getElementById('adminPageTitle').textContent = info.title;
    document.getElementById('adminPageSubtitle').textContent = info.subtitle;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Close sidebar on mobile
    if (window.innerWidth < 1024) closeSidebar();

    // Load support data when messages section is shown
    if (name === 'messages') {
        loadSupportConversations();
    }
    // Load booking connections when bookings section is shown
    if (name === 'bookings') {
        loadBookingConnections();
    }
}

function adminLogout() {
    fetch('api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=logout'
    }).then(function(r) { return r.json(); }).then(function(result) {
        if (result.success) {
            window.location.href = 'index.php';
        } else {
            showToast('Logout failed: ' + (result.message || 'Unknown error'));
        }
    }).catch(function() {
        // Fallback: direct navigation
        window.location.href = 'index.php?page=logout';
    });
}

/* ============================================================
   TOAST
   ============================================================ */
let toastTimeout;
function showToast(message) {
    const toast = document.getElementById('adminToast');
    const msg = document.getElementById('adminToastMsg');
    msg.textContent = message;
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
    }, 2500);
}

/* ============================================================
   TABLE SELECT ALL
   ============================================================ */
document.querySelectorAll('.admin-checkbox').forEach(checkbox => {
    // Individual checkboxes handled by browser default
});

/* ============================================================
   KEYBOARD SHORTCUT
   ============================================================ */
document.addEventListener('keydown', function(e) {
    // Cmd/Ctrl + K to focus search
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('.admin-search input');
        if (searchInput) searchInput.focus();
    }
});

/* ============================================================
   RESPONSIVE FIX
   ============================================================ */
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
});
</script>

</body>
</html>