<?php
require 'SubjectAmounts.php';  // Ensure this connects to your Database class

if (isset($_POST['subject_id']) && isset($_POST['amount'])) {
    $subjectId = $_POST['subject_id'];
    $amount = $_POST['amount'];

    try {
        // Get the PDO instance from your Database class
        $pdo = Database::connect();
        
        // Prepare the SQL statement to update the price in the subjects table
        $stmt = $pdo->prepare("UPDATE subjects SET price = :amount WHERE id = :subject_id");
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo "Amount updated successfully.";
        } else {
            echo "Failed to update amount.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid input.";
}
?>
