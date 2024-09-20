<?php
require '../db/db_connection3.php'; // Adjust the path as needed


$pdo = Database::connect();

if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];

    $query = "SELECT id, course_name FROM courses WHERE department_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$department_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<option value="">Select Course</option>';
    foreach ($courses as $course) {
        echo '<option value="' . $course['id'] . '">' . $course['course_name'] . '</option>';
    }
}
?>
