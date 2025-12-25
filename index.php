<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>To-Do List App</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php
  require 'inc/db.php';
  require 'inc/auth.php';
  if (session_status() === PHP_SESSION_NONE) { session_start(); }
  ?>

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
      <?php if (!empty($_SESSION['user_id'])): ?>
        <div class="app-grid">
          <aside class="sidebar">
            <div class="sidebar-top">
              <div class="app-name">My ToDo</div>
            </div>
            <nav class="projects">
              <div class="projects-heading">Projects</div>
              <ul id="projectList">
                <li class="project-item" data-id="">Inbox</li>
              </ul>
              <div class="category-form small">
                <input type="text" id="newCategoryNameSidebar" placeholder="+ New project">
                <button id="addCategoryBtnSidebar">Add</button>
              </div>
            </nav>
          </aside>

          <div class="main-area">
            <div class="topbar">
              <p>Welcome — manage your tasks below.</p>
            </div>

            <section id="task-form">
              <div class="composer">
                <form id="addTaskForm" autocomplete="off">
                  <input type="text" name="title" class="composer-input" placeholder="Add task..." required>
                  <div class="composer-controls" aria-hidden="true">
                    <input type="text" name="description" placeholder="Optional description" class="composer-desc">
                    <select name="priority" class="composer-priority">
                      <option value="Low">Low</option>
                      <option value="Medium" selected>Medium</option>
                      <option value="High">High</option>
                    </select>
                    <input type="date" name="due_date">
                    <select name="category_id" id="categorySelect">
                      <option value="">No project</option>
                    </select>
                    <button type="submit">Add</button>
                  </div>
                </form>
              </div>

              <div class="category-form" style="margin-top:10px;">
                <input type="text" id="newCategoryName" placeholder="New project name">
                <button id="addCategoryBtn">Add Project</button>
              </div>

              <div id="editTaskForm">
                <h4>Edit Task</h4>
                <form id="editForm">
                  <input type="hidden" name="id">
                  <label>Title</label>
                  <input type="text" name="title" required>
                  <label>Description</label>
                  <input type="text" name="description">
                  <div class="controls">
                    <div>
                      <label>Priority</label>
                      <select name="priority">
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                      </select>
                    </div>
                    <div>
                      <label>Due date</label>
                      <input type="date" name="due_date">
                    </div>
                    <div>
                      <label>Project</label>
                      <select name="category_id" id="editCategorySelect">
                        <option value="">No project</option>
                      </select>
                    </div>
                  </div>
                  <div style="margin-top:8px;">
                    <button type="submit">Save Changes</button>
                    <button type="button" id="cancelEdit">Cancel</button>
                  </div>
                </form>
              </div>
            </section>

            <section id="tasks">
              <div class="tasks-header">
                <h2 id="projectTitle">Inbox</h2>
                <div class="tasks-controls">
                  <input type="search" id="searchInput" placeholder="Search tasks...">
                  <select id="filterPriority">
                    <option value="">All</option>
                    <option>High</option>
                    <option>Medium</option>
                    <option>Low</option>
                  </select>
                  <select id="filterCompleted">
                    <option value="">All</option>
                    <option value="0">Active</option>
                    <option value="1">Completed</option>
                  </select>
                  <button id="backupBtn">Server Backup</button>
                </div>
              </div>

              <div id="tasksList">Loading...</div>
            </section>
          </div>
        </div>

      <?php else: ?>
        <section class="hero">
          <div class="hero-inner">
            <h2>Welcome!</h2>
            <p class="hero-sub">Please <a class="hero-link" href="login.php">log in</a> or <a class="hero-link" href="register.php">register</a> to manage tasks.</p>
            <div class="hero-actions">
              <a class="btn primary" href="register.php">Get Started</a>
              <a class="btn ghost" href="login.php">Sign In</a>
            </div>
            <div class="hero-features">
              <div class="feature">Fast tasks</div>
              <div class="feature">Organize projects</div>
              <div class="feature">Set priorities & due dates</div>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <!-- About section removed from homepage; use About page instead -->
    </main>
  </div>
  <script src="js/app.js"></script>
</body>
</html>
