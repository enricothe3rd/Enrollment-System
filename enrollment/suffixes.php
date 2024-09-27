<?php
session_start();
require '../db/db_connection3.php';

$pdo = Database::connect();

// Handle Create
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $suffix_name = $_POST['suffix_name'];
    $stmt = $pdo->prepare("INSERT INTO suffixes (suffix_name) VALUES (:suffix_name)");
    $stmt->bindParam(':suffix_name', $suffix_name);
    if ($stmt->execute()) {
        echo "Suffix added successfully.";
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to refresh the page
        exit;
    } else {
        echo "Error adding suffix.";
    }
}

// Handle Read
$suffixes = [];
$stmt = $pdo->query("SELECT * FROM suffixes");
$suffixes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Update
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = $_POST['suffix_id'];
    $suffix_name = $_POST['suffix_name'];
    $stmt = $pdo->prepare("UPDATE suffixes SET suffix_name = :suffix_name WHERE id = :id");
    $stmt->bindParam(':suffix_name', $suffix_name);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "Suffix updated successfully.";
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to refresh the page
        exit;
    } else {
        echo "Error updating suffix.";
    }
}

// Handle Delete
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['suffix_id'];
    $stmt = $pdo->prepare("DELETE FROM suffixes WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "Suffix deleted successfully.";
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to refresh the page
        exit;
    } else {
        echo "Error deleting suffix.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Suffixes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-2xl font-semibold text-red-800 mb-4">Manage Suffixes</h2>

        <!-- Form to add new suffix -->
        <form method="POST" class="mb-6 flex space-x-4">
            <input type="hidden" name="action" value="create">
            <input type="text" name="suffix_name" placeholder="New Suffix" class="border border-red-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-red-300" required>
            <button type="submit" class="bg-red-700 text-white px-6 py-2 rounded-lg shadow-lg hover:bg-red-600 transition duration-300">Add</button>
        </form>

        <!-- Suffixes Table -->
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-lg">
            <thead class="bg-red-700 text-white uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Suffix</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($suffixes as $suffix): ?>
                <tr class="border-b bg-red-50 hover:bg-red-200">
                    <form method="POST">
                        <input type="hidden" name="suffix_id" value="<?php echo htmlspecialchars($suffix['id']); ?>">
                        <td class="py-3 px-6 border-b"><?php echo htmlspecialchars($suffix['id']); ?></td>
                        <td class="py-3 px-6 border-b">
                            <input type="text" name="suffix_name" value="<?php echo htmlspecialchars($suffix['suffix_name']); ?>" class="border border-red-300 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring focus:border-red-300" required>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <input type="hidden" name="action" value="update">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-700 transition duration-300">Update</button>
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
