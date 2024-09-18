<?php
require_once 'db_connection3.php';
require_once 'classes/Section.php';

$section = new Section($pdo);

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $sections = $section->getSectionsByCourse($course_id);

    echo '<option value="">Select Section</option>';
    foreach ($sections as $section) {
        echo '<option value="' . $section['id'] . '">' . $section['name'] . '</option>';
    }
}
?>
