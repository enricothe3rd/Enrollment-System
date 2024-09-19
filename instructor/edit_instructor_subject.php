<?php
require 'Instructor_subject.php';

$instructorSubject = new InstructorSubject();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the assignment details
    $assignment = $instructorSubject->getAssignmentById($id);
} else {
    // Handle the case where no ID is provided
    die('Invalid assignment ID');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instructor_id = $_POST['instructor_id'];
    $subject_id = $_POST['subject_id'];
    // $semester_id is not needed since it's not editable

    // Update the assignment
    $instructorSubject->updateAssignment($id, $instructor_id, $subject_id);

    // Redirect or display a success message
    header('Location: read_instructor_subjects.php');
    exit;
}

$instructors = $instructorSubject->getInstructors();
$subjects = $instructorSubject->getSubjects();
$semesters = $instructorSubject->getSemesters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor Subject</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Instructor Subject</h1>
        
        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="instructor_id" class="block text-gray-700 text-sm font-bold mb-2">Instructor:</label>
                <select id="instructor_id" name="instructor_id" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <?php foreach ($instructors as $instructor): ?>
                        <option value="<?php echo htmlspecialchars($instructor['id']); ?>" <?php echo $instructor['id'] == $assignment['instructor_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="subject_id" class="block text-gray-700 text-sm font-bold mb-2">Subject:</label>
                <select id="subject_id" name="subject_id" class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo htmlspecialchars($subject['id']); ?>" <?php echo $subject['id'] == $assignment['subject_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="semester_id" class="block text-gray-700 text-sm font-bold mb-2">Semester:</label>
                <span class="block w-full bg-gray-200 border border-gray-300 rounded-lg py-2 px-3 text-gray-700">
                    <?php echo htmlspecialchars($assignment['semester_name']); ?>
                </span>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Update</button>
        </form>
    </div>
</body>
</html>
