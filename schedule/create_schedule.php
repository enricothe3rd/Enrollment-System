<?php
require 'Schedule.php';

// Create an instance of Schedule
$schedule = new Schedule();

// Handle schedule creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schedule->handleCreateScheduleRequest();
}

// Fetch all sections for the dropdown
$sections = $schedule->getAllSections();

// Initialize variables
$selectedSectionId = isset($_POST['section_id']) ? intval($_POST['section_id']) : null;
$subjects = [];

// Fetch subjects based on selected section
if ($selectedSectionId) {
    $subjects = $schedule->getSubjectsBySection($selectedSectionId);
} else {
    // Fetch all subjects initially (if no section selected)
    $subjects = $schedule->getAllSubjects();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Add New Schedule</h1>
        <form action="create_schedule.php" method="post" class="space-y-4">
            <div>
                <label for="section_id" class="block text-gray-700 font-medium">Section:</label>
                <select id="section_id" name="section_id" onchange="this.form.submit()" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="" disabled selected>Select a section</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo htmlspecialchars($section['id']); ?>" <?php echo $selectedSectionId == $section['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($section['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="subject_id" class="block text-gray-700 font-medium">Subject:</label>
                <select id="subject_id" name="subject_id" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="" disabled selected>Select a subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo htmlspecialchars($subject['id']); ?>">
                            <?php echo htmlspecialchars($subject['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="day_of_week" class="block text-gray-700 font-medium">Day of Week:</label>
                <select id="day_of_week" name="day_of_week" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="" disabled selected>Select a day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            <div>
                <label for="start_time" class="block text-gray-700 font-medium">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="end_time" class="block text-gray-700 font-medium">End Time:</label>
                <input type="time" id="end_time" name="end_time" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="room" class="block text-gray-700 font-medium">Room:</label>
                <input type="text" id="room" name="room" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create Schedule</button>
        </form>
    </div>
</body>
</html>
