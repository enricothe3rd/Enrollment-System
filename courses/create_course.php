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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Create Course</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-lg">
        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        <h1 class="text-3xl font-bold text-red-800 mb-6">Create Course</h1>
        <form method="post" class="space-y-6">
            <div>
                <label for="course_name" class="block text-sm font-medium text-red-700 flex items-center">
                    <i class="fas fa-book mr-2 text-red-500 text-lg"></i> <!-- Bigger Book icon -->
                    Course Name
                </label>
                <input type="text" id="course_name" name="course_name" required 
                       class="mt-1 block w-full h-10 border border-red-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200">
            </div>

            <div>
                <label for="department_id" class="block text-sm font-medium text-red-700 flex items-center">
                    <i class="fas fa-building mr-2 text-red-500 text-lg"></i> <!-- Bigger Building icon -->
                    Department
                </label>
                <select id="department_id" name="department_id" required 
                        class="mt-1 block w-full h-12 bg-red-50 text-red-800 border border-red-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200">
                    <option value="" disabled selected>Select a department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept['id']) ?>">
                            <?= htmlspecialchars($dept['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded transition duration-200">Create Course</button>
        </form>
    </div>
</body>
</html>

<script>
    function goBack() {
        window.history.back(); // Navigates to the previous page
    }
</script>
