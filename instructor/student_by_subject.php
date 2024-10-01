<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../db/db_connection3.php'; // Ensure this path is correct

// Connect to the database using the Database class
$db = Database::connect();

session_start(); // Start session to access instructor data

// Fetch the instructor's ID
$instructor_id = $_SESSION['instructor_id'] ?? null; // Use null if not set

// Ensure the instructor ID is valid
if (!$instructor_id) {
    die("Instructor not found."); // Handle case where instructor does not exist
}

// Fetch subjects assigned to this instructor
$assigned_subjects = [];
$stmt = $db->prepare("
    SELECT s.id, s.title 
    FROM instructor_subjects isub 
    JOIN subjects s ON isub.subject_id = s.id 
    WHERE isub.instructor_id = ?
");
$stmt->execute([$instructor_id]);
$assigned_subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch students based on the selected subject
$students = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];
    
    $stmt = $db->prepare("
        SELECT e.student_number, e.firstname, e.lastname 
        FROM subject_enrollments se 
        JOIN enrollments e ON se.student_number = e.student_number 
        WHERE se.subject_id = ?
    ");
    $stmt->execute([$subject_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>View Students by Subject</title>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">View Students by Subject</h1>

        <!-- Form for Selecting Subject -->
        <form method="POST" action="" class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Select Subject to View Students</h2>
            <div class="mb-4">
                <label for="subject_id" class="block text-gray-700">Subject:</label>
                <select name="subject_id" class="border rounded p-2 w-full" required>
                    <option value="">-- Select a Subject --</option>
                    <?php foreach ($assigned_subjects as $subject): ?>
                        <option value="<?= $subject['id']; ?>"><?= htmlspecialchars($subject['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-blue-600">View Students</button>
        </form>

        <!-- If subject_id is selected, display students -->
        <?php if (!empty($students)): ?>
            <h2 class="text-xl font-semibold mb-4">Students Enrolled in Selected Subject</h2>
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="border-b border-gray-300 p-4">Student Number</th>
                        <th class="border-b border-gray-300 p-4">Student Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($student['student_number']); ?></td>
                            <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">No students found for this subject.</p>
        <?php endif; ?>
    </div>
</body>
</html>
