<?php
require './db_connection1.php';

// Define paths to custom icons
$icons = [
    'success' => '../../assets/images/modal-icons/checked.png', // Path to your success icon
    'error' => '../../assets/images/modal-icons/cancel.png'     // Path to your error icon
];

// Initialize message variables
$message = '';
$messageType = '';
$customIcon = $icons['success']; // Default icon

// Handle create/update/delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $class_id = $_POST['class_id'] ?? null;
        $section_name = $_POST['section_name'] ?? '';

        if (!$class_id || !$section_name) {
            $message = 'Error: Both class and section name are required.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO sections (class_id, section_name) VALUES (?, ?)");
                $stmt->execute([$class_id, $section_name]);
                $message = 'Section added successfully.'; // Success message
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            } catch (PDOException $e) {
                $message = '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Error message
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } elseif (isset($_POST['update'])) {
        $section_id = $_POST['section_id'] ?? null;
        $class_id = $_POST['class_id'] ?? null;
        $section_name = $_POST['section_name'] ?? '';

        if (!$section_id || !$class_id || !$section_name) {
            $message = 'Error: Missing section ID, class ID, or section name.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
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
                    $customIcon = '<img src="' . $icons['error'] . '" alt="Warning Icon" class="w-12 h-12 mx-auto mb-4">';
                } else {
                    // Perform the update
                    $stmt = $pdo->prepare("UPDATE sections SET class_id = ?, section_name = ? WHERE id = ?");
                    $stmt->execute([$class_id, $section_name, $section_id]);
                    $message = 'Section updated successfully.'; // Success message
                    $messageType = 'success';
                    $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
                }
            } catch (PDOException $e) {
                $message = '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Error message
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
            }
        }
    } elseif (isset($_POST['delete'])) {
        $section_id = $_POST['section_id'] ?? null;

        if (!$section_id) {
            $message = 'Error: Missing section ID.';
            $messageType = 'error';
            $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM sections WHERE id = ?");
                $stmt->execute([$section_id]);
                $message = 'Section deleted successfully.'; // Success message
                $messageType = 'success';
                $customIcon = '<img src="' . $icons['success'] . '" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
            } catch (PDOException $e) {
                $message = '<p class="message">Error: ' . htmlspecialchars($e->getMessage()) . '</p>'; // Error message
                $messageType = 'error';
                $customIcon = '<img src="' . $icons['error'] . '" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
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
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Manage Sections</h1>
        
        <!-- Success/Error Modal -->
        <?php if ($message): ?>
        <div id="messageModal" class="fixed inset-0 flex items-center justify-center z-50 animate__animated <?php echo $messageType == 'success' ? 'animate__bounceIn' : 'animate__shakeX'; ?>">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center max-w-md w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
                <?php echo $customIcon; ?>
                <div class="<?php echo $messageType == 'success' ? 'text-green-500' : 'text-red-500'; ?> text-lg sm:text-xl font-semibold mb-4">
                    <span><?php echo htmlspecialchars($message); ?></span>
                </div>
                <button onclick="closeModal('messageModal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Close
                </button>
            </div>
        </div>

        <script>
            function closeModal(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }
            document.getElementById('messageModal').style.display = 'flex';
        </script>
        <?php endif; ?>

        <!-- Add New Section Modal -->
        <div id="addSectionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center max-w-md w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
                <h2 class="text-xl font-semibold mb-4">Add New Section</h2>
                <form method="POST" class="space-y-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="class_id">Class</label>
                        <select name="class_id" id="add_class_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select a class</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="section_name">Section Name</label>
                        <input type="text" name="section_name" id="add_section_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" name="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Section</button>
                        <button type="button" onclick="closeModal('addSectionModal')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Section Modal -->
        <div id="updateSectionModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center max-w-md w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
                <h2 class="text-xl font-semibold mb-4">Update Section</h2>
                <form id="updateForm" method="POST" class="space-y-4">
                    <input type="hidden" name="section_id" id="update_section_id">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="class_id">Class</label>
                        <select name="class_id" id="update_class_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="section_name">Section Name</label>
                        <input type="text" name="section_name" id="update_section_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Section</button>
                        <button type="button" onclick="closeModal('updateSectionModal')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Buttons to trigger modals -->
        <div class="mb-4">
            <button onclick="openModal('addSectionModal')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add New Section</button>
        </div>

        <!-- List All Sections -->
        <div class="bg-white shadow-md rounded p-4">
            <h2 class="text-xl font-semibold mb-4">Sections List</h2>
            <form id="deleteForm" method="POST" class="mb-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-800 text-white text-left">
                                <th class="py-3 px-4 uppercase font-semibold text-sm">
                                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll()">
                                </th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Section Name</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Class</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sections as $section): ?>
                            <tr>
                                <td class="border px-4 py-2">
                                    <input type="checkbox" name="section_ids[]" value="<?php echo $section['id']; ?>">
                                </td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($section['id']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($section['section_name']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($section['class_name']); ?></td>
                                <td class="border px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <!-- Update Button -->
                                        <button onclick="openUpdateModal(<?php echo $section['id']; ?>, '<?php echo htmlspecialchars($section['section_name']); ?>', <?php echo $section['class_id']; ?>)" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <button type="submit" name="delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete Selected</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openUpdateModal(id, name, classId) {
            document.getElementById('update_section_id').value = id;
            document.getElementById('update_section_name').value = name;
            document.getElementById('update_class_id').value = classId;
            openModal('updateSectionModal');
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('input[name="section_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }
    </script>
</body>
</html>

