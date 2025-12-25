<?php
require 'inc/db.php';
require 'inc/auth.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    
  // Temporary debug logging (do not leave enabled in production)
  $dbgPath = __DIR__ . '/inc/register_debug.log';
  $log = sprintf("[%s] POST received: username=%s email=%s password_len=%d\n", date('c'), htmlspecialchars($username), htmlspecialchars($email), strlen($password));
  // Pre-check for existing user (helpful to see why "taken" occurs)
  $stmt_dbg = $mysqli->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
  if ($stmt_dbg) {
    $stmt_dbg->bind_param('ss', $username, $email);
    $stmt_dbg->execute();
    $stmt_dbg->store_result();
    $count = $stmt_dbg->num_rows;
    $log .= "Pre-check found rows: " . $count . "\n";
    $stmt_dbg->close();
  } else {
    $log .= "Pre-check prepare failed: " . $mysqli->error . "\n";
  }
  // Append to debug file
  @file_put_contents($dbgPath, $log, FILE_APPEND);

    if ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $err = '';
        if (register_user($mysqli, $username, $email, $password, $err)) {
            $success = 'Registration successful. You can now log in.';
        } else {
            $errors[] = $err;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - To-Do App</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .form { max-width: 420px; margin: 0 auto; }
    .form input, .form button { width: 100%; padding: 8px; margin: 8px 0; }
    .error { color: #b00020; }
    .success { color: #006600; }
  </style>
</head>
<body>
  <div class="container form">
    <h2>Create an account</h2>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach ($errors as $e) { echo '<div>' . htmlspecialchars($e) . '</div>'; } ?>
      </div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <label>Username</label>
      <input type="text" name="username" required>

      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <label>Confirm Password</label>
      <input type="password" name="password2" required>

      <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a></p>
  </div>
</body>
</html>
