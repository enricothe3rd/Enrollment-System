<?php
require 'Section.php';

// Create an instance of Section
$section = new Section();

// Fetch section data based on the provided ID in the URL
if (isset($_GET['id'])) {
    $sec = $section->getSectionById($_GET['id']);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section->handleUpdateSectionRequest();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Section</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Update Section</h1>

        <!-- Check if section data is available before rendering the form -->
        <?php if (isset($sec)) { ?>
            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($sec['id']); ?>">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($sec['name']); ?>" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div class="mb-4">
                    <label for="course_id" class="block text-gray-700">Course</label>
                    <select id="course_id" name="course_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <?php
                        // Fetch all courses for the dropdown
                        $courses = $section->getAllCourses();
                        foreach ($courses as $course) {
                            $selected = $course['id'] == $sec['course_id'] ? 'selected' : '';
                            echo "<option value=\"{$course['id']}\" $selected>{$course['course_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-150">Update</button>
            </form>
        <?php } else { ?>
            <p class="text-red-500">Invalid Section ID.</p>
        <?php } ?>
    </div>
</body>
</html>
