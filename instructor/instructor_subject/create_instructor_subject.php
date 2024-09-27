<?php
require 'Instructor_subject.php'; // Adjust the path if necessary

// Instantiate the InstructorSubject class
$instructorSubject = new InstructorSubject();

// Fetch departments
$departments = $instructorSubject->getDepartments();

// Fetch instructors
$instructors = $instructorSubject->getInstructors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Instructor Data</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <script>
        async function fetchCourses(departmentId) {
            try {
                const response = await fetch(`fetch_courses.php?department_id=${departmentId}`);
                const data = await response.json();
                let courseDropdown = document.getElementById('course');
                courseDropdown.innerHTML = '<option>Select a course</option>';
                data.forEach(course => {
                    courseDropdown.innerHTML += `<option value="${course.id}">${course.course_name}</option>`;
                });
            } catch (error) {
                console.error('Error fetching courses:', error);
            }
        }

        async function fetchSections(courseId) {
            try {
                const response = await fetch(`fetch_sections.php?course_id=${courseId}`);
                const data = await response.json();
                let sectionDropdown = document.getElementById('section');
                sectionDropdown.innerHTML = '<option>Select a section</option>';
                data.forEach(section => {
                    sectionDropdown.innerHTML += `<option value="${section.id}">${section.name}</option>`;
                });
            } catch (error) {
                console.error('Error fetching sections:', error);
            }
        }

        async function fetchSubjects(sectionId, semesterId) {
            try {
                const response = await fetch(`fetch_subjects.php?section_id=${sectionId}&semester_id=${semesterId}`);
                const data = await response.json();
                let subjectDropdown = document.getElementById('subject');
                subjectDropdown.innerHTML = '<option>Select a subject</option>';
                data.forEach(subject => {
                    subjectDropdown.innerHTML += `<option value="${subject.id}">${subject.title}</option>`;
                });
            } catch (error) {
                console.error('Error fetching subjects:', error);
            }
        }

        function updateSubjects() {
            const sectionId = document.getElementById('section').value;
            const semesterId = document.getElementById('semester').value;
            if (sectionId && semesterId) {
                fetchSubjects(sectionId, semesterId);
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div  class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-2xl">
    <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Assign Subject to Instructor</h1>
        <form method="POST" action="process_instructor.php" class="space-y-4">
            <!-- Instructor Information -->
            <div class="mb-4">
                <label for="instructor" class="block text-red-700 font-medium">
                    <i class="fas fa-user-tie mr-2"></i> Select Instructor:
                </label>
                <select id="instructor" name="instructor" class="bg-red-50 block w-full px-3 py-3 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                    <option>Select an instructor</option>
                    <?php
                    foreach ($instructors as $inst) {
                        echo "<option value='{$inst['id']}'>{$inst['first_name']} {$inst['last_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Department Dropdown -->
            <div class="mb-4">
                <label for="department" class="block text-red-700 font-medium">
                    <i class="fas fa-building mr-2"></i> Select Department:
                </label>
                <select id="department" name="department" onchange="fetchCourses(this.value)" class="bg-red-50 block w-full px-3 py-3 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                    <option>Select a department</option>
                    <?php
                    foreach ($departments as $department) {
                        echo "<option value='{$department['id']}'>{$department['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Course Dropdown -->
            <div class="mb-4">
                <label for="course" class="block text-red-700 font-medium">
                    <i class="fas fa-book mr-2"></i> Select Course:
                </label>
                <select id="course" name="course" onchange="fetchSections(this.value)" class="bg-red-50 block w-full px-3 py-3 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                    <option>Select a course</option>
                </select>
            </div>

            <!-- Section Dropdown -->
            <div class="mb-4">
                <label for="section" class="block text-red-700 font-medium">
                    <i class="fas fa-chalkboard-teacher mr-2"></i> Select Section:
                </label>
                <select id="section" name="section" onchange="updateSubjects()" class="bg-red-50 block w-full px-3 py-3 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                    <option>Select a section</option>
                </select>
            </div>

            <!-- Semester Dropdown -->
            <div class="mb-4">
                <label for="semester" class="block text-red-700 font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i> Select Semester:
                </label>
                <select id="semester" name="semester" onchange="updateSubjects()" class="bg-red-50 block w-full px-3 py-3 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                </select>
            </div>

            <!-- Subject Dropdown -->
            <div class="mb-4">
                <label for="subject" class="block text-red-700 font-medium">
                    <i class="fas fa-book-open mr-2"></i> Select Subject:
                </label>
                <select id="subject" name="subject" class="bg-red-50 block w-full px-3 py-3 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                    <option>Select a subject</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-red-700 text-white py-2 px-4 rounded-lg hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-plus-circle mr-2"></i> Assign Subject
            </button>
        </form>
    </div>
</body>
<script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
    </script>
</html>
