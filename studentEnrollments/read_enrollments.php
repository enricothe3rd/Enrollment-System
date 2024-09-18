<?php
require 'Enrollment.php';
$enrollment = new Enrollment();
$enrollments = $enrollment->getEnrollments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="w-full bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-6">Enrollments List</h2>
            <a href="create_enrollment.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Enrollment</a>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Student Number</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Course</th>
                        <th class="px-4 py-2">Section</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= $enrollment['student_number']; ?></td>
                            <td class="border px-4 py-2"><?= $enrollment['firstname'] . ' ' . $enrollment['lastname']; ?></td>
                            <td class="border px-4 py-2"><?= $enrollment['course_name']; ?></td>
                            <td class="border px-4 py-2"><?= $enrollment['section_name']; ?></td>
                            <td class="border px-4 py-2">
                                <a href="edit_enrollment.php?id=<?= $enrollment['id']; ?>" class="text-blue-500">Edit</a> |
                                <a href="delete_enrollment.php?id=<?= $enrollment['id']; ?>" class="text-red-500">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
