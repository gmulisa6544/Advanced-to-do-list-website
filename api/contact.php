<?php
// Simple contact form handler: validates input and appends submissions to backups/contact_submissions.json
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../contact.php'); exit;
}

$name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Basic validation
if ($name === '' || $email === '' || $message === '') {
  $err = 'Please fill in all required fields.';
  header('Location: ../contact.php?error=' . urlencode($err)); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $err = 'Please provide a valid email address.';
  header('Location: ../contact.php?error=' . urlencode($err)); exit;
}

$entry = [
  'name' => $name,
  'email' => $email,
  'message' => $message,
  'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
  'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
  'created_at' => date('c')
];

$dir = __DIR__ . '/../backups';
if (!is_dir($dir)) {
  @mkdir($dir, 0755, true);
}
$file = $dir . '/contact_submissions.json';

// Read existing
$data = [];
if (file_exists($file)) {
  $json = @file_get_contents($file);
  $decoded = @json_decode($json, true);
  if (is_array($decoded)) { $data = $decoded; }
}

$data[] = $entry;

// Write back safely
if (@file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX) === false) {
  $err = 'Failed to save your message. Please try again later.';
  header('Location: ../contact.php?error=' . urlencode($err)); exit;
}

// Redirect back with success
header('Location: ../contact.php?success=1'); exit;
