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
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Assign Subject to Instructor</h1>
        <form method="POST" action="process_instructor.php" class="bg-white p-6 rounded-lg shadow-lg">
            <!-- Instructor Information -->
            <div class="mb-4">
                <label for="instructor" class="block text-gray-700 text-sm font-bold mb-2">Select Instructor:</label>
                <select id="instructor" name="instructor" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                <label for="department" class="block text-gray-700 text-sm font-bold mb-2">Select Department:</label>
                <select id="department" name="department" onchange="fetchCourses(this.value)" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                <label for="course" class="block text-gray-700 text-sm font-bold mb-2">Select Course:</label>
                <select id="course" name="course" onchange="fetchSections(this.value)" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select a course</option>
                </select>
            </div>

            <!-- Section Dropdown -->
            <div class="mb-4">
                <label for="section" class="block text-gray-700 text-sm font-bold mb-2">Select Section:</label>
                <select id="section" name="section" onchange="updateSubjects()" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select a section</option>
                </select>
            </div>

            <!-- Semester Dropdown -->
            <div class="mb-4">
                <label for="semester" class="block text-gray-700 text-sm font-bold mb-2">Select Semester:</label>
                <select id="semester" name="semester" onchange="updateSubjects()" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                </select>
            </div>

            <!-- Subject Dropdown -->
            <div class="mb-4">
                <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Select Subject:</label>
                <select id="subject" name="subject" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Select a subject</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Assign Subject</button>
        </form>
    </div>
</body>
</html>
