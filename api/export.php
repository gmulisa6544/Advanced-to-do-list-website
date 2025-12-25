<?php
// Export endpoint: JSON, CSV, SQL, and server-side backup
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/tasks.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo 'Not authenticated';
    exit;
}
$user_id = (int)$_SESSION['user_id'];
$action = $_GET['action'] ?? 'json';

$tasks = get_tasks($mysqli, $user_id);
$cats = get_categories($mysqli, $user_id);

if ($action === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['tasks' => $tasks, 'categories' => $cats]);
    exit;
}

if ($action === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="todo-export-' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['id','title','description','priority','due_date','completed','category_id','category_name','created_at','updated_at']);
    foreach ($tasks as $t) {
        fputcsv($out, [$t['id'],$t['title'],$t['description'],$t['priority'],$t['due_date'],$t['completed'],$t['category_id'],$t['category_name'],$t['created_at'] ?? '', $t['updated_at'] ?? '']);
    }
    fclose($out);
    exit;
}

if ($action === 'sql') {
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="todo-export-' . date('Ymd_His') . '.sql"');
    // Produce simple INSERT statements for categories and tasks
    foreach ($cats as $c) {
        $name = $mysqli->real_escape_string($c['name']);
        echo "INSERT INTO categories (id, user_id, name, created_at) VALUES ({$c['id']}, {$user_id}, '{$name}', NOW());\n";
    }
    foreach ($tasks as $t) {
        $title = $mysqli->real_escape_string($t['title']);
        $desc = $mysqli->real_escape_string($t['description']);
        $prio = $mysqli->real_escape_string($t['priority']);
        $due = $t['due_date'] ? "'{$t['due_date']}'" : 'NULL';
        $catid = $t['category_id'] ? $t['category_id'] : 'NULL';
        $completed = (int)$t['completed'];
        echo "INSERT INTO tasks (id, user_id, category_id, title, description, priority, due_date, completed, created_at, updated_at) VALUES ({$t['id']}, {$user_id}, {$catid}, '{$title}', '{$desc}', '{$prio}', {$due}, {$completed}, NOW(), NULL);\n";
    }
    exit;
}

if ($action === 'backup') {
    // Create backups folder if missing
    $dir = __DIR__ . '/../backups';
    if (!is_dir($dir)) { mkdir($dir, 0755, true); }
    $file = $dir . '/todo-backup-' . date('Ymd_His') . '.json';
    $data = ['tasks' => $tasks, 'categories' => $cats, 'created_at' => date(DATE_ATOM)];
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'file' => 'backups/' . basename($file)]);
    exit;
}

http_response_code(400);
echo 'Unknown action';
