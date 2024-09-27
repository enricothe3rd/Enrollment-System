<?php
session_start();

require '../db/db_connection3.php'; // Ensure this is the correct path to your Database class
$pdo = Database::connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input data
    $student_number = $_POST['student_number'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $suffix = $_POST['suffix'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $sex = $_POST['sex'];
    $dob = $_POST['dob'];

    try {
        // Prepare the SQL statement to update the enrollment data
        $stmt = $pdo->prepare("
            UPDATE enrollments
            SET firstname = :firstname,
                middlename = :middlename,
                lastname = :lastname,
                suffix = :suffix,
                email = :email,
                contact_no = :contact_no,
                course_id = :course_id,
                section_id = :section_id,
                sex = :sex,
                dob = :dob
            WHERE student_number = :student_number
        ");

        // Bind parameters
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':middlename', $middlename);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':suffix', $suffix);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contact_no', $contact_no);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':sex', $sex);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':student_number', $student_number);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect or show success message
            header("Location: display_all_student.php"); // Replace with your success page
            exit;
        } else {
            echo "Error updating record: " . $stmt->errorInfo()[2];
        }
    } catch (PDOException $e) {
        // Handle any SQL errors
        echo "Error: " . $e->getMessage();
    }
}
?>
