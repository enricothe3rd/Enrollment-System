<?php
require 'db/db_connection1.php';

// Handle Create
if (isset($_POST['create_suffix'])) {
    $suffix_name = $_POST['suffix_name'];
    $stmt = $pdo->prepare("INSERT INTO suffixes (suffix_name) VALUES (:suffix_name)");
    $stmt->bindParam(':suffix_name', $suffix_name);
    if ($stmt->execute()) {
        echo "Suffix added successfully.";
    } else {
        echo "Error adding suffix.";
    }
}

// Handle Read
$suffixes = [];
$stmt = $pdo->query("SELECT * FROM suffixes");
$suffixes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Update
if (isset($_POST['update_suffix'])) {
    $id = $_POST['suffix_id'];
    $suffix_name = $_POST['suffix_name'];
    $stmt = $pdo->prepare("UPDATE suffixes SET suffix_name = :suffix_name WHERE id = :id");
    $stmt->bindParam(':suffix_name', $suffix_name);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "Suffix updated successfully.";
    } else {
        echo "Error updating suffix.";
    }
}

// Handle Delete
if (isset($_POST['delete_suffix'])) {
    $id = $_POST['suffix_id'];
    $stmt = $pdo->prepare("DELETE FROM suffixes WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "Suffix deleted successfully.";
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
<body class="bg-gray-100 p-6">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Manage Suffixes</h2>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <!-- Add Suffix Form -->
        <form method="POST" action="suffixes.php">
            <div class="mb-4">
                <label for="suffix_name" class="block text-gray-700 mb-2">Add New Suffix</label>
                <input type="text" id="suffix_name" name="suffix_name" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <button type="submit" name="create_suffix" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Add Suffix</button>
        </form>

        <!-- Display Suffixes -->
        <h3 class="text-xl font-bold mt-6 mb-4">Existing Suffixes</h3>
        <table class="w-full border border-gray-300 rounded-lg">
            <thead>
                <tr>
                    <th class="border-b p-2 text-left">Suffix</th>
                    <th class="border-b p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suffixes as $suffix) : ?>
                    <tr>
                        <form method="POST" action="suffixes.php">
                            <td class="border-b p-2">
                                <input type="text" name="suffix_name" value="<?php echo htmlspecialchars($suffix['suffix_name']); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                <input type="hidden" name="suffix_id" value="<?php echo htmlspecialchars($suffix['id']); ?>">
                            </td>
                            <td class="border-b p-2">
                                <button type="submit" name="update_suffix" class="bg-yellow-500 text-white px-4 py-2 rounded-lg">Update</button>
                                <button type="submit" name="delete_suffix" class="bg-red-500 text-white px-4 py-2 rounded-lg">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
