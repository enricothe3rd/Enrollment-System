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
<body>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Classrooms</h1>
        <a href="create_classroom.php" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 mb-4">Add New Classroom</a>
        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Room Number</th>
                    <th class="py-2 px-4 border-b">Capacity</th>
                    <th class="py-2 px-4 border-b">Building</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classrooms as $classroom): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($classroom['room_number']) ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($classroom['capacity']) ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($classroom['building']) ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="edit_classroom.php?id=<?= $classroom['id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                            |
                            <a href="delete_classroom.php?id=<?= $classroom['id'] ?>" class="text-red-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
