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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-transparent font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md relative">
        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-700 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
        </button>

        <h1 class="text-3xl font-bold text-red-800 mb-6 text-center">Add New Department</h1>
        
        <form action="create_department.php" method="post" class="space-y-6">
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="name" class="text-red-700 font-medium mr-2"><i class="fas fa-building"></i></label>
                <input type="text" id="name" name="name" required placeholder="Department Name" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="established" class="text-red-700 font-medium mr-2"><i class="fas fa-calendar"></i></label>
                <input type="number" id="established" name="established" required placeholder="Established Year" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="dean" class="text-red-700 font-medium mr-2"><i class="fas fa-user-tie"></i></label>
                <input type="text" id="dean" name="dean" required placeholder="Dean" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="email" class="text-red-700 font-medium mr-2"><i class="fas fa-envelope"></i></label>
                <input type="email" id="email" name="email" required placeholder="Contact Email" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="phone" class="text-red-700 font-medium mr-2"><i class="fas fa-phone"></i></label>
                <input type="text" id="phone" name="phone" required placeholder="Phone" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="location" class="text-red-700 font-medium mr-2"><i class="fas fa-map-marker-alt"></i></label>
                <input type="text" id="location" name="location" required placeholder="Location" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400 ">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="student_count" class="text-red-700 font-medium mr-2"><i class="fas fa-users"></i></label>
                <input type="number" id="student_count" name="student_count" required placeholder="Number of Students" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500 placeholder-red-400">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Create Department
            </button>
        </form>
    </div>
    <script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
    </script>
</body>
</html>
