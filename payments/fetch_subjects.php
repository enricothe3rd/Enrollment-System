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
        
        // Fetch the subjects for the given section
        $stmt = $db->prepare("SELECT * FROM subjects WHERE section_id = :section_id");
        $stmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
        $stmt->execute();
        
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If subjects were found, fetch the schedule for each subject
        if ($subjects) {
            $subjectIds = array_column($subjects, 'id');
            $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));

            // Prepare the SQL statement for fetching schedules
            $scheduleStmt = $db->prepare("
                SELECT subject_id, day_of_week, start_time, end_time, room 
                FROM schedules 
                WHERE subject_id IN ($placeholders)
            ");
            
            // Execute the schedule query with the list of subject IDs
            $scheduleStmt->execute($subjectIds);
            $schedules = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

            // Attach schedules to the corresponding subjects
            foreach ($subjects as &$subject) {
                $subject['schedules'] = array_filter($schedules, function ($schedule) use ($subject) {
                    return $schedule['subject_id'] === $subject['id'];
                });
            }
        }

        // Return the subjects with their schedules as a JSON response
        echo json_encode($subjects);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching subjects: ' . $e->getMessage()]);
    }
}
?>
