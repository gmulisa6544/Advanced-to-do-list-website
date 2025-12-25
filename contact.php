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
  <title>Contact - Kaayyoo To-Do List</title>
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
          <h2>📞 CONTACT PAGE</h2>
          <h3>🔹 Get in Touch</h3>
          <p>If you have questions, suggestions, or feedback, feel free to contact us.</p>

          <h3>📬 Contact Form</h3>
          <?php if (!empty($_GET['success'])): ?>
            <div class="notice success">Thank you — your message was received.</div>
          <?php elseif (!empty($_GET['error'])): ?>
            <div class="notice error"><?php echo htmlspecialchars($_GET['error']); ?></div>
          <?php endif; ?>

          <form method="post" action="api/contact.php" class="contact-form">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required></textarea>

            <div style="margin-top:12px;">
              <button type="submit" class="btn primary">Submit</button>
            </div>
          </form>

          <h3>📧 Contact Information</h3>
          <p>Email: kayo123@gmail.com</p>
          <p>Phone: +251965444334</p>
          <p>Location: Ethiopia,Jimma University,Agaro Campus</p>

          

          <h3>🔹 Closing Message</h3>
          <p>Your feedback helps us improve. We look forward to hearing from you.</p>
        </div>
      </section>
    </main>
  </div>
  <script src="js/app.js"></script>
</body>
</html>
