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
  <title>Home - Kaayyoo To-Do List</title>
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
      <section class="home-section">
        <div class="home-inner">
          <h2>🏠 HOME PAGE</h2>
          <h3>🔹 Hero Section</h3>
          <h1>Kaayyoo To-Do List</h1>
          <p class="tagline">Organize your tasks. Manage your time. Achieve more.</p>
          <p>A simple and powerful task management system designed to help you plan, track, and complete your daily tasks efficiently.</p>
          <div class="hero-actions">
            <a class="btn primary" href="register.php">Get Started</a>
            <a class="btn" href="index.php#task-form">Create Task</a>
          </div>

          <h3>🔹 Why Choose This App?</h3>
          <p>Managing tasks can be stressful and confusing. This application helps you stay organized, meet deadlines, and improve productivity by keeping everything in one place.</p>

          <h3>🔹 Key Features</h3>
          <ul>
            <li>Create, edit, and delete tasks</li>
            <li>Set deadlines and priorities</li>
            <li>Mark tasks as completed</li>
            <li>Secure user accounts</li>
            <li>Works on all devices</li>
          </ul>

          <h3>🔹 How It Works</h3>
          <ol>
            <li>Sign up or log in</li>
            <li>Add your tasks</li>
            <li>Track progress and complete tasks</li>
          </ol>

          <h3>🔹 Call to Action</h3>
          <p>Start organizing your tasks today and take control of your time.</p>
          <a class="btn primary" href="register.php">Get Started Now</a>
        </div>
      </section>
    </main>
  </div>
  <script src="js/app.js"></script>
</body>
</html>
