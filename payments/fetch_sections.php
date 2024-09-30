<?php
session_start();
require '../db/db_connection3.php'; // Adjust the filename as needed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseId = $_POST['course_id'];
    $schoolYearId = $_POST['school_year_id'] ?? null; // Get school year ID
    $semesterId = $_POST['semester_id'] ?? null; // Get semester ID

    if (empty($courseId) || empty($schoolYearId) || empty($semesterId)) {
        echo json_encode(['error' => 'Required parameters are missing.']);
        exit;
    }

    try {
        $db = Database::connect();
        
        // Step 1: Fetch unique sections for the selected course and school year/semester
        $stmt = $db->prepare("
            SELECT sec.id, sec.name 
            FROM sections sec 
            JOIN subjects s ON s.section_id = sec.id 
            WHERE sec.course_id = :course_id
            AND s.school_year_id = :school_year_id 
            AND s.semester_id = :semester_id
            GROUP BY sec.id, sec.name
        ");
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':school_year_id', $schoolYearId, PDO::PARAM_INT);
        $stmt->bindParam(':semester_id', $semesterId, PDO::PARAM_INT);
        $stmt->execute();

        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Step 2: Fetch subjects for the retrieved sections
        $subjectData = [];
        if (!empty($sections)) {
            $sectionIds = implode(',', array_column($sections, 'id')); // Create a list of section IDs
            $stmt = $db->prepare("
                SELECT s.id as subject_id, s.title, s.code, s.units, s.section_id
                FROM subjects s
                WHERE s.section_id IN ($sectionIds) 
                AND s.school_year_id = :school_year_id 
                AND s.semester_id = :semester_id
            ");
            $stmt->bindParam(':school_year_id', $schoolYearId, PDO::PARAM_INT);
            $stmt->bindParam(':semester_id', $semesterId, PDO::PARAM_INT);
            $stmt->execute();
            $subjectData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Step 3: Combine sections and their subjects for output
        foreach ($sections as &$section) {
            $section['subjects'] = array_filter($subjectData, function($subject) use ($section) {
                return $subject['section_id'] == $section['id'];
            });
        }

        echo json_encode($sections);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching sections: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
