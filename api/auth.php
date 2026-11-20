<?php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$remember = isset($_POST['remember']) && $_POST['remember'] === '1';
if ($remember) {
    // Extend session cookie lifetime for remember me (must be before session_start)
    session_set_cookie_params(60 * 60 * 24 * 30); // 30 days
}

if (session_status() === PHP_SESSION_NONE) session_start();

$action = $_POST['action'] ?? '';

function respond($success, $data = [], $message = '') {
    echo json_encode(array_merge(['success' => $success], $data, ['message' => $message]));
    exit;
}

function table_exists(PDO $pdo, $t) {
    try {
        $s = $pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?");
        $s->execute([$t]);
        return ((int)$s->fetchColumn()) > 0;
    } catch (Throwable $e) {
        return false;
    }
}

function column_exists(PDO $pdo, $t, $c) {
    try {
        $s = $pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?");
        $s->execute([$t, $c]);
        return ((int)$s->fetchColumn()) > 0;
    } catch (Throwable $e) {
        return false;
    }
}

// Ensure users table exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(190) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NULL,
        last_name VARCHAR(100) NULL,
        phone VARCHAR(60) NULL,
        role ENUM('client','owner','company','agency') NOT NULL DEFAULT 'client',
        company_name VARCHAR(190) NULL,
        is_verified TINYINT(1) NOT NULL DEFAULT 0,
        first_login TINYINT(1) NOT NULL DEFAULT 1,
        last_login TIMESTAMP NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY idx_users_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Add first_login column if missing
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'first_login')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN first_login TINYINT(1) NOT NULL DEFAULT 1");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'last_login')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'is_verified')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN is_verified TINYINT(1) NOT NULL DEFAULT 0");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'role')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN role ENUM('client','owner','company','agency') NOT NULL DEFAULT 'client'");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'company_name')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN company_name VARCHAR(190) NULL");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'first_name')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN first_name VARCHAR(100) NULL");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'last_name')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN last_name VARCHAR(100) NULL");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'phone')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(60) NULL");
    }
    if (table_exists($pdo, 'users') && !column_exists($pdo, 'users', 'password_hash')) {
        $pdo->exec("ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NOT NULL DEFAULT ''");
    }
} catch (Throwable $e) {
    error_log('Users table setup error: ' . $e->getMessage());
}

// Ensure user_profiles table exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        email VARCHAR(190) NULL,
        full_name VARCHAR(190) NULL,
        phone VARCHAR(60) NULL,
        location VARCHAR(190) NULL,
        bio TEXT NULL,
        avatar_url VARCHAR(255) NULL,
        account_role VARCHAR(40) NULL,
        company_name VARCHAR(190) NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uq_user_profiles_email (email),
        KEY idx_user_profiles_user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Throwable $e) {
    error_log('user_profiles table setup error: ' . $e->getMessage());
}

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (!$email || !$password) {
        respond(false, [], 'Email and password are required');
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            respond(false, [], 'Invalid email or password');
        }

        // Update last_login and set first_login to 0 if it was 1
        $isFirstLogin = (int)$user['first_login'] === 1;
        $stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, first_login = 0 WHERE id = ?");
        $stmt->execute([$user['id']]);

        // Set session
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['company_name'] = $user['company_name'];
        $_SESSION['full_name'] = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        $_SESSION['name'] = $_SESSION['full_name'];

        respond(true, [
            'user' => [
                'id' => (int)$user['id'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'full_name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
                'role' => $user['role'],
                'company_name' => $user['company_name'],
                'first_login' => $isFirstLogin
            ]
        ], 'Welcome back!');
    } catch (Throwable $e) {
        respond(false, [], 'Login failed: ' . $e->getMessage());
    }
}

if ($action === 'register') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'client';
    $companyName = trim($_POST['company_name'] ?? '');

    if (!$firstName || !$lastName || !$email || !$phone || !$password) {
        respond(false, [], 'All fields are required');
    }
    if ($password !== $confirm) {
        respond(false, [], 'Passwords do not match');
    }
    if (strlen($password) < 8) {
        respond(false, [], 'Password must be at least 8 characters');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, [], 'Invalid email format');
    }
    if (!in_array($role, ['client', 'owner', 'company', 'agency'])) {
        $role = 'client';
    }

    try {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            respond(false, [], 'An account with this email already exists');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, first_name, last_name, phone, role, company_name, first_login) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([$email, $hash, $firstName, $lastName, $phone, $role, $companyName ?: null]);
        $userId = (int)$pdo->lastInsertId();

        // Create user_profiles entry
        $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, email, full_name, phone, account_role, company_name) VALUES (?, ?, ?, ?, ?, ?)");
        $fullName = trim($firstName . ' ' . $lastName);
        $stmt->execute([$userId, $email, $fullName, $phone, $role, $companyName ?: null]);

        // Set session
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['company_name'] = $companyName;
        $_SESSION['full_name'] = $fullName;
        $_SESSION['name'] = $fullName;

        respond(true, [
            'user' => [
                'id' => $userId,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'full_name' => $fullName,
                'role' => $role,
                'company_name' => $companyName,
                'first_login' => true
            ]
        ], 'Account created successfully!');
    } catch (Throwable $e) {
        respond(false, [], 'Registration failed: ' . $e->getMessage());
    }
}

if ($action === 'logout') {
    session_destroy();
    respond(true, [], 'Logged out successfully');
}

if ($action === 'check') {
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT id, email, first_name, last_name, role, company_name FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            respond(true, [
                'user' => [
                    'id' => (int)$user['id'],
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'full_name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
                    'role' => $user['role'],
                    'company_name' => $user['company_name'],
                    'first_login' => false
                ]
            ]);
        }
    }
    respond(false, [], 'Not authenticated');
}

respond(false, [], 'Invalid action');