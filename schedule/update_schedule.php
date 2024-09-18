<?php
require 'Schedule.php';

// Create an instance of Schedule
$schedule = new Schedule();

// Fetch schedule data based on the provided ID in the URL
$sched = null;
if (isset($_GET['id'])) {
    $sched = $schedule->getScheduleById($_GET['id']);
}

// Handle form submission for updating the schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schedule->handleUpdateScheduleRequest();
}

// Fetch all subjects and sections for the dropdowns
$subjects = $schedule->getAllSubjects();
$sections = $schedule->getAllSections(); // Fetch sections

// Define days of the week for the dropdown
$daysOfWeek = [
    'Monday'    => 'Monday',
    'Tuesday'   => 'Tuesday',
    'Wednesday' => 'Wednesday',
    'Thursday'  => 'Thursday',
    'Friday'    => 'Friday',
    'Saturday'  => 'Saturday',
    'Sunday'    => 'Sunday',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Update Schedule</h1>

        <!-- Check if schedule data is available before rendering the form -->
        <?php if ($sched): ?>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($sched['id']); ?>">

                <!-- Section Dropdown -->
                <div class="mb-4">
                    <label for="section_id" class="block text-gray-700 font-medium">Section</label>
                    <select id="section_id" name="section_id" class="form-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <?php if (is_array($sections) && !empty($sections)): ?>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo htmlspecialchars($section['id']); ?>" <?php echo $section['id'] == $sched['section_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($section['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No sections available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Subject Dropdown -->
                <div class="mb-4">
                    <label for="subject_id" class="block text-gray-700 font-medium">Subject</label>
                    <select id="subject_id" name="subject_id" class="form-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <?php if (is_array($subjects) && !empty($subjects)): ?>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo htmlspecialchars($subject['id']); ?>" <?php echo $subject['id'] == $sched['subject_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($subject['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No subjects available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Day of Week Dropdown -->
                <div class="mb-4">
                    <label for="day_of_week" class="block text-gray-700 font-medium">Day of Week</label>
                    <select id="day_of_week" name="day_of_week" class="form-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <?php foreach ($daysOfWeek as $key => $value): ?>
                            <option value="<?php echo htmlspecialchars($key); ?>" <?php echo $key == $sched['day_of_week'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Start Time -->
                <div class="mb-4">
                    <label for="start_time" class="block text-gray-700 font-medium">Start Time</label>
                    <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($sched['start_time']); ?>" class="form-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <!-- End Time -->
                <div class="mb-4">
                    <label for="end_time" class="block text-gray-700 font-medium">End Time</label>
                    <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($sched['end_time']); ?>" class="form-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <!-- Room -->
                <div class="mb-4">
                    <label for="room" class="block text-gray-700 font-medium">Room</label>
                    <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($sched['room']); ?>" class="form-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-150">Update Schedule</button>
            </form>

        <?php else: ?>
            <p class="text-red-500">Invalid Schedule ID.</p>
        <?php endif; ?>
    </div>
</body>
</html>
