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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-lg">
        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Add New Section</h1>
        <form action="create_section.php" method="post" class="space-y-4">
            <div>
                <label for="name" class="block text-red-700 font-medium">Section Name:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-chalkboard-teacher px-3 text-red-500"></i> <!-- Section icon -->
                    <input type="text" id="name" name="name" placeholder="Enter section name" required class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
            </div>
            <div>
                <label for="course_id" class="block text-red-700 font-medium">Course:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-book px-3 text-red-500"></i> <!-- Course icon -->
                    <select id="course_id" name="course_id" required class=" block w-full px-3 py-2 bg-red-50 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                        <option value="" disabled selected>Select a course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo htmlspecialchars($course['id']); ?>">
                                <?php echo htmlspecialchars($course['course_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 flex items-center justify-center">
                <i class="fas fa-plus-circle mr-2"></i> <!-- Plus icon -->
                Create Section
            </button>
        </form>
    </div>
</body>
</html>

<script>
    function goBack() {
        window.history.back(); // Navigates to the previous page
    }
</script>
