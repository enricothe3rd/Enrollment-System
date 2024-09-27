<?php
require 'Classroom.php';
$classroom = new Classroom();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $currentClassroom = $classroom->getClassroomById($id);
    if (!$currentClassroom) {
        echo 'Classroom not found!';
        exit();
    }
    $classroom->handleUpdateClassroomRequest($id);
} else {
    echo 'No ID specified!';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Classroom</title>
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
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Edit Classroom</h1>
        <form action="edit_classroom.php?id=<?= $currentClassroom['id'] ?>" method="POST" class="space-y-4">
            <div class="mb-4">
                <label for="room_number" class="block text-red-700 font-medium">Room Number</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm focus-within:border-red-500">
                    <div class="px-4 py-2">
                        <i class="fas fa-door-open text-red-500"></i>
                    </div>
                    <input type="text" id="room_number" name="room_number" value="<?= $currentClassroom['room_number'] ?>" required class="w-full px-4 py-2 border-0 focus:outline-none">
                </div>
            </div>
            <div class="mb-4">
                <label for="capacity" class="block text-red-700 font-medium">Capacity</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm focus-within:border-red-500">
                    <span class="px-3 text-gray-500">
                        <i class="fas fa-users text-red-500"></i>
                    </span>
                    <input type="number" id="capacity" name="capacity" value="<?= $currentClassroom['capacity'] ?>" required class="w-full px-4 py-2 border-0 focus:outline-none">
                </div>
            </div>
            <div class="mb-4">
                <label for="building" class="block text-red-700 font-medium">Building</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm focus-within:border-red-500">
                    <span class="px-3 text-gray-500">
                        <i class="fas fa-building text-red-500"></i>
                    </span>
                    <input type="text" id="building" name="building" value="<?= $currentClassroom['building'] ?>" required class="w-full px-4 py-2 border-0 focus:outline-none">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800">Update Classroom</button>
        </form>
    </div>

    <script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
    </script>
</body>
</html>
