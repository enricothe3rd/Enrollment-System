<?php
require 'SubjectAmounts.php';  // Ensure the path is correct

$subjectAmount = new SubjectAmount();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? null;

    if ($course_id) {
        $sections = $subjectAmount->getSectionsByCourse($course_id);
        foreach ($sections as $section) {
            echo "<option value='{$section['id']}'>{$section['name']}</option>";
        }
    } else {
        echo "<option value=''>Invalid course ID</option>";
    }
} else {
    echo "<option value=''>Invalid request method</option>";
}
?>
