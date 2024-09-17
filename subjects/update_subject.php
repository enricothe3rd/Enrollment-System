<?php
require 'Subject.php';

// Create an instance of Subject, passing the PDO instance if required

$subject = new Subject();

// Fetch subject data based on the provided ID in the URL
$sub = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sub = $subject->find($_GET['id']);
}

// Fetch all sections for the dropdown
$sections = $subject->getAllSections();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject->handleUpdateSubjectRequest();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Update Subject</h1>
        
        <!-- Display the form if subject data is found -->
        <?php if ($sub) { ?>
            <form action="update_subject.php" method="post" class="space-y-4">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($sub['id']); ?>">
                
                <div>
                    <label for="code" class="block text-gray-700 font-medium">Subject Code:</label>
                    <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($sub['code']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="title" class="block text-gray-700 font-medium">Subject Title:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($sub['title']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="section_id" class="block text-gray-700 font-medium">Section:</label>
                    <select id="section_id" name="section_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <?php foreach ($sections as $section) { ?>
                            <option value="<?php echo htmlspecialchars($section['id']); ?>" <?php echo ($section['id'] == $sub['section_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section['name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                
                <div>
                    <label for="units" class="block text-gray-700 font-medium">Units:</label>
                    <input type="number" id="units" name="units" value="<?php echo htmlspecialchars($sub['units']); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update Subject</button>
            </form>
        <?php } else { ?>
            <p class="text-red-500">Invalid Subject ID or no data found for the provided ID.</p>
        <?php } ?>
    </div>
</body>
</html>
