<?php
session_start(); // Start the session

// Include the Database class
require_once '../db/db_connection3.php';

$student_number = $_SESSION['student_number'] ?? null;

$pdo = Database::connect();
$grades = [];

if ($student_number) {
    // Fetch the grades and join with the subjects table to get subject code
    $sql = "SELECT subjects.code AS subject_code, grades.grade, grades.prelim, grades.midterm, grades.finals, 
                   grades.term, grades.created_at 
            FROM grades 
            JOIN subjects ON grades.subject_id = subjects.id 
            WHERE grades.student_number = :student_number";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">My Grades</h1>

    <?php if ($grades): ?>
        <table class="min-w-full bg-white shadow-md rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4">Subject Code</th>
                    <th class="py-2 px-4">Prelim</th>
                    <th class="py-2 px-4">Midterm</th>
                    <th class="py-2 px-4">Finals</th>
                    <th class="py-2 px-4">Final Grade</th>
                    <th class="py-2 px-4">Term</th>
                    <th class="py-2 px-4">Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['subject_code']); ?></td>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['prelim']); ?></td>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['midterm']); ?></td>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['finals']); ?></td>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['grade']); ?></td>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['term']); ?></td>
                        <td class="border-t py-2 px-4"><?php echo htmlspecialchars($grade['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No grades found for this student.</p>
    <?php endif; ?>
</body>
</html>
