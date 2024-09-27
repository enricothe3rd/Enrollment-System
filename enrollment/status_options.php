<?php
session_start();
// require 'session_timeout.php';
// require 'session_timeout.php';
require '../db/db_connection3.php';

// // Check if user is logged in and has the correct role
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: index.php"); // Redirect to login page
//     exit(); // Stop further execution
// }
$pdo = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $status_name = filter_input(INPUT_POST, 'status_name', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        switch ($action) {
            case 'create':
                $sql = "INSERT INTO status_options (status_name) VALUES (:status_name)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':status_name' => $status_name]);
                break;
            case 'update':
                $sql = "UPDATE status_options SET status_name = :status_name WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':status_name' => $status_name, ':id' => $id]);
                break;
            case 'delete':
                $sql = "DELETE FROM status_options WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                break;
        }
    }
}

// Fetch all status options
$sql = "SELECT * FROM status_options";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$status_options = $stmt->fetchAll(PDO::FETCH_ASSOC);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Status Options</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-2xl font-semibold text-red-800 mb-4">Manage Status Options</h2>

        <!-- Form to add new status option -->
        <form method="POST" class="mb-6 flex space-x-4">
            <input type="hidden" name="action" value="create">
            <input type="text" name="status_name" placeholder="New Status" class="border border-red-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-red-300">
            <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-red-700 transition duration-300">Add</button>
        </form>

        <!-- Status Options Table -->
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
            <thead class="bg-red-700 text-white uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($status_options as $status_option): ?>
                <tr class="border-b bg-red-50 hover:bg-red-200">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($status_option['id']); ?>">
                        <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($status_option['id']); ?></td>
                        <td class="py-3 px-6 border-b">
                            <input type="text" name="status_name" value="<?php echo htmlspecialchars($status_option['status_name']); ?>" class="border border-red-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-red-300">
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                <button type="submit" name="action" value="update" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-700 transition duration-300">Update</button>
                                <button type="submit" name="action" value="delete" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-red-700 transition duration-300">Delete</button>
                            </div>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
