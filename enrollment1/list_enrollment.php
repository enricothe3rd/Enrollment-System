<?php
require_once 'db_connection3.php';

// Fetch enrollments
$stmt = $pdo->query("SELECT * FROM enrollments");
$enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-6">Enrollments List</h1>
        
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Student Number</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Course</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($enrollment['student_number']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($enrollment['firstname'] . ' ' . $enrollment['lastname']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($enrollment['course_id']) ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="views/edit_enrollment.php?id=<?= $enrollment['id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
