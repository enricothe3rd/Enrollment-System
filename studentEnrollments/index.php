<?php
require_once '../db/db_connection1.php';
require_once 'classes/Course.php';
require_once 'classes/Enrollment.php';

$enrollment = new Enrollment($pdo);
$course = new Course($pdo);

$student_number = $enrollment->generateStudentNumber();
$courses = $course->getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-6">Enrollment Form</h1>

        <form action="enroll.php" method="POST">
            <!-- System-generated Student Number -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Student Number</label>
                <input type="text" name="student_number" value="<?= $student_number ?>" readonly class="w-full px-4 py-2 border rounded-md bg-gray-100">
            </div>

            <!-- Student Details -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input type="text" name="firstname" placeholder="First Name" required class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <input type="text" name="middlename" placeholder="Middle Name" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <input type="text" name="lastname" placeholder="Last Name" required class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <input type="text" name="suffix" placeholder="Suffix" class="w-full px-4 py-2 border rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <select name="student_type" required class="w-full px-4 py-2 border rounded-md">
                        <option value="regular">Regular</option>
                        <option value="new student">New Student</option>
                        <option value="irregular">Irregular</option>
                        <option value="summer">Summer</option>
                    </select>
                </div>
                <div>
                    <select name="sex" required class="w-full px-4 py-2 border rounded-md">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <input type="date" name="dob" required class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded-md">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <input type="text" name="contact_no" placeholder="Contact Number" class="w-full px-4 py-2 border rounded-md">
                </div>
                <div>
                    <!-- Course Selection -->
                    <select id="course" name="course_id" required class="w-full px-4 py-2 border rounded-md">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>"><?= $course['course_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Section Selection (Populated dynamically) -->
            <div class="mt-4">
                <select id="section" name="section_id" required class="w-full px-4 py-2 border rounded-md">
                    <option value="">Select Section</option>
                </select>
            </div>

            <!-- Subject and Schedule (Populated dynamically) -->
            <div id="subjectSchedule" class="mt-4">
                <!-- This section will be populated by Ajax -->
            </div>

            <button type="submit" class="mt-6 w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Enroll</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        // Fetch sections when a course is selected
        $('#course').change(function() {
            var course_id = $(this).val();
            $.ajax({
                url: 'get_sections.php',
                type: 'GET',
                data: { course_id: course_id },
                success: function(data) {
                    $('#section').html(data);
                }
            });
        });

        // Fetch subjects and schedule when a section is selected
        $('#section').change(function() {
            var section_id = $(this).val();
            $.ajax({
                url: 'get_subjects.php',
                type: 'GET',
                data: { section_id: section_id },
                success: function(data) {
                    $('#subjectSchedule').html(data);
                }
            });
        });
    });
    </script>
</body>
</html>
