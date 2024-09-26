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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Edit Course</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        <h1 class="text-2xl font-bold text-red-800 mb-6 flex items-center">
            <i class="fas fa-edit mr-2 text-red-500 text-lg"></i> <!-- Edit icon -->
            Edit Course
        </h1>
        <form method="POST" action="update_course.php?id=<?= htmlspecialchars($id) ?>">
            <div class="mb-6">
                <label class="block text-red-700 text-sm font-bold mb-2 flex items-center">
                    <i class="fas fa-book mr-2 text-red-500 text-lg"></i> <!-- Book icon -->
                    Course Name
                </label>
                <input type="text" name="course_name" value="<?= htmlspecialchars($currentCourse['course_name']) ?>" 
                       class="shadow border rounded border-red-300 w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500" required>
            </div>
            <div class="mb-6">
                <label class="block text-red-700 text-sm font-bold mb-2 flex items-center">
                    <i class="fas fa-building mr-2 text-red-500 text-lg"></i> <!-- Building icon -->
                    Department
                </label>
                <select name="department_id" 
                        class="shadow border rounded border-red-300 w-full py-2 px-3 text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['id']) ?>" <?= $dept['id'] == $currentCourse['department_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded transition duration-200 ease-in-out">Update</button>
        </form>
    </div>
</body>
</html>

<script>
    function goBack() {
        window.history.back(); // Navigates to the previous page
    }
</script>