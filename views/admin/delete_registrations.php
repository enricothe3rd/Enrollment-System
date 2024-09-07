<?php
// Include database connection
require '../../db/db_connection1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];

    // Prepare the DELETE query using placeholders
    $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));

    // Delete selected registrations
    $stmt = $pdo->prepare("DELETE FROM user_registration WHERE id IN ($placeholders)");
    $stmt->execute($delete_ids);

    // Redirect to user registration page after deletion
    header('Location: user_registration.php');
    exit();
}
?>
