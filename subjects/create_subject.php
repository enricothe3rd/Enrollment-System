<?php
require 'Subject.php';

$subject = new Subject();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle subject creation
    $subject->handleCreateSubjectRequest();
}

// Get all sections and semesters
$sections = $subject->getAllSections();
$semesters = $subject->getAllSemesters();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Add New Subject</h1>
        <form action="create_subject.php" method="post" class="space-y-4">
            <div>
                <label for="code" class="block text-gray-700 font-medium">Subject Code:</label>
                <input type="text" id="code" name="code" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="title" class="block text-gray-700 font-medium">Subject Title:</label>
                <input type="text" id="title" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="semester_id" class="block text-gray-700 font-medium">Semester:</label>
                <select id="semester_id" name="semester_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?php echo htmlspecialchars($semester['id']); ?>">
                            <?php echo htmlspecialchars($semester['semester_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="section_id" class="block text-gray-700 font-medium">Section:</label>
                <select id="section_id" name="section_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo htmlspecialchars($section['id']); ?>">
                            <?php echo htmlspecialchars($section['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="units" class="block text-gray-700 font-medium">Units:</label>
                <input type="number" id="units" name="units" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create Subject</button>
        </form>
    </div>
</body>
</html>
