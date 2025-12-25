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
  <title>About - Kaayyoo To-Do List</title>
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
          <h2>📄 About Us</h2>
          <h3>🧠 About the Project</h3>
          <p>The Advanced To-Do Management System is a web-based application designed to help users organize their daily tasks efficiently and improve productivity. The platform allows users to create, update, manage, and track tasks in a simple and user-friendly environment.</p>

          <p>This project focuses on:</p>
          <ul>
            <li>Reducing task overload</li>
            <li>Improving time management</li>
            <li>Helping users stay focused and organized</li>
          </ul>

          <h3>🎯 Purpose &amp; Vision</h3>
          <p>The main goal of this project is to provide a simple, fast, and reliable task management solution for students, professionals, and individuals who want to manage their time better.</p>
          <p>Our vision is to build a productivity tool that is:</p>
          <ul>
            <li>Easy to use</li>
            <li>Secure</li>
            <li>Accessible on all devices</li>
          </ul>

          <h3>⚙️ Key Features</h3>
          <ul>
            <li>Create, edit, and delete tasks</li>
            <li>Set deadlines and priorities</li>
            <li>Mark tasks as completed</li>
            <li>Organize tasks into categories</li>
            <li>Secure user authentication</li>
            <li>Responsive design for mobile and desktop</li>
          </ul>

          <h3>👨‍💻 About the Developer</h3>
          <p>Hi,This is a Group-1  Website built by a Computer Science student specializing in Artificial Intelligence. I built this project to strengthen my skills in web development and backend programming, and to gain hands-on experience in building real-world applications.</p>
          <p>This project reflects my interest in:</p>
          <ul>
            <li>Web Development</li>
            <li>Software Engineering</li>
            <li>Problem-solving through technology</li>
          </ul>

          <h3>🛠 Technologies Used</h3>
          <p>HTML – Structure of the website<br>
          CSS – Styling and layout<br>
          JavaScript – Client-side interactivity<br>
          PHP – Server-side logic<br>
          MySQL – Database management</p>

          <h3>🚀 Future Improvements</h3>
          <p>Planned features include:</p>
          <ul>
            <li>Task reminders and notifications</li>
            <li>Dark mode</li>
            <li>Analytics and productivity reports</li>
            <li>Cloud synchronization</li>
          </ul>

          <h3>📌 Closing Statement</h3>
          <p>This project is a step toward building practical and scalable software solutions. Feedback and suggestions are always welcome to help improve the system.</p>

          <p>If you want, I can also:</p>
          <ul>
            <li>🔹 Simplify this (student-level)</li>
            <li>🔹 Make it very professional (job-portfolio style)</li>
            <li>🔹 Convert it into HTML code</li>
            <li>🔹 Rewrite it to sound like a startup product</li>
          </ul>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
