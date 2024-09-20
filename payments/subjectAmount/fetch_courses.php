<?php
require 'SubjectAmounts.php';


$subjectAmount = new SubjectAmount();
$department_id = $_POST['department_id'] ?? null;

if ($department_id) {
    $courses = $subjectAmount->getCoursesByDepartment($department_id);
    if (!empty($courses)) {
        foreach ($courses as $course) {
            echo "<option value='{$course['id']}'>{$course['course_name']}</option>";
        }
    } else {
        echo "<option value=''>No courses available</option>";
    }
}
?>

