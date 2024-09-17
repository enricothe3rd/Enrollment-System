<?php
require 'Course.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $course = new Course();
    $course->delete($id);
    header('Location: read_courses.php');
    exit();
} else {
    echo 'No course ID provided.';
}
?>
