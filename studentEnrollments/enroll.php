<?php
require_once 'db_connection1.php';
require_once 'classes/Enrollment.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enrollment = new Enrollment($pdo);

    $data = [
        'student_number' => $_POST['student_number'],
        'firstname' => $_POST['firstname'],
        'middlename' => $_POST['middlename'],
        'lastname' => $_POST['lastname'],
        'suffix' => $_POST['suffix'],
        'student_type' => $_POST['student_type'],
        'sex' => $_POST['sex'],
        'dob' => $_POST['dob'],
        'email' => $_POST['email'],
        'contact_no' => $_POST['contact_no'],
        'course_id' => $_POST['course_id'],
        'section_id' => $_POST['section_id'],
        'type_of_student' => 'Regular'
    ];

    if ($enrollment->enrollStudent($data)) {
        header('Location: index.php?status=success');
    } else {
        echo "Failed to enroll student!";
    }
}
?>
