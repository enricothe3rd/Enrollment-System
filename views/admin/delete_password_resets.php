<?php
// Include the database connection file
require '../../db/db_connection1.php';

if (isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];

    // Prepare the SQL statement with placeholders
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Prepare the deletion query
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE id IN ($placeholders)");

    // Execute the statement
    $stmt->execute($ids);

    // Redirect back or show success message
    header("Location: users.php");
    exit();
} else {
    // No checkboxes were selected
    header("Location: users.php?error=No items selected");
}
?>
