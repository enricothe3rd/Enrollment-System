<?php
// Include the Enrollment class and initialize the object
require 'Enrollment.php';
$enrollment = new Enrollment();

// Check if the 'id' parameter is passed via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Call the delete method from the Enrollment class
    if ($enrollment->deleteEnrollment($id)) {
        // Redirect to the enrollment listing page after deletion
        header('Location: read_enrollments.php?message=Enrollment deleted successfully');
        exit();
    } else {
        // Display an error message if the deletion failed
        echo 'Failed to delete enrollment.';
    }
} else {
    // If no 'id' is provided, redirect to the enrollment listing page
    header('Location: read_enrollments.php');
    exit();
}
