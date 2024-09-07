<?php
require 'db_connection1.php';

// Initialize feedback variables
$message = '';
$messageType = '';
$customIcon = 'checked.png'; // Path to your custom icon

// Pagination settings
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Handle create/update/delete actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['create'])) {
            // Validate input
            $required_fields = ['section_id', 'code', 'subject_title', 'units', 'room', 'day', 'start_time', 'end_time'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception('All fields are required.');
                }
            }
            
            $section_id = $_POST['section_id'];
            $code = $_POST['code'];
            $subject_title = $_POST['subject_title'];
            $units = $_POST['units'];
            $room = $_POST['room'];
            $day = $_POST['day'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];

            $stmt = $pdo->prepare("INSERT INTO subjects (section_id, code, subject_title, units, room, day, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$section_id, $code, $subject_title, $units, $room, $day, $start_time, $end_time]);

            $message = 'Subject added successfully.';
            $messageType = 'success';
            $customIcon = '<img src="checked.png" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
        } elseif (isset($_POST['update'])) {
            // Validate input
            $required_fields = ['subject_id', 'section_id', 'code', 'subject_title', 'units', 'room', 'day', 'start_time', 'end_time'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception('All fields are required.');
                }
            }

            $subject_id = $_POST['subject_id'];
            $section_id = $_POST['section_id'];
            $code = $_POST['code'];
            $subject_title = $_POST['subject_title'];
            $units = $_POST['units'];
            $room = $_POST['room'];
            $day = $_POST['day'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];

            $stmt = $pdo->prepare("UPDATE subjects SET section_id = ?, code = ?, subject_title = ?, units = ?, room = ?, day = ?, start_time = ?, end_time = ? WHERE id = ?");
            $stmt->execute([$section_id, $code, $subject_title, $units, $room, $day, $start_time, $end_time, $subject_id]);

            $message = 'Subject updated successfully.';
            $messageType = 'success';
            $customIcon = '<img src="checked.png" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
        } elseif (isset($_POST['delete'])) {
            $subject_id = $_POST['subject_id'];
            $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
            $stmt->execute([$subject_id]);

            $message = 'Subject deleted successfully.';
            $messageType = 'success';
            $customIcon = '<img src="checked.png" alt="Success Icon" class="w-12 h-12 mx-auto mb-4">';
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
        $customIcon = '<img src="cancel.png" alt="Error Icon" class="w-12 h-12 mx-auto mb-4">';
    }
}

// Fetch paginated subjects
$stmt = $pdo->prepare("SELECT subjects.*, sections.section_name 
                       FROM subjects 
                       JOIN sections ON subjects.section_id = sections.id
                       LIMIT :offset, :items_per_page");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total number of subjects for pagination controls
$total_stmt = $pdo->query("SELECT COUNT(*) FROM subjects");
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Fetch all sections for dropdowns
$stmt = $pdo->query("SELECT * FROM sections");
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retain POST values for form fields
$input_values = [
    'section_id' => $_POST['section_id'] ?? '',
    'code' => $_POST['code'] ?? '',
    'subject_title' => $_POST['subject_title'] ?? '',
    'units' => $_POST['units'] ?? '',
    'room' => $_POST['room'] ?? '',
    'day' => $_POST['day'] ?? '',
    'start_time' => $_POST['start_time'] ?? '',
    'end_time' => $_POST['end_time'] ?? ''
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Manage Subjects</h1>

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

        <!-- Create New Subject -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Add New Subject</h2>
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="section_id">Section</label>
                    <select name="section_id" id="section_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo $section['id']; ?>" <?php if ($input_values['section_id'] == $section['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($section['section_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="code">Code</label>
                    <input type="text" name="code" id="code" value="<?php echo htmlspecialchars($input_values['code']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="subject_title">Subject Title</label>
                    <input type="text" name="subject_title" id="subject_title" value="<?php echo htmlspecialchars($input_values['subject_title']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="units">Units</label>
                    <input type="number" step="0.01" name="units" id="units" value="<?php echo htmlspecialchars($input_values['units']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="room">Room</label>
                    <input type="text" name="room" id="room" value="<?php echo htmlspecialchars($input_values['room']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="day">Day</label>
                    <input type="text" name="day" id="day" value="<?php echo htmlspecialchars($input_values['day']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_time">Start Time</label>
                    <input type="time" name="start_time" id="start_time" value="<?php echo htmlspecialchars($input_values['start_time']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_time">End Time</label>
                    <input type="time" name="end_time" id="end_time" value="<?php echo htmlspecialchars($input_values['end_time']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Subject</button>
                </div>
            </form>
        </div>

        <!-- List All Subjects -->
        <div class="bg-white shadow-md rounded p-4">
            <h2 class="text-xl font-semibold mb-4">Subjects List</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">ID</th>
                        <th class="py-2">Code</th>
                        <th class="py-2">Title</th>
                        <th class="py-2">Units</th>
                        <th class="py-2">Room</th>
                        <th class="py-2">Day</th>
                        <th class="py-2">Start Time</th>
                        <th class="py-2">End Time</th>
                        <th class="py-2">Section</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['id']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['code']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['subject_title']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['units']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['room']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['day']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['start_time']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['end_time']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['section_name']); ?></td>
                        <td class="border px-4 py-2">
                            <!-- Update Form -->
                            <form method="POST" class="inline">
                                <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                <select name="section_id" class="border px-2 py-1">
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?php echo $section['id']; ?>" <?php if ($section['id'] == $subject['section_id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($section['section_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="code" value="<?php echo htmlspecialchars($subject['code']); ?>" class="border px-2 py-1">
                                <input type="text" name="subject_title" value="<?php echo htmlspecialchars($subject['subject_title']); ?>" class="border px-2 py-1">
                                <input type="number" step="0.01" name="units" value="<?php echo htmlspecialchars($subject['units']); ?>" class="border px-2 py-1">
                                <input type="text" name="room" value="<?php echo htmlspecialchars($subject['room']); ?>" class="border px-2 py-1">
                                <input type="text" name="day" value="<?php echo htmlspecialchars($subject['day']); ?>" class="border px-2 py-1">
                                <input type="time" name="start_time" value="<?php echo htmlspecialchars($subject['start_time']); ?>" class="border px-2 py-1">
                                <input type="time" name="end_time" value="<?php echo htmlspecialchars($subject['end_time']); ?>" class="border px-2 py-1">
                                <button type="submit" name="update" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Update</button>
                            </form>
                            <!-- Delete Form -->
                            <form method="POST" class="inline">
                                <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                <button type="submit" name="delete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mt-4">
                <div>
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Previous</a>
                    <?php endif; ?>
                </div>
                <div>
                    Page <?php echo $page; ?> of <?php echo $total_pages; ?>
                </div>
                <div>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
