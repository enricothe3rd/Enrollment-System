<?php
session_start();
require 'db/db_connection1.php';

// Check if user is logged in and their role is either 'student' or 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'student' && $_SESSION['user_role'] !== 'admin')) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$selected_class_id = $_SESSION['selected_class_id'] ?? null;
$selected_subjects = $_SESSION['selected_subjects'] ?? [];

$sections = [];
if ($selected_class_id) {
    try {
        // Fetch the selected class details
        $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$selected_class_id]);
        $class = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($class) {
            // Fetch sections and subjects related to the selected class
            // Existing query logic for fetching sections and subjects
            // Make sure to populate $sections array with the fetched data
        } else {
            echo '<p class="text-red-500">Class not found.</p>';
        }
    } catch (PDOException $e) {
        echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}

// Handle final enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_enrollment'])) {
    if (!empty($selected_subjects)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO subject_enrollments (student_id, subject_id) VALUES (?, ?)");

            foreach ($selected_subjects as $subject_id) {
                $stmt->execute([$user_id, $subject_id]);
            }

            $pdo->commit();
            echo '<p class="text-green-500">Enrollment successful!</p>';
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<p class="text-red-500">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    } else {
        echo '<p class="text-red-500">No subjects selected.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Preview Enrollment</h1>

        <?php if ($selected_class_id && $sections): ?>
            <form method="POST" class="bg-white shadow-md rounded p-4 mt-4">
                <h2 class="text-xl font-semibold mb-4">Class: <?php echo htmlspecialchars($class['name']); ?></h2>

                <?php foreach ($sections as $section_id => $section): ?>
                    <div class="section-container mb-4">
                        <h3 class="text-lg font-semibold">
                            Section: <?php echo htmlspecialchars($section['section_name']); ?>
                        </h3>
                        <table class="min-w-full bg-white mt-2">
                            <thead>
                                <tr>
                                    <th class="py-2">Code</th>
                                    <th class="py-2">Subject Title</th>
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
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['code']); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($subject['subject_title']); ?></td>
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

                <div class="flex items-center justify-between mt-4">
                    <button type="submit" name="confirm_enrollment" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Confirm Enrollment</button>
                    <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Go Back</a>
                </div>
            </form>
        <?php else: ?>
            <p class="text-red-500">No class or sections available.</p>
        <?php endif; ?>

    </div>
</body>
</html>
