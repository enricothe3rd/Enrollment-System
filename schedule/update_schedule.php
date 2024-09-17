<?php
require 'Schedule.php';

// Create an instance of Schedule
$schedule = new Schedule();

// Fetch schedule data based on the provided ID in the URL
if (isset($_GET['id'])) {
    $sched = $schedule->getScheduleById($_GET['id']);
}

// Handle form submission for updating the schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schedule->handleUpdateScheduleRequest();
}

// Fetch all subjects for the dropdown
$subjects = $schedule->getAllSubjects();

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
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Update Schedule</h1>

        <!-- Check if schedule data is available before rendering the form -->
        <?php if (isset($sched)) { ?>

            <form action="" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($sched['id']); ?>">

                <div class="mb-4">
                    <label for="subject_id" class="block text-gray-700">Subject</label>
                    <select id="subject_id" name="subject_id" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <?php
                        // Check if $subjects is an array and has elements
                        if (is_array($subjects) && !empty($subjects)) {
                            foreach ($subjects as $subject) {
                                $selected = $subject['id'] == $sched['subject_id'] ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($subject['id']) . "\" $selected>" . htmlspecialchars($subject['title']) . "</option>";
                            }
                        } else {
                            echo '<option value="">No subjects available</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="day_of_week" class="block text-gray-700">Day of Week</label>
                    <select id="day_of_week" name="day_of_week" class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <?php
                        // Populate the dropdown with days of the week
                        foreach ($daysOfWeek as $key => $value) {
                            $selected = $key == $sched['day_of_week'] ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($key) . "\" $selected>" . htmlspecialchars($value) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="start_time" class="block text-gray-700">Start Time</label>
                    <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($sched['start_time']); ?>" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <div class="mb-4">
                    <label for="end_time" class="block text-gray-700">End Time</label>
                    <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($sched['end_time']); ?>" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <div class="mb-4">
                    <label for="room" class="block text-gray-700">Room</label>
                    <input type="text" id="room" name="room" value="<?php echo htmlspecialchars($sched['room']); ?>" class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-150">Update Schedule</button>
            </form>
        <?php } else { ?>
            <p class="text-red-500">Invalid Schedule ID.</p>
        <?php } ?>
    </div>
</body>
</html>
