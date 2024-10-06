<?php
session_start();
require '../db/db_connection3.php';
require '../message.php';

$pdo = Database::connect();

// Check if the session variables are set
if (isset($_SESSION['student_number']) && isset($_SESSION['user_email'])) {
    $student_number = $_SESSION['student_number'];
    $email = $_SESSION['user_email'];

    // Check if the student has made payments with the desired status and method
    $checkPaymentStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM payments 
        WHERE student_number = :student_number
        AND payment_status = 'completed'
        AND payment_method = 'cash'
    ");
    
    // Bind the student number
    $checkPaymentStmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
    
    // Execute the payment check
    $checkPaymentStmt->execute();
    $paymentValid = $checkPaymentStmt->fetchColumn();

    // Initialize subjects array
    $subjects = [];

    // Only fetch subjects if the payment is valid
    if ($paymentValid > 0) {
        // Prepare the SQL statement to fetch subject enrollments
        $SubjectStmt = $pdo->prepare("
            SELECT 
                se.id,
                se.student_number,
                s.name AS section_name,
                d.name AS department_name,
                c.course_name AS course_name,
                sub.code AS subject_code,
                sub.title AS subject_title,
                sub.units AS subject_units,
                sem.semester_name AS semester_name,
                sch.day_of_week AS day_of_week,
                sch.start_time AS start_time,
                sch.end_time AS end_time,
                sch.room AS room
            FROM subject_enrollments se
            LEFT JOIN sections s ON se.section_id = s.id
            LEFT JOIN departments d ON se.department_id = d.id
            LEFT JOIN courses c ON se.course_id = c.id
            LEFT JOIN subjects sub ON se.subject_id = sub.id
            LEFT JOIN semesters sem ON sub.semester_id = sem.id
            LEFT JOIN schedules sch ON se.schedule_id = sch.id
            WHERE se.student_number = :student_number
        ");

        // Bind the session student number to the SQL statement
        $SubjectStmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);

        // Execute the statement
        $SubjectStmt->execute();

        // Fetch the subjects
        $subjects = $SubjectStmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // If no valid payment, inform the user
        displayMessage('error', 'Payment Required', 'No valid payment found for this student. Please complete your payment using cash to view subjects.');
        exit; // Exit to prevent further processing
    }
} else {
    // Handle case where session variables are not set
    displayMessage('warning', 'Session Error', 'No valid session found. Please log in.');
    exit; // Exit if session is invalid
}

// Extract course details if subjects are available
$semester_name = !empty($subjects) ? $subjects[0]['semester_name'] : null;
$section_name = !empty($subjects) ? $subjects[0]['section_name'] : null;
$course_name = !empty($subjects) ? $subjects[0]['course_name'] : null;
$department_name = !empty($subjects) ? $subjects[0]['department_name'] : null;

// Function to convert time to 12-hour format with AM/PM
function formatTime($time) {
    return date("g:i A", strtotime($time));
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Subject Enrollments</title>
</head>
<body class="bg-gray-100">

<div class="container mx-auto my-4 p-6 overflow-x-auto">
    <h2 class="text-2xl font-semibold text-red-800 mb-4">Enrolled Subject</h2>
    
    <!-- Display the email if it's available -->
    <!-- <?php// if// ($email): ?>
    <div class="p-6 bg-gray-200">
        Logged in as:// <?php //echo htmlspecialchars($email); ?>
    </div>
    <?php //endif; ?> -->
    
    <!-- Display the course and section names -->
    <div class="mb-4">
   
        <?php if ($course_name): ?>
            <h3 class="block text-red-700 text-lg font-medium">Course: <span class="text-gray-800"><?= htmlspecialchars($course_name) ?></span></h3>
        <?php endif; ?>

        <?php if ($department_name): ?>
            <h3 class="block text-red-700 text-lg font-medium">Department: <span class="text-gray-800"><?= htmlspecialchars($department_name) ?></span></h3>
        <?php endif; ?>

        <?php if ($semester_name): ?>
            <h3 class="block text-red-700 text-lg font-medium">Semester: <span class="text-gray-800"><?= htmlspecialchars($semester_name) ?></span></h3>
        <?php endif; ?>
    </div>


            <div>
                <p>please complete you payment first</p>
            </div>

    <!-- Table of subject enrollments -->
    <table class="min-w-full table-auto bg-gray-50 rounded-lg shadow-md">
        <thead class="bg-red-800">
            <tr class="hidden sm:table-row">
            <th class="px-4 py-4 border-b text-left text-white">Section Name</th>
                <th class="px-4 py-4 border-b text-left text-white">Subject Code</th>
                <th class="px-4 py-4 border-b text-left text-white">Subject Title</th>
                <th class="px-4 py-4 border-b text-left text-white">Units</th>
                <th class="px-4 py-4 border-b text-left text-white">Schedule (Day, Time)</th>
                <th class="px-4 py-4 border-b text-left text-white">Room</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subjects)): ?>
                <?php foreach ($subjects as $subject): ?>
                    <tr class="border-b bg-red-50 hover:bg-red-200 block sm:table-row">
                    <td class="border-t px-6 py-3 block sm:table-cell">
                            <span class="sm:hidden font-semibold">Room: </span>
                            <?= htmlspecialchars($subject['section_name']) ?>
                        </td>
                        <td class="border-t px-6 py-3 block sm:table-cell">
                            <span class="sm:hidden font-semibold">Subject Code: </span>
                            <?= htmlspecialchars($subject['subject_code']) ?>
                        </td>
                        <td class="border-t px-6 py-3 block sm:table-cell">
                            <span class="sm:hidden font-semibold">Subject Title: </span>
                            <?= htmlspecialchars($subject['subject_title']) ?>
                        </td>
                        <td class="border-t px-6 py-3 block sm:table-cell">
                            <span class="sm:hidden font-semibold">Units: </span>
                            <?= htmlspecialchars($subject['subject_units']) ?>
                        </td>
                        <td class="border-t px-6 py-3 block sm:table-cell">
                            <span class="sm:hidden font-semibold">Schedule: </span>
                            <?= htmlspecialchars($subject['day_of_week']) ?>, 
                            <?= formatTime($subject['start_time']) ?> - 
                            <?= formatTime($subject['end_time']) ?>
                        </td>
                        <td class="border-t px-6 py-3 block sm:table-cell">
                            <span class="sm:hidden font-semibold">Room: </span>
                            <?= htmlspecialchars($subject['room']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="py-3 px-6 text-center">No subjects found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
