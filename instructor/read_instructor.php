<?php
require 'Instructor.php';

// Instantiate Instructor class
$instructor = new Instructor();

// Get all instructors
$instructors = $instructor->getInstructors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Instructors</h1>
        <a href="create_instructor.php" class="bg-blue-500 text-white px-4 py-2 mb-4 rounded inline-block">Add New Instructor</a>
        
        <table class="table-auto w-full bg-white rounded shadow-md">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">First Name</th>
                    <th class="px-4 py-2">Last Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Department</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instructors as $instructor): ?>
                    <tr class="border-t hover:bg-gray-100">
                        <td class="px-4 py-2"><?= $instructor['first_name']; ?></td>
                        <td class="px-4 py-2"><?= $instructor['last_name']; ?></td>
                        <td class="px-4 py-2"><?= $instructor['email']; ?></td>
                        <td class="px-4 py-2"><?= $instructor['department_name']; ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_instructor.php?id=<?= $instructor['id']; ?>" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="delete_instructor.php?id=<?= $instructor['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Are you sure you want to delete this instructor?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
