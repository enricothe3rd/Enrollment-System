<?php

require 'Section.php';

// Create an instance of the Section class
$section = new Section();

// Fetch all sections
$sections = $section->getAllSections();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Sections</h1>
        <a href="create_section.php" class="inline-block mb-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">Add New Section</a>
        <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="px-4 py-2 border-b text-left text-xs font-medium uppercase tracking-wider">ID</th>
                    <th class="px-4 py-2 border-b text-left text-xs font-medium uppercase tracking-wider">Name</th>
                    <th class="px-4 py-2 border-b text-left text-xs font-medium uppercase tracking-wider">Course</th>
                    <th class="px-4 py-2 border-b text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php foreach ($sections as $sec): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2"><?php echo htmlspecialchars($sec['id'] ?? ''); ?></td>
                    <td class="px-4 py-2"><?php echo htmlspecialchars($sec['name'] ?? ''); ?></td>
                    <td class="border-t px-6 py-4"><?= htmlspecialchars($section->getCourseName($sec['course_id'])) ?></td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="update_section.php?id=<?php echo $sec['id']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                        <a href="delete_section.php?id=<?php echo $sec['id']; ?>" onclick="return confirm('Are you sure you want to delete this section?');" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
