<?php
require '../db/db_connection3.php'; // Adjust the path as needed

$pdo = Database::connect();

$query = "SELECT id, name FROM departments";
$stmt = $pdo->query($query);
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($departments as $department) {
    echo '<option value="' . $department['id'] . '">' . $department['name'] . '</option>';
}
?>
