<?php
require 'inc/db.php';
require 'inc/auth.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services - Kaayyoo To-Do List</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <header>
      <div class="header-left">
        <h1>Kaayyoo To-Do List</h1>
        <nav class="main-nav">
          <a href="home.php">Home</a>
          <a href="about.php">About</a>
          <a href="service.php">Service</a>
          <a href="contact.php">Contact</a>
        </nav>
      </div>
      <a href="logout.php" class="logout-btn">Log out</a>
    </header>

    <main>
      <section class="about-section">
        <div class="about-inner">
          <h2>🛠 SERVICES PAGE</h2>
          <h3>🔹 Our Services</h3>
          <p>We provide a complete task management solution designed for simplicity and efficiency.</p>

          <h3>📝 Task Management</h3>
          <ul>
            <li>Add new tasks</li>
            <li>Edit or delete tasks</li>
            <li>Organize tasks into categories</li>
          </ul>

          <h3>⏰ Deadline &amp; Priority Control</h3>
          <ul>
            <li>Set due dates</li>
            <li>Assign task priorities (High, Medium, Low)</li>
            <li>Sort tasks by importance</li>
          </ul>

          <h3>🔐 Secure User System</h3>
          <ul>
            <li>User registration and login</li>
            <li>Personal task storage</li>
            <li>Data protected using backend validation</li>
          </ul>

          <h3>📊 Productivity Tracking</h3>
          <ul>
            <li>Track completed and pending tasks</li>
            <li>Monitor daily progress</li>
            <li>Improve time management habits</li>
          </ul>

          <h3>👥 Who Can Use This?</h3>
          <ul>
            <li>Students</li>
            <li>Professionals</li>
            <li>Anyone who wants better productivity</li>
          </ul>
        </div>
      </section>
    </main>
  </div>
  <script src="js/app.js"></script>
</body>
</html>
