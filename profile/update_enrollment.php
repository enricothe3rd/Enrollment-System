<?php
session_start();

require '../db/db_connection3.php'; // Ensure this is the correct path to your Database class
$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $student_number = trim($_POST['student_number']);
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $suffix = trim($_POST['suffix']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $contact_no = trim($_POST['contact_no']);
    $course_id = (int)$_POST['course_id']; // Cast to integer for safety
    $section_id = (int)$_POST['section_id']; // Cast to integer for safety
    $sex = trim($_POST['sex']);
    $dob = $_POST['dob'];

    try {
        // Validate required fields (simple example, can be expanded)
        if (empty($student_number) || empty($firstname) || empty($lastname) || empty($email)) {
            throw new Exception('Required fields cannot be empty.');
        }

        // Prepare the SQL statement to update the enrollments table
        $stmt = $pdo->prepare("
            UPDATE enrollments
            SET firstname = :firstname,
                middlename = :middlename,
                lastname = :lastname,
                suffix = :suffix,
                email = :email,
                contact_no = :contact_no,
                sex = :sex,
                dob = :dob
            WHERE student_number = :student_number
        ");

        // Bind parameters for enrollments
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact_no', $contact_no);
        $stmt->bindParam(':sex', $sex);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':student_number', $student_number);

        // Execute the statement for enrollments
        if ($stmt->execute()) {
            // Prepare the SQL statement to update the subject_enrollments table
            $stmt2 = $pdo->prepare("
                UPDATE subject_enrollments
                SET course_id = :course_id,
                    section_id = :section_id
                WHERE student_number = :student_number
            ");

            // Bind parameters for the subject_enrollments update
            $stmt2->bindParam(':course_id', $course_id);
            $stmt2->bindParam(':section_id', $section_id);
            $stmt2->bindParam(':student_number', $student_number);

            // Debugging: Output the values being bound (optional)
            // echo "Updating subject_enrollments with: Course ID: $course_id, Section ID: $section_id, Student Number: $student_number\n";

            // Execute the subject_enrollments update
            if ($stmt2->execute()) {
                // Redirect or show success message
                header("Location: display_all_student.php"); // Replace with your success page
                exit;
            } else {
                throw new Exception("Error updating subject_enrollments: " . implode(", ", $stmt2->errorInfo()));
            }
        } else {
            throw new Exception("Error updating enrollments: " . implode(", ", $stmt->errorInfo()));
        }
    } catch (PDOException $e) {
        // Handle any SQL errors
        echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        // Handle other errors (like validation errors)
        echo "Error: " . $e->getMessage();
    }
}
?>
