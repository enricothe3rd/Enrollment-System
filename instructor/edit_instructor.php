<?php
require 'Instructor.php'; // Make sure this path is correct

// Get the PDO connection
$pdo = Database::connect();

// Create an instance of Instructor
$instructor = new Instructor($pdo);

$id = $_GET['id'];
$instructorData = $instructor->read($id);

// Fetch courses and sections for the dropdowns
$courses = $instructor->getCourses(); // Fetch all courses
$sections = $instructor->getSections(); // Fetch all sections
$departments = $instructor->getDepartments(); // Fetch all departments

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name']; // New field
    $lastName = $_POST['last_name'];
    $suffix = $_POST['suffix']; // New field
    $email = $_POST['email'];
    $departmentId = $_POST['department_id'];
    $courseId = $_POST['course_id'];
    $sectionId = $_POST['section_id'];

    // Update the instructor with the new data
    $instructor->update($id, $firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId);

    echo "Instructor updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto mt-10">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden max-w-2xl mx-auto">
            <div class="p-6">
                <button 
                    onclick="goBack()" 
                    class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
                >
                    <i class="fas fa-arrow-left mr-2 "></i> <!-- Arrow icon -->
                    Back
                </button>
                <h1 class="text-2xl font-semibold text-red-800 mb-4">Edit Instructor</h1>

                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-red-700 font-medium">First Name</label>
                            <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                                <span class="flex items-center px-3">
                                    <i class="fas fa-user  text-red-500"></i>
                                </span>
                                <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($instructorData['first_name']) ?>" 
                                       class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="middle_name" class="block text-red-700 font-medium">Middle Name</label>
                            <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                                <span class="flex items-center px-3">
                                    <i class="fas fa-user  text-red-500"></i>
                                </span>
                                <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($instructorData['middle_name']) ?>" 
                                       class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-red-700 font-medium">Last Name</label>
                            <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                                <span class="flex items-center px-3">
                                    <i class="fas fa-user  text-red-500"></i>
                                </span>
                                <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($instructorData['last_name']) ?>" 
                                       class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        </div>

                        <!-- Suffix -->
                        <div>
                            <label for="suffix" class="block text-red-700 font-medium">Suffix</label>
                            <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                                <span class="flex items-center px-3">
                                    <i class="fas fa-user-tag  text-red-500"></i>
                                </span>
                                <input type="text" id="suffix" name="suffix" value="<?= htmlspecialchars($instructorData['suffix']) ?>" 
                                       class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-red-700 font-medium">Email</label>
                        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                            <span class="flex items-center px-3">
                                <i class="fas fa-envelope  text-red-500"></i>
                            </span>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($instructorData['email']) ?>" 
                                   class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-red-700 font-medium">Department</label>
                        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                            <span class="flex items-center px-3">
                                <i class="fas fa-building  text-red-500"></i>
                            </span>
                            <select id="department_id" name="department_id" 
                                    class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= $dept['id'] == $instructorData['department_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Course -->
                    <div>
                        <label for="course_id" class="block text-red-700 font-medium">Course</label>
                        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                            <span class="flex items-center px-3">
                                <i class="fas fa-book  text-red-500"></i>
                            </span>
                            <select id="course_id" name="course_id" 
                                    class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>" <?= $course['id'] == $instructorData['course_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($course['course_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section -->
                    <div>
                        <label for="section_id" class="block text-red-700 font-medium">Section</label>
                        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                            <span class="flex items-center px-3">
                                <i class="fas fa-th-list  text-red-500"></i>
                            </span>
                            <select id="section_id" name="section_id" 
                                    class="mt-1 block w-full rounded-lg shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Section</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?= $section['id'] ?>" <?= $section['id'] == $instructorData['section_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($section['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Instructor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>

<script>
    

        function goBack() {
            window.history.back(); // Navigates to the previous page
        }

    document.addEventListener('DOMContentLoaded', function () {
        const departmentSelect = document.getElementById('department_id');
        const courseSelect = document.getElementById('course_id');
        const sectionSelect = document.getElementById('section_id');

        // Fetch courses when department is selected
        departmentSelect.addEventListener('change', function () {
            const departmentId = this.value;

            // Reset course and section dropdowns
            courseSelect.innerHTML = '<option value="">Select Course</option>';
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (departmentId) {
                fetchCourses(departmentId);
            }
        });

        // Fetch sections when course is selected
        courseSelect.addEventListener('change', function () {
            const courseId = this.value;

            // Reset section dropdown
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (courseId) {
                fetchSections(courseId);
            }
        });

        // Function to fetch courses based on department ID
        function fetchCourses(departmentId) {
            fetch('fetch_courses.php?department_id=' + departmentId)
                .then(response => response.json())
                .then(courses => {
                    courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.course_name;
                        courseSelect.appendChild(option);
                    });
                });
        }

        // Function to fetch sections based on course ID
        function fetchSections(courseId) {
            fetch('fetch_sections.php?course_id=' + courseId)
                .then(response => response.json())
                .then(sections => {
                    sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.name;
                        sectionSelect.appendChild(option);
                    });
                });
        }
    });
</script>

