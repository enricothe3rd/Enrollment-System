<?php
require 'Subject.php';

$subject = new Subject();
$subjects = $subject->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects and Amounts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> <!-- Updated CDN URL -->
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Subjects and Amounts</h1>
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Code</th>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Section ID</th>
                    <th class="py-2 px-4 border-b">Units</th>
                    <th class="py-2 px-4 border-b">Semester ID</th>
                    <th class="py-2 px-4 border-b">Price</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['id']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['code']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['title']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['section_id']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['units']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['semester_id']); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($subject['price']); ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="update_amount.php?id=<?php echo htmlspecialchars($subject['id']); ?>" class="text-blue-500 hover:underline">Edit</a>
                            <a href="delete_amount.php?id=<?php echo htmlspecialchars($subject['id']); ?>" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="create_amount.php" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Add New Subject</a>
    </div>
</body>
</html>
