<?php
session_start();
require 'session_timeout.php';
require 'db/db_connection1.php';

// // Check if user is logged in and has the correct role
// if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: index.php"); // Redirect to login page
//     exit(); // Stop further execution
// }

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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Status Options</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-6">Manage Status Options</h2>
    <form method="POST" class="mb-6">
        <input type="hidden" name="action" value="create">
        <input type="text" name="status_name" placeholder="New Status" class="border border-gray-300 rounded-lg px-3 py-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700">Add</button>
    </form>
    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($status_options as $status_option): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($status_option['id']); ?>">
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($status_option['id']); ?></td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="status_name" value="<?php echo htmlspecialchars($status_option['status_name']); ?>" class="border border-gray-300 rounded-lg px-3 py-2">
                        </td>
                        <td class="py-2 px-4 border-b">
                            <button type="submit" name="action" value="update" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-700">Update</button>
                            <button type="submit" name="action" value="delete" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-red-700">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
