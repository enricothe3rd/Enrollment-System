<?php
require 'Instructor.php';

// Instantiate Instructor class
$instructor = new Instructor();

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Call the delete method
    if ($instructor->deleteInstructor($id)) {
        // Redirect to the instructors list with success
        header('Location: read_instructor.php');
        exit();
    } else {
        // Handle the error
        echo "Failed to delete instructor.";
    }
} else {
    // If no ID is provided, redirect back to the instructors list
    header('Location: read_instructor.php');
    exit();
}
?>
