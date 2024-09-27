<?php
session_start();
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
        $school_year = filter_input(INPUT_POST, 'school_year', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        switch ($action) {
            case 'create':
                $sql = "INSERT INTO school_years (year) VALUES (:school_year)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':school_year' => $school_year]);
                break;
            case 'update':
                $sql = "UPDATE school_years SET year = :school_year WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':school_year' => $school_year, ':id' => $id]);
                break;
            case 'delete':
                $sql = "DELETE FROM school_years WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);
                break;
        }
    }
}

// Fetch all school years
$sql = "SELECT * FROM school_years";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$school_years = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage School Years</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="container mx-auto max-w-4xl  ">
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Manage School Years</h1>
        
        <!-- Form to add new school year -->
        <form method="POST" class="mb-6 flex space-x-4">
            <input type="hidden" name="action" value="create">
            <input type="text" name="school_year" placeholder="New School Year" class="border border-red-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-red-300">
            <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-red-700 transition duration-300">Add</button>
        </form>

        <!-- School Years Table -->
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
            <thead class="bg-red-700 text-white uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Year</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($school_years as $year): ?>
                <tr class="border-b bg-red-50 hover:bg-red-200">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($year['id']); ?>">
                        <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($year['id']); ?></td>
                        <td class="py-3 px-6 border-b">
                            <input type="text" name="school_year" value="<?php echo htmlspecialchars($year['year']); ?>" class="border border-red-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-red-300">
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
