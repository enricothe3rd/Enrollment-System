<?php
require 'Instructor.php';

// Get the PDO connection
$pdo = Database::connect();

// Create an instance of the Instructor class
$instructor = new Instructor($pdo);

// Fetch all instructors
$instructors = $instructor->readAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor List</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<div class="container mx-auto mt-10 p-6">
    <div class="bg-transparent overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-red-800 mb-4">Instructor List</h1>

            <!-- Create Instructor Button -->
            <a href="create_instructor.php" class="inline-block mb-4 px-4 py-4 bg-red-700 text-white rounded hover:bg-red-800">
                + Create Instructor
            </a>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <!-- Table -->
                <table class="min-w-full border-collapse border border-gray-200 shadow-lg rounded-lg">
                    <thead class="bg-red-800 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">First Name</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Middle Name</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Last Name</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Suffix</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Department Name</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Course Name</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Section Name</th>
                            <th class="px-6 py-4 text-left font-medium uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-red-50 divide-y divide-gray-200">
                        <?php foreach ($instructors as $row): ?>
                            <tr class="hover:bg-red-200 transition duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['first_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['middle_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['last_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['suffix']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['department_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['course_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500"><?= htmlspecialchars($row['section_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 flex space-x-2">
                                    <a href="edit_instructor.php?id=<?= htmlspecialchars($row['id']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                                    <a href="delete_instructor.php?id=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Are you sure you want to delete this instructor?');" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-2 rounded transition duration-150">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
