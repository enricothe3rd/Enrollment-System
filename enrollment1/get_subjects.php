<?php
require_once 'db_connection3.php';
require_once 'classes/Subject.php';

$subject = new Subject($pdo);

if (isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];
    $subjects = $subject->getSubjectsBySection($section_id);

    foreach ($subjects as $subj) {
        echo '<div class="mt-4">';
        echo '<h2 class="font-bold">' . $subj['code'] . ' - ' . $subj['title'] . '</h2>';

        // Fetch schedule for each subject
        $schedules = $subject->getScheduleBySubject($subj['id']);
        foreach ($schedules as $schedule) {
            echo '<p>' . $schedule['day_of_week'] . ' | ' . $schedule['start_time'] . ' - ' . $schedule['end_time'] . ' | ' . $schedule['room'] . '</p>';
        }
        echo '</div>';
    }
}
?>
