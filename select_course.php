<?php
session_start();
require 'db/db_connection1.php';

// Check if user is logged in and their role is either 'student' or 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'student' && $_SESSION['user_role'] !== 'admin')) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Initialize variables
$selected_class_id = null;
$sections = [];
$subjects = [];
$selected_subjects = [];

// Handle class selection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_class'])) {
    $selected_class_id = $_POST['class_id'] ?? null;
    
    if ($selected_class_id) {
        try {
            // Fetch the selected class details
            $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
            $stmt->execute([$selected_class_id]);
            $class = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($class) {
                // Fetch sections and subjects related to the selected class
                $stmt = $pdo->prepare("
                    SELECT s.id AS subject_id, s.subject_title, s.code, s.units, s.room, s.day, s.start_time, s.end_time,
                           sec.id AS section_id, sec.section_name
                    FROM subjects s
                    JOIN sections sec ON s.section_id = sec.id
                    WHERE sec.class_id = ?
                ");
                $stmt->execute([$selected_class_id]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Organize sections and subjects
                foreach ($results as $row) {
                    $section_id = $row['section_id'];
                    $subject_id = $row['subject_id'];

                    if (!isset($sections[$section_id])) {
                        $sections[$section_id] = [
                            'section_name' => $row['section_name'],
                            'subjects' => []
                        ];
                    }

                    $sections[$section_id]['subjects'][] = [
                        'id' => $subject_id,
                        'subject_title' => $row['subject_title'],
                        'code' => $row['code'],
                        'units' => $row['units'],
                        'room' => $row['room'],
                        'day' => $row['day'],
                        'start_time' => $row['start_time'],
                        'end_time' => $row['end_time']
                    ];
                }
            } else {
                echo '<p class="text-red-500">Class not found.</p>';
            }
        } catch (PDOException $e) {
            echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}

// Handle subject selection and enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
    $selected_subjects = $_POST['subjects'] ?? [];

    if (!empty($selected_subjects)) {
        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Prepare the insert statement for subject enrollments
            $stmt = $pdo->prepare("INSERT INTO subject_enrollments (student_id, subject_id) VALUES (?, ?)");

            // Insert each selected subject for the logged-in user
            foreach ($selected_subjects as $subject_id) {
                $stmt->execute([$user_id, $subject_id]);
            }

            // Commit the transaction
            $pdo->commit();

            echo '<p class="text-green-500">Enrollment successful!</p>';
        } catch (PDOException $e) {
            // Rollback in case of an error
            $pdo->rollBack();
            echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    } else {
        echo '<p class="text-red-500">No subjects selected.</p>';
    }
}

// Fetch all classes for selection
$stmt = $pdo->query("SELECT * FROM classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4 ">
        <h1 class="text-2xl font-bold mb-4">Select a Class</h1>

        <!-- Class Selection Form -->
        <form method="POST" class=" px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="class_id">Choose Class</label>
                <select name="class_id" id="class_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select a class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo htmlspecialchars($class['id']); ?>" <?php echo $selected_class_id == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($class['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" name="select_class" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Select Class</button>
            </div>
        </form>

        <?php if ($selected_class_id && $sections): ?>
            <!-- Display Sections and Subjects -->
            <form method="POST" class="bg-white shadow-md rounded p-4 mt-4">
                <h2 class="text-xl font-semibold mb-4">Sections and Subjects for Selected Class</h2>
                <?php foreach ($sections as $section_id => $section): ?>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">Section: <?php echo htmlspecialchars($section['section_name']); ?></h3>
                        <table class="min-w-full bg-white mt-2">
                            <thead>
                                <tr>
                                    <th class="py-2">Select</th>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Subject Title</th>
                                    <th class="py-2">Code</th>
                                    <th class="py-2">Units</th>
                                    <th class="py-2">Room</th>
                                    <th class="py-2">Day</th>
                                    <th class="py-2">Start Time</th>
                                    <th class="py-2">End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($section['subjects'] as $subject): ?>
                                <tr>
                                    <td class="border px-4 py-2">
                                        <input type="checkbox" name="subjects[]" value="<?php echo htmlspecialchars($subject['id']); ?>">
                                    </td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['id']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['subject_title']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['code']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['units']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['room']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['day']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['start_time']); ?></td>
                                    <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['end_time']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                <div class="flex items-center justify-between">
                    <button type="submit" name="enroll" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Enroll</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
