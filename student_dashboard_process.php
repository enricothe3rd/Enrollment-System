<?php
session_start();
require 'session_timeout.php'; // Include session timeout mechanism
require 'db/db_connection1.php'; // Include your database connection file

// Check if user is logged in and their role is either 'student' or 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'student' && $_SESSION['user_role'] !== 'admin')) {
    header("Location: index.php"); // Redirect to login page
    exit(); // Stop further execution
}



// Retrieve the student ID from session
$student_id = $_SESSION['user_id'];

try {
    // Prepare and execute the SQL statement to check the status of enrollment
    $stmt = $pdo->prepare("SELECT statusofenrollment FROM enrollment WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $status = $stmt->fetchColumn();

    if ($status === false) {
        // No record found for this student_id, redirect to enrollment form
        header("Location: http://localhost/Enrollment-System/enrollment_form.php");
        exit(); // Stop further execution
    }

    // Redirect based on the status
    if ($status === 'verifying') {
        header("Location: http://localhost/Enrollment-System/student_dashboard_verifying.php");
    } elseif ($status === 'enrolled') {
        header("Location: http://localhost/Enrollment-System/student_dashboard.php");
    } elseif ($status === 'incomplete') {
        header("Location: http://localhost/Enrollment-System/select_course.php");
    }  else {
        header("Location: http://localhost/Enrollment-System/student_dashboard_rejected.php");
    }
    exit(); // Stop further execution

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit(); // Stop further execution
}
?>



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

