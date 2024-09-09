<?php
require './db_connection1.php';

// Define paths to custom icons
$icons = [
    'success' => '../../assets/images/modal-icons/checked.png', // Path to your success icon
    'error' => '../../assets/images/modal-icons/cancel.png'     // Path to your error icon
];

// Initialize variables
$message = '';
$messageType = '';
$customIcon = $icons['success']; // Default icon

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'] ?? null;
    $course_name = trim($_POST['course_name'] ?? '');

    try {
        if (isset($_POST['create'])) {
            if (empty($course_name)) {
                $message = 'Please enter a course name.';
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            } else {
                $stmt = $pdo->prepare("INSERT INTO courses (course_name) VALUES (?)");
                $stmt->execute([$course_name]);
                $message = 'Course added successfully.';
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        } elseif (isset($_POST['update'])) {
            $stmt = $pdo->prepare("SELECT course_name FROM courses WHERE id = ?");
            $stmt->execute([$course_id]);
            $existing_course = $stmt->fetchColumn();

            if (empty($course_name)) {
                $message = 'Please enter a course name.';
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            } elseif ($course_name === $existing_course) {
                $message = 'No changes detected. Please update the course name.';
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            } else {
                $stmt = $pdo->prepare("UPDATE courses SET course_name = ? WHERE id = ?");
                $stmt->execute([$course_name, $course_id]);
                $message = 'Course updated successfully.';
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        } elseif (isset($_POST['delete']) && $course_id) {
            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
            $stmt->execute([$course_id]);
            $message = 'Course deleted successfully.';
            $messageType = 'success';
            $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
        }
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { // Handling foreign key constraint violation
            $message = 'Cannot delete this course as it is linked to existing classes. Please remove the related classes first.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            $message = 'An error occurred while processing your request. Please try again.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        }
    }
}

// Fetch all courses
$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Manage Courses</h1>

        <!-- Success/Error Modal -->
        <?php if ($message): ?>
        <div id="messageModal" class="fixed inset-0 flex items-center justify-center z-50 animate__animated <?php echo $messageType == 'success' ? 'animate__bounceIn' : 'animate__shakeX'; ?>">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center max-w-md w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
                <?php echo $customIcon; ?>
                <div class="<?php echo $messageType == 'success' ? 'text-green-500' : 'text-red-500'; ?> text-lg sm:text-xl font-semibold mb-4">
                    <span><?php echo htmlspecialchars($message); ?></span>
                </div>
                <button onclick="closeModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Close
                </button>
            </div>
        </div>

        <script>
            function closeModal() {
                document.getElementById('messageModal').style.display = 'none';
            }
            document.getElementById('messageModal').style.display = 'flex';
        </script>
        <?php endif; ?>

        <!-- Create New Course -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Add New Course</h2>
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="course_name">Course Name</label>
                    <input type="text" name="course_name" id="course_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Course</button>
                </div>
            </form>
        </div>

  <!-- List All Courses -->
<div class="bg-white shadow-md rounded p-4 overflow-x-auto">
    <h2 class="text-xl font-semibold mb-4">Courses List</h2>
    <table class="min-w-full bg-white whitespace-nowrap">
        <thead>
            <tr class="bg-gray-800 text-white text-left">
                <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">Course Name</th>
                <th class="py-3 px-4 uppercase font-semibold text-sm">
                    <div class="flex gap-8 items-center">
                        <span>Edit Course Name</span>
                        <span class="ml-12">Update</span>
                        <span class="">Delete</span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($course['id']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($course['course_name']); ?></td>
                <td class="border px-4 py-2">
                    <!-- Update Form -->
                    <form method="POST" class="inline">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['id']); ?>">
                        <input type="text" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" class="border px-2 py-1">
                        <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Update</button>
                    </form>
                    <!-- Delete Form -->
                    <form method="POST" class="inline">
                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['id']); ?>">
                        <button type="submit" name="delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    </div>
</body>
</html>
