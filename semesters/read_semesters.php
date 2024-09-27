<?php
require 'Semester.php';

$semester = new Semester();
$semesters = $semester->getSemesters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semesters List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-transparent font-sans leading-normal tracking-normal">

    <div class="container mx-auto mt-10 p-6 ">
        <h2 class="text-2xl font-semibold text-red-800 mb-6">Semesters</h2>

        <div class="mb-4">
            <a href="create_semester.php" class="inline-block px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800">Add Semester</a>
        </div>

        <table class="min-w-full border-collapset shadow-md rounded-lg">
            <thead class="bg-red-800">
                <tr>
                    <th class="px-4 py-4 border-b text-left text-white">ID</th>
                    <th class="px-4 py-4 border-b text-left text-white">Semester Name</th>
                    <th class="px-4 py-4 border-b text-left text-white">Start Date</th>
                    <th class="px-4 py-4 border-b text-left text-white">End Date</th>
                    <th class="px-4 py-4 border-b text-left text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($semesters as $semester) : ?>
                    <tr class="border-b bg-red-50 hover:bg-red-200">
                        <td class="border-t px-6 py-3"><?php echo htmlspecialchars($semester['id']); ?></td>
                        <td class="border-t px-6 py-3"><?php echo htmlspecialchars($semester['semester_name']); ?></td>
                        <td class="border-t px-6 py-3"><?php echo htmlspecialchars($semester['start_date']); ?></td>
                        <td class="border-t px-6 py-3"><?php echo htmlspecialchars($semester['end_date']); ?></td>
                        <td class="border-t px-6 py-3 text-center">
                            <a href="edit_semester.php?id=<?php echo htmlspecialchars($semester['id']); ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                            <a href="delete_semester.php?id=<?php echo htmlspecialchars($semester['id']); ?>" onclick="return confirm('Are you sure?');" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
