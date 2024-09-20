<?php
require 'SubjectAmounts.php';

$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = $_POST['subject_id'];

    if ($subjectId) {
        try {
            $pdo = Database::connect();
            $stmt = $pdo->prepare("UPDATE subjects SET price = 0 WHERE id = :id");
            $stmt->bindParam(':id', $subjectId, PDO::PARAM_INT);
            $stmt->execute();

            echo "Price reset to 0 for subject ID: " . $subjectId;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid subject ID.";
    }
}
?>