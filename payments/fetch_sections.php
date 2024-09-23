<?php
session_start();
require '../db/db_connection3.php'; // Adjust the filename as needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseId = $_POST['course_id'];

    if (empty($courseId)) {
        echo json_encode(['error' => 'Course ID is not set.']);
        exit;
    }

    try {
        $db = Database::connect();
        // Fetch sections corresponding to the selected course
        $stmt = $db->prepare("SELECT id, name FROM sections WHERE course_id = :course_id");
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($sections);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching sections: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
