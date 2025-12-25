<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple sanitize helper for input values
function sanitize($value) {
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

// Register a new user. Returns true on success, false on failure and sets $error message.
function register_user($mysqli, $username, $email, $password, &$error) {
    $username = sanitize($username);
    $email = sanitize($email);

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill all required fields.';
        return false;
    }

    // Check if username or email already exists
    $stmt = $mysqli->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
    if (!$stmt) { $error = 'Database error (prepare).'; return false; }
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $error = 'Username or email already taken.';
        return false;
    }
    $stmt->close();

    // Hash the password securely
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $mysqli->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
    if (!$stmt) { $error = 'Database error (prepare insert).'; return false; }
    $stmt->bind_param('sss', $username, $email, $hash);
    $ok = $stmt->execute();
    if (!$ok) {
        $error = 'Database error (execute insert).';
        $stmt->close();
        return false;
    }
    $stmt->close();
    return true;
}

// Log a user in using username or email + password. On success sets $_SESSION['user_id']
function login_user($mysqli, $user_or_email, $password, &$error) {
    $user_or_email = sanitize($user_or_email);

    if (empty($user_or_email) || empty($password)) {
        $error = 'Please provide credentials.';
        return false;
    }

    $stmt = $mysqli->prepare('SELECT id, password FROM users WHERE username = ? OR email = ? LIMIT 1');
    if (!$stmt) { $error = 'Database error (prepare select).'; return false; }
    $stmt->bind_param('ss', $user_or_email, $user_or_email);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    if ($stmt->fetch()) {
        $stmt->close();
        if (password_verify($password, $hash)) {
            // Credentials ok — set session
            $_SESSION['user_id'] = $id;
            return true;
        } else {
            $error = 'Incorrect password.';
            return false;
        }
    } else {
        $stmt->close();
        $error = 'User not found.';
        return false;
    }
}

// Simple helper to require login on a page
function require_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

?>
