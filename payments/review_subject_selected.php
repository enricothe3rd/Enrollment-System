<?php
// Start the session
session_start();

// Include your database connection
require '../db/db_connection3.php';

try {
    // Create a new PDO connection
    $db = Database::connect(); // Assuming Database::connect() returns a PDO instance

    // Prepare the SQL statement
    $stmt = $db->prepare("SELECT * FROM subject_enrollments WHERE student_number = :student_number");

    // Bind the session student number to the SQL statement
    $stmt->bindParam(':student_number', $_SESSION['student_number'], PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Fetch the results
    $subjectEnrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-5">
    <h1 class="text-2xl font-bold mb-4">Subject Enrollments</h1>

    <?php if (isset($error)): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($subjectEnrollments)): ?>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Student Number</th>
                    <th class="py-2 px-4">Section ID</th>
                    <th class="py-2 px-4">Department ID</th>
                    <th class="py-2 px-4">Course ID</th>
                    <th class="py-2 px-4">Subject ID</th>
                    <th class="py-2 px-4">Schedule ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjectEnrollments as $enrollment): ?>
                    <tr class="hover:bg-gray-200">
                        <td class="border px-4 py-2"><?= $enrollment['id'] ?></td>
                        <td class="border px-4 py-2"><?= $enrollment['student_number'] ?></td>
                        <td class="border px-4 py-2"><?= $enrollment['section_id'] ?></td>
                        <td class="border px-4 py-2"><?= $enrollment['department_id'] ?></td>
                        <td class="border px-4 py-2"><?= $enrollment['course_id'] ?></td>
                        <td class="border px-4 py-2"><?= $enrollment['subject_id'] ?></td>
                        <td class="border px-4 py-2"><?= $enrollment['schedule_id'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="bg-yellow-300 p-3 rounded">
            No subject enrollments found for this student.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
