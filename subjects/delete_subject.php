<?php
require 'Subject.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $subject = new Subject();

    // Attempt to delete the subject
    if ($subject->delete($id)) {
        // Redirect on successful deletion
        header('Location: read_subjects.php?message=Subject deleted successfully.');
        exit();
    } else {
        echo 'Error: Unable to delete the subject. Please try again.';
    }
} else {
    echo 'Error: No subject ID provided.';
}
?>
