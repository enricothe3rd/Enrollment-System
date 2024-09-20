<?php
require 'SubjectAmounts.php';  // Ensure this connects to your Database class


$pdo = Database::connect();

if (isset($_POST['subject_id']) && isset($_POST['amount'])) {
    $subjectId = $_POST['subject_id'];
    $amount = $_POST['amount'];

    $stmt = $pdo->prepare("UPDATE subjects SET price = :amount WHERE id = :subject_id");
    $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
    $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Amount updated successfully.";
    } else {
        echo "Failed to update amount.";
    }
} else {
    echo "Invalid input.";
}
?>
