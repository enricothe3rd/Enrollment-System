<?php
require 'Instructor.php';

// Instantiate Instructor class
$instructor = new Instructor();

// Get instructor by ID
if (isset($_GET['id'])) {
    $instructor_data = $instructor->getInstructorById($_GET['id']);
} else {
    header('Location: instructors.php');
    exit();
}

// Handle update instructor request
$instructor->handleUpdateInstructorRequest($_GET['id']);

// Get departments for the department dropdown
$departments = $instructor->getDepartments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Edit Instructor</h1>

        <form method="POST" action="edit_instructor.php?id=<?= $_GET['id']; ?>" class="bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= $instructor_data['first_name']; ?>" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="<?= $instructor_data['last_name']; ?>" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= $instructor_data['email']; ?>" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" id="department_id" class="mt-1 block w-full rounded border-gray-300" required>
                    <option value="" disabled>Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['id']; ?>" <?= $department['id'] == $instructor_data['department_id'] ? 'selected' : ''; ?>><?= $department['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update Instructor</button>
            </div>
        </form>
    </div>
</body>
</html>
