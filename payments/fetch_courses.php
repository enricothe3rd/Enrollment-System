<?php
require '../db/db_connection3.php'; // Adjust the filename as needed

if (isset($_GET['department_id'])) {
    $departmentId = $_GET['department_id'];

    try {
        // Create a new PDO instance
        $db = Database::connect();

        // Prepare and execute the query to fetch courses by department
        $stmt = $db->prepare("SELECT id, course_name FROM courses WHERE department_id = :department_id");
        $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch all courses
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return courses as JSON
        echo json_encode($courses);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['error' => 'No department ID provided']);
}
?>
