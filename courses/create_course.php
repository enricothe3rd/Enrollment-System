<?php
require 'Course.php';

// Initialize Course class
$course = new Course();

// Handle form submission
$course->handleCreateCourseRequest();

// Fetch departments for the dropdown
$departments = $course->getDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Create Course</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Course</h1>
        <form method="post" class="space-y-6">
            <div>
                <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                <input type="text" id="course_name" name="course_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                <select id="department_id" name="department_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Select a department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['id']) ?>">
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Course</button>
        </form>
    </div>
</body>
</html>
