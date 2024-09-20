<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require 'SubjectAmounts.php'; // Include the class that fetches data

$subjectAmount = new SubjectAmount(); // Create an instance of the class
$departments = $subjectAmount->getDepartments(); // Fetch departments

if ($departments) {
    foreach ($departments as $department) {
        echo "<option value='{$department['id']}'>{$department['name']}</option>";
    }
} else {
    echo "<option value=''>No departments found</option>";
}
?>
