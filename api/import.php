<?php
// Simple import endpoint to restore tasks/categories from JSON upload
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/tasks.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
$user_id = (int)$_SESSION['user_id'];

header('Content-Type: application/json; charset=utf-8');

if (empty($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
    exit;
}

$data = file_get_contents($_FILES['import_file']['tmp_name']);
$json = json_decode($data, true);
if (!is_array($json)) { echo json_encode(['success' => false, 'error' => 'Invalid JSON']); exit; }

$cats = $json['categories'] ?? [];
$tasks = $json['tasks'] ?? [];

// Map of old category id => new category id
$catMap = [];

// Insert categories (avoid duplicates by name)
foreach ($cats as $c) {
    $name = trim($c['name'] ?? '');
    if ($name === '') continue;
    // check existing
    $stmt = $mysqli->prepare('SELECT id FROM categories WHERE user_id = ? AND name = ? LIMIT 1');
    $stmt->bind_param('is', $user_id, $name);
    $stmt->execute(); $stmt->bind_result($existingId);
    if ($stmt->fetch()) { $newId = $existingId; $stmt->close(); }
    else { $stmt->close(); $ins = $mysqli->prepare('INSERT INTO categories (user_id, name) VALUES (?, ?)'); $ins->bind_param('is', $user_id, $name); $ins->execute(); $newId = $ins->insert_id; $ins->close(); }
    $catMap[$c['id'] ?? null] = $newId;
}

// Insert tasks
$added = 0; $errors = [];
foreach ($tasks as $t) {
    $title = trim($t['title'] ?? '');
    if ($title === '') continue;
    $description = $t['description'] ?? '';
    $priority = in_array($t['priority'] ?? '', ['Low','Medium','High']) ? $t['priority'] : 'Medium';
    $due_date = $t['due_date'] ?? null;
    $oldCat = $t['category_id'] ?? null;
    $catId = $catMap[$oldCat] ?? null;
    list($ok, $res) = add_task($mysqli, $user_id, $title, $description, $priority, $due_date, $catId);
    if ($ok) $added++; else $errors[] = $res;
}

echo json_encode(['success' => true, 'added' => $added, 'errors' => $errors]);
