<?php
session_start();
require '../db/db_connection3.php'; // Adjust the filename as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sectionId = $_POST['section_id'] ?? null;

    if (!$sectionId) {
        echo json_encode(['error' => 'Section ID is required.']);
        exit;
    }

    try {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM subjects WHERE section_id = :section_id");
        $stmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
        $stmt->execute();
        
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($subjects);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching subjects: ' . $e->getMessage()]);
    }
}

        
        // Prepare the SQL statement
        $stmt = $db->prepare("
            SELECT day_of_week, start_time, end_time, room 
            FROM schedules 
            WHERE subject_id IN ($placeholders)
        ");
        
?>
