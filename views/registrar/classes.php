<?php
require './db_connection1.php';

// Define paths to custom icons
$icons = [
    'success' => '../../assets/images/modal-icons/checked.png', // Path to your success icon
    'error' => '../../assets/images/modal-icons/cancel.png'   // Path to your error icon
];

// Initialize variables
$message = '';
$messageType = '';
$customIcon = $icons['success']; // Default icon

// Handle create/update/delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = isset($_POST['course_id']) ? trim($_POST['course_id']) : null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (isset($_POST['create'])) {
        if (!$course_id || !$name || !$description) {
            $message = 'Course ID, class name, and description are required.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO classes (course_id, name, description) VALUES (?, ?, ?)");
                $stmt->execute([$course_id, $name, $description]);
                $message = 'Class added successfully.';
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            } catch (PDOException $e) {
                $message = 'Error: ' . htmlspecialchars($e->getMessage());
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } elseif (isset($_POST['update'])) {
        $class_id = $_POST['class_id'] ?? null;

        if (!$class_id || !$course_id || !$name || !$description) {
            $message = 'Error: Missing class ID, course ID, class name, or description.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT course_id, name, description FROM classes WHERE id = ?");
                $stmt->execute([$class_id]);
                $currentClass = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($currentClass['course_id'] == $course_id && $currentClass['name'] == $name && $currentClass['description'] == $description) {
                    $message = 'Please enter your changes.';
                    $messageType = 'error';
                    $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
                } else {
                    $stmt = $pdo->prepare("UPDATE classes SET course_id = ?, name = ?, description = ? WHERE id = ?");
                    $stmt->execute([$course_id, $name, $description, $class_id]);
                    $message = 'Class updated successfully.';
                    $messageType = 'success';
                    $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
                }
            } catch (PDOException $e) {
                $message = 'Error: ' . htmlspecialchars($e->getMessage());
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } elseif (isset($_POST['delete'])) {
        $class_id = $_POST['class_id'] ?? null;

        if (!$class_id) {
            $message = 'Error: Missing class ID.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
                $stmt->execute([$class_id]);
                $message = 'Class deleted successfully.';
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            } catch (PDOException $e) {
                $message = 'Error: ' . htmlspecialchars($e->getMessage());
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    }
}


// Pagination variables
$itemsPerPage = 10; // Number of items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $itemsPerPage;

// Fetch total number of classes
$totalStmt = $pdo->query("SELECT COUNT(*) FROM classes");
$totalItems = $totalStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch paginated classes and courses for dropdowns
$stmt = $pdo->prepare("SELECT classes.id, classes.name, classes.description, courses.course_name 
                       FROM classes 
                       JOIN courses ON classes.course_id = courses.id 
                       LIMIT :offset, :itemsPerPage");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
<div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Manage Classes</h1>

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

        <!-- Create New Class -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Add New Class</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">Course</label>
                    <select name="course_id" id="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" >
                        <option value="">Select a course</option> <!-- Add a placeholder option -->
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['id']); ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Class Name</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" >
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Class</button>
                </div>
            </form>
        </div>

        <!-- List All Classes -->
        <div class="bg-white shadow-md rounded p-4">
            <h2 class="text-xl font-semibold mb-4">Classes List</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border-collapse">
                    <thead>
                        <tr class="bg-gray-800 text-white text-left">
                            <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Name</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Course</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm">Description</th>
                            <th class="py-3 px-4 uppercase font-semibold text-sm flex gap-8">
                                <span>Change Course Name</span>
                                <span>Edit Class Name</span>
                                <span class="ml-16">Edit Description</span>
                                <span class="ml-12">Update</span>
                                <span class="">Delete</span>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classes as $class): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($class['id']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($class['name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($class['course_name']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($class['description']); ?></td>
                            <td class="border px-4 py-2">
                                <!-- Flex container for alignment -->
                                <div class="flex gap-2 items-end">
                                    <!-- Update Form -->
                                    <form method="POST" class="flex items-end">
                                        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class['id']); ?>">

                                        <div class="flex flex-col mr-4">
                                            <!-- <label for="course_id_<?php //echo htmlspecialchars($class['id']); ?>" class="block font-medium mb-1 uppercase">Change Course</label> -->
                                            <select id="course_id_<?php echo htmlspecialchars($class['id']); ?>" name="course_id" class="border px-2 py-1" required>
                                                <?php foreach ($courses as $course): ?>
                                                    <option value="<?php echo htmlspecialchars($course['id']); ?>" 
                                                        <?php echo (isset($class['course_id']) && $course['id'] == $class['course_id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($course['course_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="flex flex-col mr-4">
                                            <!-- <label for="name_<?php //echo htmlspecialchars($class['id']); ?>" class="block font-medium mb-1">Edit Class Name</label> -->
                                            <input type="text" id="name_<?php echo htmlspecialchars($class['id']); ?>" name="name" value="<?php echo htmlspecialchars($class['name']); ?>" class="border px-2 py-1" required>
                                        </div>

                                        <div class="flex flex-col mr-4">
                                            <!-- <label for="description_<?php //echo htmlspecialchars($class['id']); ?>" class="block font-medium mb-1">Edit Description</label> -->
                                            <textarea id="description_<?php echo htmlspecialchars($class['id']); ?>" name="description" rows="1" class="border px-2 py-1"><?php echo htmlspecialchars($class['description']); ?></textarea>
                                        </div>

                                        <!-- Update Button -->
                                        <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-2 rounded">Update</button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form method="POST" class="flex items-end">
                                        <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class['id']); ?>">
                                        <button type="submit" name="delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="mt-4 flex justify-between items-center">
                <div>
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Previous</a>
                    <?php endif; ?>
                </div>
                <div>
                    <span>Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                </div>
                <div>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


</body>
</html>





