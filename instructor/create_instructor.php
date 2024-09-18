<?php
require 'Instructor.php';

// Instantiate Instructor class
$instructor = new Instructor();

// Handle create instructor request
$instructor->handleCreateInstructorRequest();

// Get departments for the department dropdown
$departments = $instructor->getDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Create Instructor</h1>

        <form method="POST" action="create_instructor.php" class="bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" id="department_id" class="mt-1 block w-full rounded border-gray-300" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Instructor</button>
            </div>
        </form>
    </div>
</body>
</html>
