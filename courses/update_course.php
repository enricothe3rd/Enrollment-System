<?php
require 'Course.php';

$course = new Course();
$id = $_GET['id'];
$currentCourse = $course->getCourseById($id);
$departments = $course->getDepartments(); // Fetch departments

// Handle the update request
$course->handleUpdateCourseRequest($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="tailwind.css" rel="stylesheet">
    <title>Edit Course</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Edit Course</h1>
        <form method="POST" action="update_course.php?id=<?= htmlspecialchars($id) ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="course_name">Course Name</label>
                <input type="text" name="course_name" value="<?= htmlspecialchars($currentCourse['course_name']) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="department_id">Department</label>
                <select name="department_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['id']) ?>" <?= $dept['id'] == $currentCourse['department_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>
</body>
</html>
