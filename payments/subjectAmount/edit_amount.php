<?php
require 'Subject.php';

$subject = new Subject();
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: SubjectAmounts.php');
    exit();
}

$subjectData = $subject->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price = $_POST['price'];

    if ($subject->updatePrice($id, $price)) {
        header('Location: SubjectAmounts.php');
        exit();
    } else {
        echo 'Failed to update subject price.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Subject Price</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> <!-- Updated CDN URL -->
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Update Subject Price</h1>
        <form method="POST" action="" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label for="id" class="block text-sm font-medium text-gray-700">Subject ID:</label>
                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($subjectData['id']); ?>" readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
            </div>
            <div class="mb-4">
                <label for="current_price" class="block text-sm font-medium text-gray-700">Current Price:</label>
                <input type="number" step="0.01" id="current_price" value="<?php echo htmlspecialchars($subjectData['price']); ?>" readonly class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">New Price:</label>
                <input type="number" step="0.01" id="price" name="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Update Price</button>
        </form>
        <a href="SubjectAmounts.php" class="mt-4 inline-block text-blue-500 hover:underline">Back to List</a>
    </div>
</body>
</html>
