<?php
// Include your DB connection
require_once '../db/db_connection1.php'; 

// Generate student number based on the last student record
function generateStudentNumber($pdo) {
    // Fetch the last student_number
    $stmt = $pdo->query("SELECT student_number FROM enrollments ORDER BY id DESC LIMIT 1");
    $lastStudent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($lastStudent) {
        // Increment the last student number by 1
        $lastNumber = intval(substr($lastStudent['student_number'], -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    } else {
        // Start from a base number if no student exists
        $newNumber = '0001';
    }

    // Concatenate with the current year (you can change this logic as per your requirement)
    return date('Y') . '-' . $newNumber;
}

$student_number = generateStudentNumber($pdo); // Call the function to generate student number

// Fetch courses
$stmt = $pdo->query("SELECT id, course_name FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form action="enroll.php" method="POST">
        <!-- System-generated Student Number (Read-only) -->
        <input type="text" name="student_number" value="<?= $student_number ?>" readonly>

        <!-- Student Details -->
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="middlename" placeholder="Middle Name">
        <input type="text" name="lastname" placeholder="Last Name" required>
        <input type="text" name="suffix" placeholder="Suffix">

        <select name="student_type" required>
            <option value="regular">Regular</option>
            <option value="new student">New Student</option>
            <option value="irregular">Irregular</option>
            <option value="summer">Summer</option>
        </select>

        <select name="sex" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        <input type="date" name="dob" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="contact_no" placeholder="Contact Number">

        <!-- Course Selection -->
        <select id="course" name="course_id" required>
            <option value="">Select Course</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>"><?= $course['course_name'] ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Section Selection (Populated dynamically) -->
        <select id="section" name="section_id" required>
            <option value="">Select Section</option>
        </select>

        <!-- Subject and Schedule (Populated dynamically) -->
        <div id="subjectSchedule">
            <!-- This section will be populated by Ajax -->
        </div>

        <button type="submit">Enroll</button>
    </form>

    <script>
    $(document).ready(function() {
        // When course is selected, load sections
        $('#course').change(function() {
            var courseId = $(this).val();
            if (courseId) {
                $.ajax({
                    url: 'get_sections.php',
                    type: 'POST',
                    data: {course_id: courseId},
                    success: function(response) {
                        $('#section').html(response);
                    }
                });
            } else {
                $('#section').html('<option value="">Select Section</option>');
            }
        });

        // When section is selected, load subjects and schedules
        $('#section').change(function() {
            var sectionId = $(this).val();
            if (sectionId) {
                $.ajax({
                    url: 'get_subjects.php',
                    type: 'POST',
                    data: {section_id: sectionId},
                    success: function(response) {
                        $('#subjectSchedule').html(response);
                    }
                });
            } else {
                $('#subjectSchedule').html('');
            }
        });
    });
    </script>
</body>
</html>
