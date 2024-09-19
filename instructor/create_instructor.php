<?php
require 'Instructor.php';

// Create a new PDO connection
$pdo = Database::connect();

// Create a new instance of the Instructor class
$instructor = new Instructor($pdo);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $middleName = $_POST['middle_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $suffix = $_POST['suffix'] ?? '';
    $email = $_POST['email'] ?? '';
    $departmentId = $_POST['department_id'] ?? '';
    $courseId = $_POST['course_id'] ?? '';
    $sectionId = $_POST['section_id'] ?? '';

    try {
        // Create a new instructor
        $instructor->create($firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId);
        $message = 'Instructor created successfully.';
    } catch (Exception $e) {
        $message = 'Error creating instructor: ' . $e->getMessage();
    }
}

// Fetch departments, courses, and sections for the dropdowns
$departments = $instructor->getDepartments();
$courses = $instructor->getCourses();
$sections = $instructor->getSections();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Instructor</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Function to update courses based on department selection
            function updateCourses() {
                const departmentId = document.getElementById('department_id').value;
                const courseSelect = document.getElementById('course_id');
                courseSelect.innerHTML = '<option value="" disabled selected>Select a Course</option>'; // Clear previous options
                const sectionSelect = document.getElementById('section_id');
                sectionSelect.innerHTML = '<option value="" disabled selected>Select a Section</option>'; // Clear previous options

                if (departmentId) {
                    fetch(`fetch_courses.php?department_id=${departmentId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            data.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = course.course_name;
                                courseSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Fetch error:', error));
                }
            }

            // Function to update sections based on course selection
            function updateSections() {
                const courseId = document.getElementById('course_id').value;
                const sectionSelect = document.getElementById('section_id');
                sectionSelect.innerHTML = '<option value="" disabled selected>Select a Section</option>'; // Clear previous options

                if (courseId) {
                    fetch(`fetch_sections.php?course_id=${courseId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            data.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                sectionSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Fetch error:', error));
                }
            }

            // Add event listeners for the dropdowns
            document.getElementById('department_id').addEventListener('change', updateCourses);
            document.getElementById('course_id').addEventListener('change', updateSections);
        });
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Create Instructor</h1>

    <!-- Display success or error message -->
    <?php if (isset($message)): ?>
        <div class="mb-4 p-4 <?= strpos($message, 'Error') !== false ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?> rounded-md">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" id="first_name" name="first_name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div>
            <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" id="last_name" name="last_name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div>
            <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix (optional)</label>
            <input type="text" id="suffix" name="suffix" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
            <select id="department_id" name="department_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="" disabled selected>Select a Department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= htmlspecialchars($department['id']) ?>"><?= htmlspecialchars($department['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
            <select id="course_id" name="course_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="" disabled selected>Select a Course</option>
                <!-- Courses will be populated based on department selection -->
            </select>
        </div>

        <div>
            <label for="section_id" class="block text-sm font-medium text-gray-700">Section</label>
            <select id="section_id" name="section_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="" disabled selected>Select a Section</option>
                <!-- Sections will be populated based on course selection -->
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Create</button>
    </form>
</div>

</body>
</html>
