<?php
session_start();
require '../db/db_connection3.php'; // Adjust the filename as needed

$user_email = $_SESSION['user_email'] ?? '';
if (empty($user_email)) {
    echo "User email is not set in the session.";
    exit;
}

// Check if student_number is set in the session
if (isset($_SESSION['student_number'])) {
    $student_number = $_SESSION['student_number'];
} else {
    echo "Student number is not set in the session.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $departmentId = $_POST['department'];
    $courseId = $_POST['course'];
    $sectionId = $_POST['section']; // Capture the selected section

    // Validate input
    if (empty($departmentId) || empty($courseId) || empty($sectionId)) {
        echo "Please select a department, a course, and a section.";
        exit;
    }

    try {
        $db = Database::connect();

        // Prepare an SQL statement to insert the selection
        $stmt = $db->prepare("INSERT INTO enrollments (student_number, department_id, course_id, section_id) VALUES (:student_number, :department_id, :course_id, :section_id)");
        $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR); // Bind the student number
        $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT); // Bind the section ID

        try {
            // Attempt to execute the insert
            if ($stmt->execute()) {
                // Redirect to prevent resubmission
                header("Location: enrollments/create_enrollment.php?message=Selection saved successfully!");
                exit();
            }
        } catch (PDOException $e) {
            // Check if the error is a duplicate entry error
            if ($e->getCode() == 23000) {
                // Prepare an SQL statement to update the existing record
                $updateStmt = $db->prepare("UPDATE enrollments SET department_id = :department_id, course_id = :course_id, section_id = :section_id WHERE student_number = :student_number");
                $updateStmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
                $updateStmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
                $updateStmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
                $updateStmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT); // Bind the section ID

                if ($updateStmt->execute()) {
                    header("Location: enrollments/create_enrollment.php?message=Selection updated successfully!");
                    exit();
                } else {
                    echo "Error updating selection.";
                }
            } else {
                echo "Error: " . htmlspecialchars($e->getMessage()); // Securely display the error message
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage()); // Securely display the error message
    }
} else {
    echo "Invalid request method.";
}
?>
