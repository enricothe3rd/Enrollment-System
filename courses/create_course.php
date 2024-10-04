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
                <label for="course_name" class="block text-sm font-medium text-red-700">Course Name</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-book px-3 text-red-500"></i> <!-- Course icon -->
                    <input type="text" id="course_name" name="course_name" placeholder="Enter Course Name" required 
                           class="w-full h-10 px-3 py-2 focus:outline-none ">
                </div>
            </div>

            <div>
                <label for="department_id" class="block text-sm font-medium text-red-700">Department</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-building px-3 text-red-500"></i> <!-- Department icon -->
                    <select id="department_id" name="department_id" required 
                            class="w-full h-12 px-3 bg-red-50 text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md transition duration-200">
                        <option value="" disabled selected>Select a department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['id']) ?>">
                                <?= htmlspecialchars($dept['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded transition duration-200">
                Create Course
            </button>
        </form>
    </div>
</body>
</html>

<script>
    function goBack() {
        window.history.back(); // Navigates to the previous page
    }
</script>