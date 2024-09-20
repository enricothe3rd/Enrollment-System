<?php
require 'Department.php';

$department = new Department();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $established = $_POST['established'];
    $dean = $_POST['dean'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    // Get the faculty count from the department
    $faculty_count = $department->getFacultyCountByDepartment($id); // Use department ID instead of name

    // Update the department
    $department->update($id, $name, $established, $dean, $email, $phone, $location, $faculty_count);
    header('Location: read_departments.php');
    exit(); // Ensure no further code is executed after redirection
} else {
    // Check if 'id' is set in the URL query parameters
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];
        $dept = $department->find($id);

        // Check if department was found
        if (!$dept) {
            echo 'Department not found.';
            exit();
        }
    } else {
        echo 'No department ID provided.';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Department</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Update Department</h1>
        <form action="update_department.php" method="post" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($dept['id']); ?>">
            <div>
                <label for="name" class="block text-gray-700 font-medium">Department Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($dept['name']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="established" class="block text-gray-700 font-medium">Established:</label>
                <input type="number" id="established" name="established" value="<?php echo htmlspecialchars($dept['established']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="dean" class="block text-gray-700 font-medium">Dean:</label>
                <input type="text" id="dean" name="dean" value="<?php echo htmlspecialchars($dept['dean']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($dept['email']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="phone" class="block text-gray-700 font-medium">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($dept['phone']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="location" class="block text-gray-700 font-medium">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($dept['location']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="student_count" class="block text-gray-700 font-medium">Student Count:</label>
                <input type="number" id="student_count" name="student_count" value="<?php echo htmlspecialchars($dept['student_count']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update Department</button>
        </form>
    </div>
</body>
</html>
