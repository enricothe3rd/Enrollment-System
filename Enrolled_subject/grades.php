<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../db/db_connection3.php'; // Ensure this path is correct

$db = Database::connect();

session_start(); // Start session to access instructor data
$instructor_id = $_SESSION['instructor_id'] ?? null;

// Fetch the instructor's ID
$instructors = [];
if ($instructor_id) {
    $stmt = $db->prepare("SELECT id FROM instructors WHERE id = ?");
    $stmt->execute([$instructor_id]);
    $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (!empty($instructors)) {
    $instructor_id = $instructors[0]['id']; // Set the instructor_id if it exists
} else {
    die("Instructor not found."); // Handle case where instructor does not exist
}

// Fetch subjects assigned to this instructor
$assigned_subjects = [];
if (!empty($instructor_id)) {
    $stmt = $db->prepare("
        SELECT s.id, s.title, s.units 
        FROM instructor_subjects isub 
        JOIN subjects s ON isub.subject_id = s.id 
        WHERE isub.instructor_id = ?
    ");
    $stmt->execute([$instructor_id]);
    $assigned_subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch students based on the selected subject
$students = [];
$subject_id = null; // Initialize subject_id
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

// Handle form submission for adding or updating grades
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_grade'])) {
    // Your existing code for adding or updating grades
}

// Handle AJAX request to fetch existing grades
if (isset($_GET['fetch_grades']) && isset($_GET['student_number']) && isset($_GET['subject_id'])) {
    $student_number = $_GET['student_number'];
    $subject_id = $_GET['subject_id'];

    // Fetch existing grades for the student and subject
    $stmt = $db->prepare("
        SELECT prelim, midterm, finals 
        FROM grades 
        WHERE student_id = (SELECT id FROM enrollments WHERE student_number = ?) AND subject_id = ?
    ");
    $stmt->execute([$student_number, $subject_id]);
    $grades = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($grades);
    exit; // Exit to avoid rendering the rest of the page
}

// Handle form submission for deleting grades
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_grade_id'])) {
    $delete_grade_id = $_POST['grade_id'] ?? null; // Use the hidden input's name

    if ($delete_grade_id) {
        // Prepare and execute the delete statement
        $stmt = $db->prepare("DELETE FROM grades WHERE id = ?");
        if ($stmt->execute([$delete_grade_id])) {
            echo "<p class='text-green-500'>Grade deleted successfully!</p>";
        } else {
            $errorInfo = $stmt->errorInfo(); // Capture error information
            echo "<p class='text-red-500'>Error deleting grade: " . htmlspecialchars($errorInfo[2]) . "</p>";
        }
    } else {
        echo "<p class='text-red-500'>No grade ID provided.</p>";
    }
}


// Handle form submission for updating grades
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_grade'])) {
    $grade_id = $_POST['grade_id'];
    $prelim = $_POST['prelim'];
    $midterm = $_POST['midterm'];
    $finals = $_POST['finals'];

    // Prepare and execute the update statement
    $stmt = $db->prepare("UPDATE grades SET prelim = ?, midterm = ?, finals = ? WHERE id = ?");
    if ($stmt->execute([$prelim, $midterm, $finals, $grade_id])) {
        echo "<p class='text-green-500'>Grade updated successfully!</p>";
    } else {
        echo "<p class='text-red-500'>Error updating grade.</p>";
    }
}

// Fetch all grades for displaying, including units
$grades = $db->query("
    SELECT g.id, e.student_number, e.firstname, e.lastname, sub.title AS subject_title, g.prelim, g.midterm, g.finals, sub.units 
    FROM grades g 
    JOIN enrollments e ON g.student_id = e.id 
    JOIN subjects sub ON g.subject_id = sub.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Instructor Manage Grades</title>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Manage Grades</h1>

        <!-- Form for Selecting Subject -->
        <form method="POST" action="" class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Select Subject to View Students</h2>
            <div class="mb-4">
                <label for="subject_id" class="block text-gray-700">Subject:</label>
                <select name="subject_id" class="border rounded p-2 w-full" required>
                    <option value="" disabled selected>Select a subject</option>
                    <?php foreach ($assigned_subjects as $subject): ?>
                        <option value="<?= $subject['id']; ?>" <?= isset($subject_id) && $subject_id == $subject['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($subject['title'] ?? ''); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-blue-600">View Students</button>
        </form>

        <!-- If subject_id is selected, display students -->
        <?php if (!empty($students)): ?>
            <form method="POST" action="" class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Add Grades</h2>
                <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id ?? ''); ?>">

                <div class="mb-4">
                    <label for="student_number" class="block text-gray-700">Student:</label>
                    <select name="student_number" class="border rounded p-2 w-full" required>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= htmlspecialchars($student['student_number'] ?? ''); ?>">
                                <?= htmlspecialchars(($student['firstname'] ?? '') . ' ' . ($student['lastname'] ?? '')); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
    <label for="prelim" class="block text-gray-700">Prelim:</label>
    <input type="number" name="prelim" step="0.01" min="0" max="100" class="border rounded p-2 w-full" value="<?= htmlspecialchars($_POST['prelim'] ?? ''); ?>">
</div>

<div class="mb-4">
    <label for="midterm" class="block text-gray-700">Midterm:</label>
    <input type="number" name="midterm" step="0.01" min="0" max="100" class="border rounded p-2 w-full" value="<?= htmlspecialchars($_POST['midterm'] ?? ''); ?>">
</div>

<div class="mb-4">
    <label for="finals" class="block text-gray-700">Finals:</label>
    <input type="number" name="finals" step="0.01" min="0" max="100" class="border rounded p-2 w-full" value="<?= htmlspecialchars($_POST['finals'] ?? ''); ?>">
</div>


                <button type="submit" name="add_grade" class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-blue-600">Add Grades</button>
            </form>
        <?php endif; ?>

        <!-- Table for Displaying Grades -->
        <h2 class="text-xl font-semibold mb-4">Grades List</h2>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow">
            <thead>
                <tr>
                    <th class="border-b border-gray-300 p-4">Student Number</th>
                    <th class="border-b border-gray-300 p-4">Student Name</th>
                    <th class="border-b border-gray-300 p-4">Subject</th>
                    <th class="border-b border-gray-300 p-4">Units</th>
                    <th class="border-b border-gray-300 p-4">Prelim</th>
                    <th class="border-b border-gray-300 p-4">Midterm</th>
                    <th class="border-b border-gray-300 p-4">Finals</th>
                    <th class="border-b border-gray-300 p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['student_number'] ?? ''); ?></td>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['firstname'] . ' ' . $grade['lastname']); ?></td>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['subject_title'] ?? ''); ?></td>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['units'] ?? ''); ?></td>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['prelim'] ?? ''); ?></td>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['midterm'] ?? ''); ?></td>
                        <td class="border-b border-gray-300 p-4"><?= htmlspecialchars($grade['finals'] ?? ''); ?></td>
                        <td class="border-b border-gray-300 p-4">
                            <form method="POST" action="" class="inline">
                                <input type="hidden" name="grade_id" value="<?= htmlspecialchars($grade['id'] ?? ''); ?>">
                                <button type="submit" name="delete_grade_id" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
