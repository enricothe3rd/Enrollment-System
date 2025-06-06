<?php
session_start();
require 'session_timeout.php'; // Include session timeout mechanism
require 'db/db_connection1.php'; // Include your database connection file

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'student' && $_SESSION['user_role'] !== 'admin')) {
    header("Location: index.php"); // Redirect to login page
    exit(); // Stop further execution
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard Verifying</title>
</head>
<body>
    <h2>Welcome, Student!</h2>
    <!-- Dashboard content goes here -->
    <p>You're application is for verifying .</p>
    <a href="logout.php">Logout</a>
</body>
</html>

<!-- CREATE TABLE enrollment (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50),
    lastname VARCHAR(50) NOT NULL,
    suffix VARCHAR(10),
    status ENUM('pending','confirmed','rejected','New Student','Old Student','Regular','Irregular','Transferee') DEFAULT 'New Student',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    year VARCHAR(10),
    course VARCHAR(50),
    major VARCHAR(50),
    sex VARCHAR(10),
    dob DATE,
    address TEXT,
    email VARCHAR(100),
    contact_no VARCHAR(20),
    statusofenrollment ENUM('pending', 'verifying', 'enrolled', 'rejected') DEFAULT 'pending',
    CONSTRAINT fk_student_id FOREIGN KEY (student_id) REFERENCES users(id)

); -->

