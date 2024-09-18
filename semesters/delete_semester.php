<?php
require 'Semester.php';

$semester = new Semester();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($semester->deleteSemester($id)) {
        header('Location: read_semesters.php');
        exit();
    } else {
        echo "Failed to delete semester.";
    }
} else {
    header('Location: read_semesters.php');
    exit();
}
?>
