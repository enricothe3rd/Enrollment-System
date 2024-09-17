<?php
require 'Course.php';

$course = new Course();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $course->update($id, $name);
    header('Location: read_courses.php');
    exit();
} else {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $courseData = $course->find($id);

        if (!$courseData) {
            echo 'Course not found.';
            exit();
        }
    } else {
        echo 'No course ID provided.';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Update Course</h1>
        <form action="update_course.php" method="post" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($courseData['id']); ?>">
            <div>
                <label for="name" class="block text-gray-700 font-medium">Course Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($courseData['name']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update Course</button>
        </form>
    </div>
</body>
</html>
