<?php
// Database connection settings - change as needed for your environment
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP default is empty password for root
$DB_NAME = 'todo_app';

// Create a new mysqli connection
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check for connection errors
if ($mysqli->connect_errno) {
    // Log the internal error and show a generic message
    error_log('Database connection failed: ' . $mysqli->connect_error);
    exit('Database connection error.');
}

// Ensure UTF-8 charset for proper encoding
$mysqli->set_charset('utf8mb4');

?>
