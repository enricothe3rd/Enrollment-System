<?php
require 'Section.php';

// Create an instance of Section
$section = new Section();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle section creation
    $section->handleCreateSectionRequest();
}

// Fetch all courses for the dropdown
$courses = $section->getAllCourses(); // Fetch all courses using the method from Section class
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Section</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Add New Section</h1>
        <form action="create_section.php" method="post" class="space-y-4">
            <div>
                <label for="name" class="block text-gray-700 font-medium">Section Name:</label>
                <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="course_id" class="block text-gray-700 font-medium">Course:</label>
                <select id="course_id" name="course_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="" disabled selected>Select a course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create Section</button>
        </form>
    </div>
</body>
</html>
