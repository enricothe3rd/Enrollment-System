<?php
require 'Semester.php';

$semester = new Semester();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester->handleCreateSemesterRequest();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Semester</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 shadow-lg rounded-lg">
       
    <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        
    <h1 class="text-2xl font-semibold text-red-800 mb-4">Create New Semester</h1>

        <form method="POST" class="space-y-4">
            <div>
                <label for="semester_name" class="block text-red-700 font-medium">Semester Name</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm focus-within:border-red-500">
                    <div class="px-4 py-2">
                        <i class="fas fa-calendar-alt  text-red-500"></i>
                    </div>
                    <input type="text" name="semester_name" id="semester_name" placeholder="Enter Semester Name" class="w-full px-4 py-2 border-0 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label for="start_date" class="block text-red-700 font-medium">Start Date</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm focus-within:border-red-500">
                    <div class="px-4 py-2">
                        <i class="fas fa-calendar-day  text-red-500"></i>
                    </div>
                    <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2 border-0 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label for="end_date" class="block text-red-700 font-medium">End Date</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm focus-within:border-red-500">
                    <div class="px-4 py-2">
                        <i class="fas fa-calendar-alt  text-red-500"></i>
                    </div>
                    <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2 border-0 focus:outline-none" required>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800 focus:outline-none">Create Semester</button>
            </div>
        </form>
    </div>

</body>
</html>

<script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
    </script>