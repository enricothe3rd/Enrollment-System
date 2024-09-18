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
</head>
<body>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Classroom</h1>
        <form action="edit_classroom.php?id=<?= $currentClassroom['id'] ?>" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
                <input type="text" id="room_number" name="room_number" value="<?= $currentClassroom['room_number'] ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                <input type="number" id="capacity" name="capacity" value="<?= $currentClassroom['capacity'] ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="building" class="block text-sm font-medium text-gray-700">Building</label>
                <input type="text" id="building" name="building" value="<?= $currentClassroom['building'] ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Update Classroom</button>
        </form>
    </div>
</body>
</html>
