<?php
require '../db/db_connection3.php'; // Adjust the path as needed

$pdo = Database::connect();

// Initialize variables to hold course and semester IDs (these would come from your form)
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : null;
$semester_id = isset($_POST['semester_id']) ? $_POST['semester_id'] : null;

// Debugging: Output selected course and semester IDs
echo "Selected Course ID: " . htmlspecialchars($course_id) . "<br>";
echo "Selected Semester ID: " . htmlspecialchars($semester_id) . "<br>";

// Fetch sections if course ID and semester ID are provided
$sections = [];
if ($course_id && $semester_id) {
    // Fetch sections that have subjects for the selected course and semester
    $query = "
        SELECT s.id, s.name 
        FROM sections s
        JOIN subjects sub ON s.id = sub.section_id 
        WHERE s.course_id = ? AND sub.semester_id = ?
        GROUP BY s.id, s.name
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$course_id, $semester_id]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections and Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <div id="content" class="space-y-6">
        <?php if ($sections): ?>
            <?php foreach ($sections as $section): ?>
                <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold text-blue-600 mb-2"><?php echo htmlspecialchars($section['name']); ?></h3>

                    <div class="mb-4">
                        <?php
                        // Fetch subjects for the selected section and semester
                        $query = "SELECT id, code, title, units, price FROM subjects WHERE section_id = ? AND semester_id = ?";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$section['id'], $semester_id]);
                        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Debugging: Output the number of subjects fetched
                        echo "Subjects fetched for section ID " . htmlspecialchars($section['id']) . ": " . count($subjects) . "<br>";
                        ?>

                        <?php if ($subjects): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <div class="bg-white p-4 rounded shadow mb-4">
                                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Subject Details</h4>
                                    <div class="flex items-center flex-row flex-wrap gap-4">
                                        <strong>Code:</strong> <?php echo htmlspecialchars($subject['code']); ?><br>
                                        <strong>Title:</strong> <?php echo htmlspecialchars($subject['title']); ?><br>
                                        <strong>Units:</strong> <?php echo (int)$subject['units']; ?> units<br>
                                        <strong>Price:</strong> $<?php echo number_format($subject['price'], 2); ?><br>
                                    </div>
                                    <h5 class="mt-4 font-semibold">Schedule:</h5>
                                    <ul class="mt-2 text-sm text-gray-600">
                                        <?php
                                        // Fetch schedules for each subject
                                        $query = "SELECT day_of_week, start_time, end_time, room FROM schedules WHERE subject_id = ?";
                                        $stmt = $pdo->prepare($query);
                                        $stmt->execute([$subject['id']]);
                                        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($schedules): ?>
                                            <?php foreach ($schedules as $schedule): ?>
                                                <li class="flex justify-between">
                                                    <span><strong>Day:</strong> <?php echo htmlspecialchars($schedule['day_of_week']); ?></span>
                                                    <span><strong>Time:</strong> <?php echo htmlspecialchars($schedule['start_time']) . ' - ' . htmlspecialchars($schedule['end_time']); ?></span>
                                                    <span class="font-semibold"><strong>Room:</strong> <?php echo htmlspecialchars($schedule['room']); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>No schedule available</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No subjects available for this section in the selected semester.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No sections found for the selected course and semester.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
