<?php
require 'SubjectAmounts.php'; // Ensure this connects to your Database class

$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $subjectId = $_GET['subject_id'];

    if ($subjectId) {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("SELECT title, code, units, semester_id AS semester, price FROM subjects WHERE id = :id");
            $stmt->bindParam(':id', $subjectId, PDO::PARAM_INT);
            $stmt->execute();
            $subject = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($subject);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo json_encode([]);
    }
}
?>
