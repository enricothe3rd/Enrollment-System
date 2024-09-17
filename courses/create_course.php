<?php
require 'Course.php';

// Initialize Course class
$course = new Course();
$departments = $course->getDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="tailwind.css" rel="stylesheet">
    <title>Create Course</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Create Course</h1>
        <form action="create_course_action.php" method="post">
            <div class="mb-4">
                <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                <input type="text" id="course_name" name="course_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                <select id="department_id" name="department_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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
