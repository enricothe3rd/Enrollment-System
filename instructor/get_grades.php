<?php
session_start(); // Start the session

// Include the Database class
require_once '../db/db_connection3.php';

$student_number = $_SESSION['student_number'] ?? null;

$pdo = Database::connect();
$grades = [];

if ($student_number) {
    // Fetch the grades and join with the subjects table to get subject code
    $sql = "SELECT 
    subjects.code AS subject_code, 
    subjects.units AS units,  -- Add units here
    grades.grade, 
    grades.prelim, 
    grades.midterm, 
    grades.finals, 
    grades.total_grade, 
    grades.created_at 
FROM grades 
JOIN subjects ON grades.subject_id = subjects.id 
WHERE grades.student_number = :student_number";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Initialize variables for GWA calculation
$totalUnits = 0;           // Total units for GWA calculation
$weightedGradesSum = 0;    // Sum of weighted grades
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4 text-center uppercase text-red-800">My Grades</h1>

    <?php if ($grades): ?>
        <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg "> <!-- Added border-separate and border-spacing-2 -->
    <thead class="bg-gray-200">
        <tr class="bg-red-800">
            <th class="px-4 py-4 border-b text-left text-white">Subject Code</th>
            <th class="px-4 py-4 border-b text-left text-white">Units</th>
            <th class="px-4 py-4 border-b text-left text-white">Prelim</th>
            <th class="px-4 py-4 border-b text-left text-white">Midterm</th>
            <th class="px-4 py-4 border-b text-left text-white">Finals</th>
            <th class="px-4 py-4 border-b text-left text-white">Total Grade</th>
            <th class="px-4 py-4 border-b text-left text-white">Date Added</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grades as $grade): ?>
            <?php
            // Update total units and weighted grades
            $totalUnits += $grade['units'];
            $weightedGradesSum += $grade['total_grade'] * $grade['units']; // Assuming total_grade is the final grade
            ?>
            <tr class="border-b bg-red-50 hover:bg-red-200 block sm:table-row">
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['subject_code']); ?></td>
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['units']); ?></td>
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['prelim']); ?></td>
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['midterm']); ?></td>
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['finals']); ?></td>
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['total_grade']); ?></td>
                <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($grade['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <?php
        // Calculate GWA
        $gwa = ($totalUnits > 0) ? ($weightedGradesSum / $totalUnits) : 0; // Avoid division by zero
        ?>
        <tr class="bg-red-100">
            <td colspan="5" class="border-b border-gray-300 py-2 px-4 text-right font-bold text-black">GWA:</td>
            <td class="border-b border-gray-300 py-2 px-4 font-bold text-black"><?php echo htmlspecialchars(number_format($gwa, 2)); ?></td>
            <td class="border-b border-gray-300 py-2 px-4"></td> <!-- Empty cell for alignment -->
        </tr>
    </tfoot>
</table>

        </div>
    <?php else: ?>
        <p class="text-center text-red-500">No grades found for this student.</p>
    <?php endif; ?>
</body>
</html>
