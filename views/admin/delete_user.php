<?php
// Include database connection
require '../../db/db_connection1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];

    // Prepare the DELETE query using placeholders
    $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));

    // Delete selected users
    $stmt = $pdo->prepare("DELETE FROM users WHERE id IN ($placeholders)");
    $stmt->execute($delete_ids);

    // Redirect to user management page after deletion
    header('Location: users.php');
    exit();
}
?>
