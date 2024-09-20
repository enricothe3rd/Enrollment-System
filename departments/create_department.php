<?php
require 'Department.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $established = $_POST['established'];
    $dean = $_POST['dean'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    // Create a new department instance
    $department = new Department();

    // Get the faculty count from the instructors table
    $faculty_count = $department->getFacultyCountByDepartment($name); // Assuming you have a method to fetch this count

    // Create the department
    $department->create($name, $established, $dean, $email, $phone, $location, $faculty_count);
    header('Location: read_departments.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Department</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Add New Department</h1>
        <form action="create_department.php" method="post" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700 font-medium">Department Name:</label>
                <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="established" class="block text-gray-700 font-medium">Established Year:</label>
                <input type="number" id="established" name="established" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="dean" class="block text-gray-700 font-medium">Dean:</label>
                <input type="text" id="dean" name="dean" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="email" class="block text-gray-700 font-medium">Contact Email:</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="phone" class="block text-gray-700 font-medium">Phone:</label>
                <input type="text" id="phone" name="phone" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="location" class="block text-gray-700 font-medium">Location:</label>
                <input type="text" id="location" name="location" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="student_count" class="block text-gray-700 font-medium">Number of Students:</label>
                <input type="number" id="student_count" name="student_count" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create Department</button>
        </form>
    </div>
</body>
</html>
