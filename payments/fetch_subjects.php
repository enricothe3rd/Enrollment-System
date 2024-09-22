<?php
require '../db/db_connection3.php'; // Adjust the filename as needed

if (isset($_GET['section_id'])) {
    $sectionId = $_GET['section_id'];

    try {
        $db = Database::connect();

        // Prepare and execute the query to fetch subjects by section
        $stmt = $db->prepare("SELECT id, code, title, units, semester_id FROM subjects WHERE section_id = :section_id");
        $stmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch all subjects
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return subjects as JSON
        echo json_encode($subjects);
    } catch (PDOException $e) {
        // Return a JSON error message
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
} else {
    // Return a JSON error message if section ID is not provided
    echo json_encode(['error' => 'No section ID provided']);
}
?>
