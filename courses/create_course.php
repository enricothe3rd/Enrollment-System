<?php
require_once '../db/db_connection2.php'; // Ensure this path is correct
require_once 'Course.php'; // Ensure this path is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch form data
    $name = $_POST['name'];
    $department_id = $_POST['department_id'];

    // Create Course object and call create method
    $course = new Course();
    $course->create($name, $department_id);

    // Redirect after successful creation
    header('Location: read_courses.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Add New Course</h1>
        <form action="create_course.php" method="post">
            <label for="name" class="block text-gray-700">Course Name:</label>
            <input type="text" id="name" name="name" required class="mb-4 p-2 border rounded w-full">
            
            <label for="department_id" class="block text-gray-700">Department:</label>
            <select id="department_id" name="department_id" required class="mb-4 p-2 border rounded w-full">
                <?php
                // Fetch departments for the dropdown
                $pdo = Database::connect(); // Use the existing connection
                $stmt = $pdo->query('SELECT id, name FROM departments');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                }
                ?>
            </select>
            
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Course</button>
        </form>
    </div>
</body>
</html>
