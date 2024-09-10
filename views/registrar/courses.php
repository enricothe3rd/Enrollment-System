<?php
require './db_connection1.php';

// Define paths to custom icons
$icons = [
    'success' => '../../assets/images/modal-icons/checked.png',
    'error' => '../../assets/images/modal-icons/cancel.png'
];

// Initialize variables
$message = '';
$messageType = '';
$customIcon = $icons['success']; // Default icon

// Process form submissions
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
            if (empty($course_name)) {
                $message = 'Please enter a course name.';
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            } else {
                // Fetch the current course name from the database
                $stmt = $pdo->prepare("SELECT course_name FROM courses WHERE id = ?");
                $stmt->execute([$course_id]);
                $currentCourseName = $stmt->fetchColumn();

                // Check if the submitted name is the same as the current one
                if ($currentCourseName === $course_name) {
                    $message = 'No changes were made.';
                    $messageType = 'error';
                    $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
                } else {
                    // Proceed with the update if the name has changed
                    $stmt = $pdo->prepare("UPDATE courses SET course_name = ? WHERE id = ?");
                    $stmt->execute([$course_name, $course_id]);
                    $message = 'Course updated successfully.';
                    $messageType = 'success';
                    $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
                }
            }
        } elseif (isset($_POST['delete_multiple'])) {
            if (empty($_POST['selected_courses'])) {
                $message = 'No courses selected for deletion.';
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            } else {
                $selected_courses = $_POST['selected_courses'];
                $in  = str_repeat('?,', count($selected_courses) - 1) . '?';
                $stmt = $pdo->prepare("DELETE FROM courses WHERE id IN ($in)");
                $stmt->execute($selected_courses);
                $message = count($selected_courses) . ' courses deleted successfully.';
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } catch (PDOException $e) {
        $message = 'An error occurred: ' . $e->getMessage();
        $messageType = 'error';
        $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
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
                <button onclick="closeModal1('messageModal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Close
                </button>
            </div>
        </div>

        <script>
            function closeModal1(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }
            document.getElementById('messageModal').style.display = 'flex';
        </script>
        <?php endif; ?>

        <!-- Add New Course Modal -->
        <div id="addCourseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
                <!-- Modal Title -->
                <h2 class="text-2xl font-semibold mb-6 text-gray-900">Add New Course</h2>
                
                <!-- Form Section -->
                <form method="POST">
                    <!-- Course Name Input Field -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-lg font-medium mb-3" for="course_name_modal">Course Name</label>
                        <input type="text" name="course_name" id="course_name_modal" 
                            class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-800 text-base leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter course name">
                    </div>
                    
                    <!-- Buttons Section -->
                    <div class="flex items-center justify-between">
                        <!-- Add Course Button -->
                        <button type="submit" name="create" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline">
                            Add Course
                        </button>
                        <!-- Close Button -->
                        <button type="button" onclick="closeModal('addCourseModal')" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Update Course Modal -->
        <div id="updateCourseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
                <!-- Modal Title -->
                <h2 class="text-2xl font-semibold mb-6 text-gray-900">Update Course</h2>

                <!-- Form Section -->
                <form method="POST">
                    <!-- Hidden Input for Course ID -->
                    <input type="hidden" name="course_id" id="course_id_modal">

                    <!-- Course Name Input Field -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-lg font-medium mb-3" for="course_name_modal_update">Course Name</label>
                        <input type="text" name="course_name" id="course_name_modal_update" 
                            class="shadow appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-800 text-base leading-tight focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Enter updated course name">
                    </div>

                    <!-- Buttons Section -->
                    <div class="flex items-center justify-between">
                        <!-- Update Course Button -->
                        <button type="submit" name="update" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline">
                            Update Course
                        </button>

                        <!-- Close Button -->
                        <button type="button" onclick="closeModal('updateCourseModal')" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>


       <!-- Trigger Add Button and Delete Button -->
            <!-- Trigger Add Button and Delete Button -->
            <div class="flex justify-between items-center mt-4">
                <button onclick="openModal('addCourseModal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add New Course
                </button>
                <form method="POST" class="inline-block">
                    <button type="submit" name="delete_multiple" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Delete Selected
                    </button>
                </form>
            </div>

        <!-- List All Courses with Multiple Delete -->
        <div class="bg-white shadow-md rounded p-4 overflow-x-auto mt-4">
            <form method="POST">
                <h2 class="text-xl font-semibold mb-4">Courses List</h2>
                <table class="min-w-full bg-white whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-800 text-white text-left">
                            <th class="py-3 px-4 uppercase font-semibold text-sm">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> 
                            </th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Course Name</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="py-3 px-4 border">
                                <input type="checkbox" name="selected_courses[]" value="<?php echo htmlspecialchars($course['id']); ?>">
                            </td>
                            <td class="py-3 px-4 border"><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td class="py-3 px-4 border">
                                <button type="button" onclick="openUpdateModal(<?php echo htmlspecialchars($course['id']); ?>, '<?php echo htmlspecialchars($course['course_name']); ?>')" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Update</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- <div class="mt-4">
                    <button type="submit" name="delete_multiple" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete Selected</button>
                </div> -->
            </form>
        </div>


    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openUpdateModal(courseId, courseName) {
            document.getElementById('course_id_modal').value = courseId;
            document.getElementById('course_name_modal_update').value = courseName;
            openModal('updateCourseModal');
        }

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('input[name="selected_courses[]"]');
            const selectAll = document.getElementById('selectAll').checked;
            checkboxes.forEach(checkbox => checkbox.checked = selectAll);
        }
    </script>
</body>
</html>
