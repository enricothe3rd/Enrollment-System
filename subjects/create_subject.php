<?php
require 'Subject.php';

$subject = new Subject();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle subject creation
    $subject->handleCreateSubjectRequest();
}

// Get all sections, semesters, and school years
$sections = $subject->getAllSections();
$semesters = $subject->getAllSemesters();
$school_years = $subject->getAllSchoolYears(); // Fetch school years
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-lg">
        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Add New Subject</h1>
        <form action="create_subject.php" method="post" class="space-y-4">
            <div>
                <label for="code" class="block text-red-700 font-medium">Subject Code:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-code px-3 text-red-500"></i> <!-- Subject code icon -->
                    <input type="text" id="code" name="code" required placeholder="Enter subject code" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
            </div>
            <div>
                <label for="title" class="block text-red-700 font-medium">Subject Title:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-book text-red-500 px-3"></i> <!-- Subject title icon -->
                    <input type="text" id="title" name="title" required placeholder="Enter subject title" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
            </div>
            <div>
                <label for="semester_id" class="block text-red-700 font-medium">Semester:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-calendar-alt text-red-500 px-3"></i> <!-- Semester icon -->
                    <select id="semester_id" name="semester_id" required class="bg-red-50 block w-full px-3 py-2 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                        <?php foreach ($semesters as $semester): ?>
                            <option value="<?php echo htmlspecialchars($semester['id']); ?>">
                                <?php echo htmlspecialchars($semester['semester_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
                <label for="section_id" class="block text-red-700 font-medium">Section:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-users text-red-500 px-3"></i> <!-- Section icon -->
                    <select id="section_id" name="section_id" required class="bg-red-50 block w-full px-3 py-2 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo htmlspecialchars($section['id']); ?>">
                                <?php echo htmlspecialchars($section['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div>
        <label for="school_year_id" class="block text-red-700 font-medium">School Year:</label>
        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
            <i class="fas fa-calendar text-red-500 px-3"></i> <!-- School year icon -->
            <select id="school_year_id" name="school_year_id" required class="bg-red-50 block w-full px-3 py-2 text-red-800 border-red-300 rounded-md shadow-sm focus:outline-none focus:bg-red-100 focus:border-red-500 sm:text-sm">
                <?php foreach ($school_years as $year): ?>
                    <option value="<?php echo htmlspecialchars($year['id']); ?>">
                        <?php echo htmlspecialchars($year['year']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
            <div>
                <label for="units" class="block text-red-700 font-medium">Units:</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-graduation-cap text-red-500 px-3"></i> <!-- Units icon -->
                    <input type="number" id="units" name="units" required placeholder="Enter number of units" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 flex items-center justify-center">
                <i class="fas fa-plus-circle mr-2"></i> <!-- Plus icon -->
                Create Subject
            </button>
        </form>
    </div>

    <script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
    </script>
</body>
</html>
