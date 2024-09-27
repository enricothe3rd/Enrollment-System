<?php
require 'Classroom.php';
$classroom = new Classroom();
$classrooms = $classroom->getClassrooms();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classrooms</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-transparent font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6">
        <h1 class="text-2xl font-semibold text-red-800 mb-6">Classrooms</h1>
        <a href="create_classroom.php" class="inline-block px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 mb-4">Add New Classroom</a>
        <table class="min-w-full border-collapse shadow-md rounded-lg">
            <thead class="bg-red-800">
                <tr>
                    <th class="py-4 px-4 border-b text-left text-white">Room Number</th>
                    <th class="py-4 px-4 border-b text-left text-white">Capacity</th>
                    <th class="py-4 px-4 border-b text-left text-white">Building</th>
                    <th class="py-4 px-4 border-b text-left text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classrooms as $classroom): ?>
                    <tr class="border-b bg-red-50 hover:bg-red-200">
                        <td class="border-t px-6 py-3"><?= htmlspecialchars($classroom['room_number']) ?></td>
                        <td class="border-t px-6 py-3"><?= htmlspecialchars($classroom['capacity']) ?></td>
                        <td class="border-t px-6 py-3"><?= htmlspecialchars($classroom['building']) ?></td>
                        <td class="border-t px-6 py-3 text-center">
                            <a href="edit_classroom.php?id=<?= htmlspecialchars($classroom['id']) ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                            <a href="delete_classroom.php?id=<?= htmlspecialchars($classroom['id']) ?>" onclick="return confirm('Are you sure?');" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

