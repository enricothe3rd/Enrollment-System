<?php
// Start session
session_start();

require '../db/db_connection3.php'; // Ensure this is the correct path to your Database class
$pdo = Database::connect();

try {
    // Prepare the SQL statement to fetch all enrollments with JOINs for courses, sections, and departments
    $stmt = $pdo->prepare("
        SELECT e.student_number, 
               e.firstname, 
               e.middlename, 
               e.lastname, 
               e.suffix, 
               c.course_name, 
               s.name AS section_name, 
               d.name AS department_name
        FROM enrollments e
        LEFT JOIN subject_enrollments se ON e.student_number = se.student_number
        LEFT JOIN courses c ON se.course_id = c.id
        LEFT JOIN sections s ON se.section_id = s.id
        LEFT JOIN departments d ON c.department_id = d.id
    ");
    $stmt->execute();

    // Fetch all enrollment data
    $enrollmentData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle any SQL errors
    echo "Error: " . $e->getMessage();
    exit;
}



    // Adjust column names to match your actual database structure
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
            sch.room AS room,
            se.school_year -- Ensure this column is selected
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
    $SubjectStmt->bindParam(':student_number', $_SESSION['student_number'], PDO::PARAM_STR);
    // Execute the statement
    $SubjectStmt->execute();

    // Fetch the subjects
    $subjects = $SubjectStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Student Enrollment Information</title>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Student Enrollment Information</h1>
        
        <!-- Enrollment Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full border-collapset shadow-md rounded-lg">
                <thead  class="bg-red-800">
                    <tr>
                        <th  class="px-4 py-4 border-b text-left text-white">Student Number</th>
                        <th  class="px-4 py-4 border-b text-left text-white">Full Name</th>
                        <th  class="px-4 py-4 border-b text-left text-white">Course</th>
                        <th  class="px-4 py-4 border-b text-left text-white">Section</th>
                        <th  class="px-4 py-4 border-b text-left text-white">Department</th>
                        <th  class="px-4 py-4 border-b text-left text-white">Action</th>
                    </tr>
                </thead>
                <tbody >
                    <?php if (count($enrollmentData) > 0): ?>
                        <?php foreach ($enrollmentData as $enrollment): ?>
                        <tr class="border-b bg-red-50 hover:bg-red-200">
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($enrollment['student_number']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($enrollment['firstname'] . ' ' . $enrollment['middlename'] . ' ' . $enrollment['lastname'] . ' ' . $enrollment['suffix']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($enrollment['course_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($enrollment['section_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($enrollment['department_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="view_details.php?student_number=<?= urlencode($enrollment['student_number']) ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-1 px-2 rounded transition duration-150">View</a>
                                <a href="edit_enrollment.php?student_number=<?= urlencode($enrollment['student_number']) ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">No enrollments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
