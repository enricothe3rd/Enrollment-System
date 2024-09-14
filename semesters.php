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
        $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        switch ($action) {
            case 'create':
                $sql = "INSERT INTO semesters (semester) VALUES (:semester)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':semester' => $semester]);
                break;
            case 'update':
                $sql = "UPDATE semesters SET semester = :semester WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':semester' => $semester, ':id' => $id]);
                break;
            case 'delete':
                $sql = "DELETE FROM semesters WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                break;
        }
    }
}

// Fetch all semesters
$sql = "SELECT * FROM semesters";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Semesters</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-6">Manage Semesters</h2>
    <form method="POST" class="mb-6">
        <input type="hidden" name="action" value="create">
        <input type="text" name="semester" placeholder="New Semester" class="border border-gray-300 rounded-lg px-3 py-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700">Add</button>
    </form>
    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Semester</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($semesters as $semester): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($semester['id']); ?>">
                        <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($semester['id']); ?></td>
                        <td class="py-2 px-4 border-b">
                            <input type="text" name="semester" value="<?php echo htmlspecialchars($semester['semester']); ?>" class="border border-gray-300 rounded-lg px-3 py-2">
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
