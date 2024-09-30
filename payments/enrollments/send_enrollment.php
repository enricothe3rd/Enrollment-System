<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the Enrollment class
require 'Enrollment.php';

// Start session
session_start();
$user_email = $_SESSION['user_email']; // Assuming the user's email is stored in the session

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $lastname = $_POST['lastname'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact_no = $_POST['contact_no'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $suffix = $_POST['suffix'] ?? '';
    $school_year = $_POST['school_year'] ?? '';
    $status = $_POST['status'] ?? '';


    // Validate data (optional, add your own validation rules)
    if (empty($lastname) || empty($firstname) || empty($user_email)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Insert or update the enrollment data
    try {
        $enrollment = new Enrollment(); // Assuming this class is in Enrollment.php

        // Check if the user already has an enrollment record
        $existingEnrollment = $enrollment->getEnrollmentByEmail($user_email);

        if ($existingEnrollment) {
            // Update the existing enrollment record
            $result = $enrollment->updateEnrollment([
                'lastname' => $lastname,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'email' => $user_email, // Use the session email
                'dob' => $dob,
                'address' => $address,
                'contact_no' => $contact_no,
                'sex' => $sex,
                'suffix' => $suffix,
                'school_year' => $school_year,
                'status' => $status,
            
            ], $existingEnrollment['id']); // Assuming the ID is used for the update

            if ($result) {
                // Redirect to payment form on successful update
                header("Location: ../select_courses.php");
                exit;
            } else {
                echo "Failed to update enrollment. Please try again.";
            }
        } else {
            // If no existing record, create a new one
            $student_number = $enrollment->generateStudentNumber();

            // Create the enrollment record
            $result = $enrollment->createEnrollment([
                'student_number' => $student_number, // Include the generated student number
                'lastname' => $lastname,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'email' => $user_email, // Use the session email
                'dob' => $dob,
                'address' => $address,
                'contact_no' => $contact_no,
                'sex' => $sex,
                'suffix' => $suffix,
                'school_year' => $school_year,
                'status' => $status
            ]);

            if ($result) {
                // Redirect to payment form on successful enrollment
                header("Location: ../select_courses.php");
                exit;
            } else {
                echo "Failed to enroll. Please try again.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch existing enrollment data to populate the form if it exists
$existingEnrollment = $enrollment->getEnrollmentByEmail($user_email);
if ($existingEnrollment) {
    $lastname = $existingEnrollment['lastname'];
    $firstname = $existingEnrollment['firstname'];
    $middlename = $existingEnrollment['middlename'];
    $dob = $existingEnrollment['dob'];
    $address = $existingEnrollment['address'];
    $contact_no = $existingEnrollment['contact_no'];
    $sex = $existingEnrollment['sex'];
    $suffix = $existingEnrollment['suffix'];
    $school_year = $existingEnrollment['school_year'];
    $status = $existingEnrollment['status'];

} else {
    // Default values if no existing enrollment
    $lastname = '';
    $firstname = '';
    $middlename = '';
    $dob = '';
    $address = '';
    $contact_no = '';
    $sex = '';
    $suffix = '';
    $school_year = '';
    $status = '';

}
?>
