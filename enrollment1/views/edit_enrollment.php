<?php
require_once '../classes/Enrollment.php';
require_once '../classes/Course.php';
require_once '../classes/Section.php';
require_once '../db_connection3.php';

$enrollment = new Enrollment($pdo);
$course = new Course($pdo);
$section = new Section($pdo);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];
$enrollmentData = $enrollment->getEnrollmentById($id);

if (!$enrollmentData) {
    die("Enrollment not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'id' => $id,
        'student_number' => $_POST['student_number'],
        'firstname' => $_POST['firstname'],
        'middlename' => $_POST['middlename'],
        'lastname' => $_POST['lastname'],
        'suffix' => $_POST['suffix'],
        'student_type' => $_POST['student_type'],
        'sex' => $_POST['sex'],
        'dob' => $_POST['dob'],
        'email' => $_POST['email'],
        'contact_no' => $_POST['contact_no'],
        'course_id' => $_POST['course_id'],
        'section_id' => $_POST['section_id']
    ];

    if ($enrollment->updateEnrollment($data)) {
        header('Location: index.php?status=updated');
        exit();
    } else {
        echo "<p class='text-red-600'>Failed to update enrollment. Please try again.</p>";
    }
}

$courses = $course->getAllCourses();
$sections = $section->getAllSections();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-200 p-6">
    <div class="max-w-6xl mx-auto bg-white p-8 shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold mb-8 text-gray-800">Edit Enrollment</h1>

        <form action="" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="student_number" class="block text-gray-700 font-medium">Student Number</label>
                    <input type="text" name="student_number" id="student_number" value="<?= htmlspecialchars($enrollmentData['student_number']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="firstname" class="block text-gray-700 font-medium">First Name</label>
                    <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($enrollmentData['firstname']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="middlename" class="block text-gray-700 font-medium">Middle Name</label>
                    <input type="text" name="middlename" id="middlename" value="<?= htmlspecialchars($enrollmentData['middlename']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block text-gray-700 font-medium">Last Name</label>
                    <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($enrollmentData['lastname']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="suffix" class="block text-gray-700 font-medium">Suffix</label>
                    <input type="text" name="suffix" id="suffix" value="<?= htmlspecialchars($enrollmentData['suffix']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="student_type" class="block text-gray-700 font-medium">Student Type</label>
                    <select name="student_type" id="student_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="regular" <?= $enrollmentData['student_type'] == 'regular' ? 'selected' : '' ?>>Regular</option>
                        <option value="new student" <?= $enrollmentData['student_type'] == 'new student' ? 'selected' : '' ?>>New Student</option>
                        <option value="irregular" <?= $enrollmentData['student_type'] == 'irregular' ? 'selected' : '' ?>>Irregular</option>
                        <option value="summer" <?= $enrollmentData['student_type'] == 'summer' ? 'selected' : '' ?>>Summer</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="sex" class="block text-gray-700 font-medium">Sex</label>
                    <select name="sex" id="sex" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="male" <?= $enrollmentData['sex'] == 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $enrollmentData['sex'] == 'female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dob" class="block text-gray-700 font-medium">Date of Birth</label>
                    <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($enrollmentData['dob']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($enrollmentData['email']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="contact_no" class="block text-gray-700 font-medium">Contact Number</label>
                    <input type="text" name="contact_no" id="contact_no" value="<?= htmlspecialchars($enrollmentData['contact_no']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="mb-4">
                    <label for="course_id" class="block text-gray-700 font-medium">Course</label>
                    <select name="course_id" id="course_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= $enrollmentData['course_id'] == $course['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['course_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="section_id" class="block text-gray-700 font-medium">Section</label>
                    <select name="section_id" id="section_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?= $section['id'] ?>" <?= $enrollmentData['section_id'] == $section['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($section['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition duration-150">Update Enrollment</button>
                <a href="index.php" class="text-blue-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
