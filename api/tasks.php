<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/tasks.php';

// Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
$user_id = (int)$_SESSION['user_id'];

$action = $_REQUEST['action'] ?? 'list';

if ($action === 'list') {
    $tasks = get_tasks($mysqli, $user_id);
    $cats = get_categories($mysqli, $user_id);
    echo json_encode(['success' => true, 'tasks' => $tasks, 'categories' => $cats]);
    exit;
}

if ($action === 'add') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $priority = $_POST['priority'] ?? 'Medium';
    $due_date = $_POST['due_date'] ?? null;
    $category_id = $_POST['category_id'] ?? '';
    list($ok, $res) = add_task($mysqli, $user_id, $title, $description, $priority, $due_date, $category_id);
    if ($ok) { echo json_encode(['success' => true, 'id' => $res]); } else { echo json_encode(['success' => false, 'error' => $res]); }
    exit;
}

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $fields = [];
    foreach (['title','description','priority','due_date','completed','category_id'] as $f) {
        if (isset($_POST[$f])) { $fields[$f] = $_POST[$f]; }
    }
    list($ok, $res) = update_task($mysqli, $user_id, $id, $fields);
    if ($ok) { echo json_encode(['success' => true]); } else { echo json_encode(['success' => false, 'error' => $res]); }
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    list($ok, $res) = delete_task($mysqli, $user_id, $id);
    if ($ok) { echo json_encode(['success' => true]); } else { echo json_encode(['success' => false, 'error' => $res]); }
    exit;
}

if ($action === 'add_category') {
    $name = $_POST['name'] ?? '';
    list($ok, $res) = add_category($mysqli, $user_id, $name);
    if ($ok) { echo json_encode(['success' => true, 'id' => $res]); } else { echo json_encode(['success' => false, 'error' => $res]); }
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Unknown action']);
