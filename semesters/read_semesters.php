<?php
require 'Semester.php';

$semester = new Semester();
$semesters = $semester->getSemesters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semesters List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto mt-10 bg-white p-8 shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-700">Semesters</h2>

        <div class="mb-4">
            <a href="create_semester.php" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Add Semester</a>
        </div>

        <table class="min-w-full bg-white border rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Semester Name</th>
                    <th class="py-3 px-6 text-left">Start Date</th>
                    <th class="py-3 px-6 text-left">End Date</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semesters as $semester) : ?>
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-3 px-6"><?= $semester['id'] ?></td>
                        <td class="py-3 px-6"><?= $semester['semester_name'] ?></td>
                        <td class="py-3 px-6"><?= $semester['start_date'] ?></td>
                        <td class="py-3 px-6"><?= $semester['end_date'] ?></td>
                        <td class="py-3 px-6 text-center">
                            <a href="edit_semester.php?id=<?= $semester['id'] ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Edit</a>
                            <a href="delete_semester.php?id=<?= $semester['id'] ?>" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
