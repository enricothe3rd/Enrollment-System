<?php
require '../db/db_connection3.php'; // Adjust the path as needed

$pdo = Database::connect();
$query = "SELECT id, semester_name FROM semesters";
$stmt = $pdo->prepare($query);
$stmt->execute();
$semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($semesters as $semester) {
    echo "<option value=\"{$semester['id']}\">{$semester['semester_name']}</option>";
}