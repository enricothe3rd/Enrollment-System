<?php
require '../db/db_connection3.php'; // Adjust the filename as needed

if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];

    try {
        $db = Database::connect();

        // Prepare and execute the query to fetch sections by course
        $stmt = $db->prepare("SELECT id, name FROM sections WHERE course_id = :course_id");
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch all sections
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return sections as JSON
        echo json_encode($sections);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['error' => 'No course ID provided']);
}
?>
