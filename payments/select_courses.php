<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course & Sections Selection</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
        <!-- <div class="w-full h-full" style="height:90vh;">
            <iframe src="enrollments/create_enrollment.php" title="Embedded Page" class="w-full h-full border-none"></iframe>
        </div> -->

    <div class="max-w-3xl mx-auto bg-white shadow-lg p-8 rounded-lg">
    

        <h2 class="text-2xl font-bold text-center mb-8 text-blue-700">Select Department and Course</h2>

        <!-- Department Dropdown -->
        <label for="department" class="block mb-2 font-medium">Select Department</label>
        <select id="department" class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Department</option>
            <!-- Department options will be populated dynamically -->
        </select>

        <!-- Course Dropdown -->
        <label for="course" class="block mb-2 font-medium">Select Course</label>
        <select id="course" class="w-full p-3 mb-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
            <option value="">Select Course</option>
        </select>

        <!-- Semester Dropdown -->
        <label for="semester" class="block mb-2 font-medium">Select Semester</label>
        <select id="semester" class="w-full p-3 mb-6 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
            <option value="">Select Semester</option>
        </select>

        <!-- Table for Sections, Subjects, and Schedule -->
        <div id="result" class="mt-6">
            <!-- Sections and Subjects will be displayed here dynamically -->
        </div>
    </div>

    <script>
        // Fetch departments on page load
        document.addEventListener('DOMContentLoaded', function () {
            fetch('fetch_departments.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('department').innerHTML += data;
                });
        });

        // Fetch courses when a department is selected
        document.getElementById('department').addEventListener('change', function () {
            const departmentId = this.value;
            const courseDropdown = document.getElementById('course');

            if (departmentId) {
                fetch('fetch_courses.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'department_id=' + departmentId
                })
                .then(response => response.text())
                .then(data => {
                    courseDropdown.innerHTML = data;
                    courseDropdown.disabled = false;
                    document.getElementById('semester').innerHTML = '<option value="">Select Semester</option>'; // Reset semester options
                    document.getElementById('semester').disabled = true; // Disable semester dropdown
                });
            } else {
                courseDropdown.innerHTML = '<option value="">Select Course</option>';
                courseDropdown.disabled = true;
                document.getElementById('semester').innerHTML = '<option value="">Select Semester</option>';
                document.getElementById('semester').disabled = true;
                document.getElementById('result').innerHTML = '';
            }
        });

        // Fetch semesters when a course is selected
        document.getElementById('course').addEventListener('change', function () {
            const semesterDropdown = document.getElementById('semester');
            semesterDropdown.disabled = !this.value;

            if (this.value) {
                fetch('fetch_semesters.php')
                    .then(response => response.text())
                    .then(data => {
                        semesterDropdown.innerHTML = '<option value="">Select Semester</option>' + data;
                        semesterDropdown.disabled = false; // Enable dropdown if semesters are available
                    });
            } else {
                semesterDropdown.innerHTML = '<option value="">Select Semester</option>';
                semesterDropdown.disabled = true;
            }
        });

        // Fetch sections, subjects, and schedule when a semester is selected
        document.getElementById('semester').addEventListener('change', function () {
            const courseId = document.getElementById('course').value;
            const semesterId = this.value;

            if (courseId && semesterId) {
                fetch('fetch_sections.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'course_id=' + courseId + '&semester_id=' + semesterId
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('result').innerHTML = data;
                });
            } else {
                document.getElementById('result').innerHTML = '';
            }
        });
    </script>

</body>
</html>
