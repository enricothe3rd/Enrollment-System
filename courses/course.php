<?php
require 'Section.php';
require 'Course.php'; // Include the Course class

$course = new Course();
$courses = $course->read(); // Fetch all courses

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $course_id = $_POST['course_id'];

    $section = new Section();
    $section->create($name, $course_id);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Section</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Create Section</h1>

        <form action="create_section.php" method="post" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Section Name:</label>
                <input type="text" id="name" name="name" class="border border-gray-300 p-2 w-full rounded" required>
            </div>
            <div class="mb-4">
                <label for="course_id" class="block text-gray-700">Select Course:</label>
                <select id="course_id" name="course_id" class="border border-gray-300 p-2 w-full rounded" required>
                    <option value="" disabled selected>Select a course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Section</button>
        </form>
    </div>
</body>
</html>
