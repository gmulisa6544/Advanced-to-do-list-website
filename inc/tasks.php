<?php
require_once __DIR__ . '/auth.php';

function get_tasks($mysqli, $user_id) {
    $stmt = $mysqli->prepare('SELECT t.id, t.title, t.description, t.priority, t.due_date, t.completed, t.category_id, c.name AS category_name FROM tasks t LEFT JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? ORDER BY t.due_date IS NULL, t.due_date ASC, FIELD(t.priority, "High","Medium","Low")');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $tasks = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $tasks;
}

function add_task($mysqli, $user_id, $title, $description, $priority, $due_date, $category_id) {
    $title = trim($title);
    if ($title === '') { return [false, 'Title required']; }
    $stmt = $mysqli->prepare('INSERT INTO tasks (user_id, category_id, title, description, priority, due_date) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$stmt) { return [false, 'DB prepare failed']; }
    if ($category_id === '') { $category_id = null; }
    $stmt->bind_param('iissss', $user_id, $category_id, $title, $description, $priority, $due_date);
    $ok = $stmt->execute();
    if (!$ok) { $err = $stmt->error; $stmt->close(); return [false, $err]; }
    $id = $stmt->insert_id;
    $stmt->close();
    return [true, $id];
}

function update_task($mysqli, $user_id, $task_id, $fields) {
    // Build simple update from allowed fields
    $allowed = ['title','description','priority','due_date','completed','category_id'];
    $set = [];
    $types = '';
    $values = [];
    foreach ($allowed as $f) {
        if (array_key_exists($f, $fields)) {
            $set[] = "$f = ?";
            $values[] = $fields[$f];
            $types .= 's';
        }
    }
    if (empty($set)) { return [false, 'No fields to update']; }
    $sql = 'UPDATE tasks SET ' . implode(', ', $set) . ' , updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?';
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) { return [false, 'DB prepare failed']; }
    // bind params dynamically
    $types .= 'ii';
    $values[] = $task_id;
    $values[] = $user_id;
    $bind_names[] = $types;
    // using call_user_func_array requires refs
    $bind_params = [];
    $bind_params[] = &$bind_names[0];
    for ($i=0;$i<count($values);$i++) { $bind_params[] = &$values[$i]; }
    call_user_func_array([$stmt, 'bind_param'], $bind_params);
    $ok = $stmt->execute();
    if (!$ok) { $err = $stmt->error; $stmt->close(); return [false, $err]; }
    $stmt->close();
    return [true, null];
}

function delete_task($mysqli, $user_id, $task_id) {
    $stmt = $mysqli->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    if (!$stmt) { return [false, 'DB prepare failed']; }
    $stmt->bind_param('ii', $task_id, $user_id);
    $ok = $stmt->execute();
    if (!$ok) { $err = $stmt->error; $stmt->close(); return [false, $err]; }
    $stmt->close();
    return [true, null];
}

function get_categories($mysqli, $user_id) {
    $stmt = $mysqli->prepare('SELECT id, name FROM categories WHERE user_id = ? ORDER BY name ASC');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $cats = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $cats;
}

function add_category($mysqli, $user_id, $name) {
    $name = trim($name);
    if ($name === '') { return [false, 'Name required']; }
    $stmt = $mysqli->prepare('INSERT INTO categories (user_id, name) VALUES (?, ?)');
    if (!$stmt) { return [false, 'DB prepare failed']; }
    $stmt->bind_param('is', $user_id, $name);
    $ok = $stmt->execute();
    if (!$ok) { $err = $stmt->error; $stmt->close(); return [false, $err]; }
    $id = $stmt->insert_id;
    $stmt->close();
    return [true, $id];
}

?>
