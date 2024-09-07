<?php
require './db_connection1.php';

// Initialize message variables
$message = '';
$messageType = '';
$customIcon = 'checked.png'; // Path to your custom icon

// Handle create/update/delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $class_id = $_POST['class_id'] ?? null;
        $section_name = $_POST['section_name'] ?? '';

        if (!$class_id || !$section_name) {
            $message = 'Error: Both class and section name are required.';
            $messageType = 'error';
            $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO sections (class_id, section_name) VALUES (?, ?)");
                $stmt->execute([$class_id, $section_name]);
                $message = 'Section added successfully.'; // Success message
                $messageType = 'success';
                $customIcon = '<img src="checked.png" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            } catch (PDOException $e) {
                $message = '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Error message
                $messageType = 'error';
                $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } elseif (isset($_POST['update'])) {
        $section_id = $_POST['section_id'] ?? null;
        $class_id = $_POST['class_id'] ?? null;
        $section_name = $_POST['section_name'] ?? '';

        if (!$section_id || !$class_id || !$section_name) {
            $message = 'Error: Missing section ID, class ID, or section name.';
            $messageType = 'error';
            $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                // Fetch the current values from the database
                $stmt = $pdo->prepare("SELECT class_id, section_name FROM sections WHERE id = ?");
                $stmt->execute([$section_id]);
                $current = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if there are any changes
                if ($current['class_id'] == $class_id && $current['section_name'] == $section_name) {
                    $message = 'No changes detected. Please enter a new section name before updating.';
                    $messageType = 'error';
                    $customIcon = '<img src="cancel.png" alt="Warning Icon" class="w-12 h-12 mx-auto mb-4">';
                } else {
                    // Perform the update
                    $stmt = $pdo->prepare("UPDATE sections SET class_id = ?, section_name = ? WHERE id = ?");
                    $stmt->execute([$class_id, $section_name, $section_id]);
                    $message = 'Section updated successfully.'; // Success message
                    $messageType = 'success';
                    $customIcon = '<img src="checked.png" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
                }
            } catch (PDOException $e) {
                $message = '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Error message
                $messageType = 'error';
                $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } elseif (isset($_POST['delete'])) {
        $section_id = $_POST['section_id'] ?? null;

        if (!$section_id) {
            $message = 'Error: Missing section ID.';
            $messageType = 'error';
            $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM sections WHERE id = ?");
                $stmt->execute([$section_id]);
                $message = 'Section deleted successfully.'; // Success message
                $messageType = 'success';
                $customIcon = '<img src="checked.png" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            } catch (PDOException $e) {
                $message = '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Error message
                $messageType = 'error';
                $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    }
}

// Fetch all sections and classes for dropdowns
$stmt = $pdo->query("SELECT sections.id, sections.section_name, classes.name AS class_name, sections.class_id 
                     FROM sections 
                     JOIN classes ON sections.class_id = classes.id");
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sections</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Manage Sections</h1>
        
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

        <!-- Create New Section -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Add New Section</h2>
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="class_id">Class</label>
                    <select name="class_id" id="class_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select a class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="section_name">Section Name</label>
                    <input type="text" name="section_name" id="section_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Section</button>
                </div>
            </form>
        </div>

        <!-- List All Sections -->
        <div class="bg-white shadow-md rounded p-4">
            <h2 class="text-xl font-semibold mb-4">Sections List</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">ID</th>
                        <th class="py-2">Section Name</th>
                        <th class="py-2">Class</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sections as $section): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($section['id']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($section['section_name']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($section['class_name']); ?></td>
                        <td class="border px-4 py-2">
                            <!-- Update Form -->
                            <form method="POST" class="inline">
                                <input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">
                                <select name="class_id" class="border px-2 py-1">
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?php echo $class['id']; ?>" <?php if ($class['id'] == $section['class_id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($class['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="section_name" value="<?php echo htmlspecialchars($section['section_name']); ?>" class="border px-2 py-1">
                                <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Update</button>
                            </form>
                            <!-- Delete Form -->
                            <form method="POST" class="inline">
                                <input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">
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
