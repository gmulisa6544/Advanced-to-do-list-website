<?php
require 'inc/db.php';
require 'inc/auth.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';

    $err = '';
    if (login_user($mysqli, $user, $password, $err)) {
        // Redirect to main page after successful login
        header('Location: index.php');
        exit;
    } else {
        $errors[] = $err;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - To-Do App</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .form { max-width: 420px; margin: 0 auto; }
    .form input, .form button { width: 100%; padding: 8px; margin: 8px 0; }
    .error { color: #b00020; }
  </style>
</head>
<body>
  <div class="container">
    <div class="auth-container">
      <div class="auth-visual">
        <img src="img/todo.jpg" alt="to-do list image">
        <div class="auth-quote">“By failing to prepare, you are preparing to fail.”<br>— Benjamin Franklin</div>
      </div>

      <div class="auth-form">
        <h2>Log in</h2>

        <?php if (!empty($errors)): ?>
          <div class="error">
            <?php foreach ($errors as $e) { echo '<div>' . htmlspecialchars($e) . '</div>'; } ?>
          </div>
        <?php endif; ?>

        <div class="form">
          <form method="post" action="">
            <label>Username or Email</label>
            <input type="text" name="user" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Log in</button>
          </form>
          <div class="note">Don't have an account? <a href="register.php">Register</a></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
