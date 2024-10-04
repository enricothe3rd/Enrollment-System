
<?php

require 'Course.php';

$course = new Course();
$id = $_GET['id'];

if ($course->deleteCourse($id)) {
    header('Location: read_courses.php');
    exit;
} else {
    echo "Error deleting course";
}
?>
