<?php
session_start();
require '../db/db_connection3.php';

// Check if the user email and student number are set in the session
$user_email = $_SESSION['user_email'] ?? '';
if (empty($user_email)) {
    echo "User email is not set in the session.";
    exit;
}

$student_number = $_SESSION['student_number'] ?? null;
if (empty($student_number)) {
    echo "Student number is not set in the session.";
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the submitted department, course, sections, subjects, and schedules
    $departmentId = $_POST['department'] ?? null;
    $courseId = $_POST['course'] ?? null;
    $selectedSections = $_POST['sections'] ?? [];
    $selectedSubjects = $_POST['subjects'] ?? [];
    $selectedSchedules = $_POST['schedules'] ?? [];

    // Validate inputs
    if (empty($departmentId) || empty($courseId)) {
        echo "Please select both a department and a course.";
        exit;
    }

    // Prepare data for console log
    $alertData = [];

    try {
        $db = Database::connect();

        foreach ($selectedSections as $sectionId) {
            $subjectIds = $selectedSubjects[$sectionId] ?? [];

            foreach ($subjectIds as $subject_id) {
                // Fetch the corresponding schedule ID based on the subject ID
                $schedule_id = $selectedSchedules[$subject_id][0] ?? null; // Assuming it's an array

                // If no schedule ID is found for the subject, skip the insertion
                if (is_null($schedule_id)) {
                    echo "Warning: No schedule ID found for subject ID: $subject_id<br>";
                    continue;
                }

                // Check for existing enrollment to prevent duplication
                $checkStmt = $db->prepare("
                    SELECT COUNT(*) FROM subject_enrollments 
                    WHERE student_number = :student_number 
                    AND section_id = :section_id 
                    AND department_id = :department_id 
                    AND course_id = :course_id 
                    AND subject_id = :subject_id
                    AND schedule_id = :schedule_id
                ");
                
                // Bind parameters for the check statement
                $checkStmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
                $checkStmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
                $checkStmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
                $checkStmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
                $checkStmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
                $checkStmt->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);

                // Execute the check statement
                $checkStmt->execute();
                $exists = $checkStmt->fetchColumn() > 0;

                if ($exists) {
                    echo "This student is already enrolled in the specified section and subject.<br>";
                    continue; // Skip to the next subject if a duplicate is found
                }

                // Add data to alert array for console logging
                $alertData[] = [
                    'student_number' => $student_number,
                    'department_id' => $departmentId,
                    'course_id' => $courseId,
                    'section_id' => $sectionId,
                    'subject_id' => $subject_id,
                    'schedule_id' => $schedule_id,
                ];

                // Prepare the insert statement
                $sectionStmt = $db->prepare("
                    INSERT INTO subject_enrollments (student_number, department_id, course_id, section_id, subject_id, schedule_id)
                    VALUES (:student_number, :department_id, :course_id, :section_id, :subject_id, :schedule_id)
                ");

                // Bind parameters for the insert statement
                $sectionStmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
                $sectionStmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
                $sectionStmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
                $sectionStmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
                $sectionStmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
                $sectionStmt->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);

                // Execute the query
                $sectionStmt->execute();
            }
        }

        header("Location: payment_form.php");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
