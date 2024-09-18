<?php
require 'Semester.php';

$semester = new Semester();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $semester_data = $semester->getSemesterById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $semester->handleUpdateSemesterRequest($id);
    }
} else {
    header('Location: read_semesters.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Semester</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-700">Edit Semester</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label for="semester_name" class="block text-gray-700 font-medium">Semester Name</label>
                <input type="text" name="semester_name" id="semester_name" value="<?= $semester_data['semester_name'] ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>

            <div>
                <label for="start_date" class="block text-gray-700 font-medium">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="<?= $semester_data['start_date'] ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>

            <div>
                <label for="end_date" class="block text-gray-700 font-medium">End Date</label>
                <input type="date" name="end_date" id="end_date" value="<?= $semester_data['end_date'] ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">Update Semester</button>
            </div>
        </form>
    </div>

</body>
</html>
