📝 Advanced To-Do List Website

An advanced, full-stack To-Do List web application built using HTML, CSS, and JavaScript for the frontend and PHP with MySQL for the backend.
The application allows users to manage tasks efficiently with authentication, projects, priorities, and filters.

 Features
 User Authentication

User registration and login

Session-based authentication

Secure logout

 Task Management

Add, edit, delete tasks

Mark tasks as completed

Set task priorities (Low, Medium, High)

Add optional descriptions and due dates

 Projects (Categories)

Create and manage projects

Assign tasks to projects

Sidebar project navigation (Inbox + custom projects)

 Filters & Search

Search tasks by title or description

Filter by:

Priority

Completion status

Project

 Data Backup

Export tasks to JSON

Import tasks from JSON backups

 Security

Frontend HTML escaping to prevent XSS

Backend input validation

Prepared SQL statements to prevent SQL Injection

 Technologies Used
Frontend

HTML5 – Structure

CSS3 – Styling and layout

JavaScript (Vanilla) – Dynamic UI, API calls, DOM manipulation

Backend

PHP – Server-side logic

MySQL – Database

PDO / MySQLi – Prepared statements

Tools

XAMPP – Local server (Apache & MySQL)

Git & GitHub – Version control

 Project Structure
my-website/
│
├── api/
│   ├── tasks.php
│   └── import.php
│
├── inc/
│   ├── db.php
│   ├── auth.php
│   └── register_debug.log
│
├── css/
│   └── style.css
│
├── js/
│   └── app.js
│
├── backups/
│   └── todo-backup-*.json
│
├── index.php
├── login.php
├── register.php
├── logout.php
├── README.md
└── .gitignore

 How It Works (Architecture)

Frontend

JavaScript sends requests using fetch() to PHP APIs

UI is updated dynamically without page reload

Tasks are rendered dynamically using DOM manipulation

Backend

PHP receives requests from frontend

Input is validated and sanitized

Database operations are done using prepared statements

Responses are returned as JSON

Database

Stores users, tasks, and categories

Each task is linked to a user and optional project

 How to Run Locally

Install XAMPP

Move the project to:

C:\xampp\htdocs\my-website


Start Apache and MySQL

Create a MySQL database and import required tables

Update database credentials in:

inc/db.php


Open in browser:

http://localhost/my-website

 What I Learned

Full-stack web development workflow

Secure authentication and session handling

REST-like API communication

Preventing XSS and SQL Injection

Dynamic UI rendering using JavaScript

Using Git and GitHub for version control


 Author

Geleta Mulisa
Computer Science Student 
GitHub: gmulisa6544

📜 License

This project is for educational purposes.
