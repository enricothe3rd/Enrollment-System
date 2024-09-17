<?php
require 'Course.php';

$course = new Course();
$courses = $course->getCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <title>View Courses</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto mt-10 p-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Courses</h1>
        
        <!-- Create Course Button -->
        <a href="create_course.php" class="bg-blue-600 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded mb-6 inline-block transition duration-200">Create Course</a>
        
        <!-- Courses Table -->
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Course Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td class="border-t px-6 py-4"><?= htmlspecialchars($course['course_name']) ?></td>
                    <td class="border-t px-6 py-4"><?= htmlspecialchars($course['department_name']) ?></td>
                    <td class="border-t px-6 py-4 flex space-x-2">
                        <a href="update_course.php?id=<?= $course['id'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                        <a href="delete_course.php?id=<?= $course['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
