<?php
require 'Course.php';

$course = new Course();
$courses = $course->getCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="tailwind.css" rel="stylesheet">
    <title>View Courses</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Courses</h1>
        
        <!-- Create Course Button -->
        <a href="create_course.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Course</a>
        
        <!-- Courses Table -->
        <table class="table-auto w-full text-left bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Course Name</th>
                    <th class="px-4 py-2">Department</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['course_name']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($course['department_name']) ?></td>
                    <td class="border px-4 py-2">
                        <a href="edit_course.php?id=<?= $course['id'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                        <a href="delete_course.php?id=<?= $course['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
