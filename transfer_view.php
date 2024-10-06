<?php
session_start(); // Start the session

// Database connection
$dsn = 'mysql:host=localhost;dbname=token_db1'; // Change to your database name
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if student_number is set in session
    if (isset($_SESSION['student_number'])) {
        $student_number = $_SESSION['student_number'];

        // Prepare statement to fetch student information
        $query = "
            SELECT * 
            FROM students 
            WHERE student_number = :student_number
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':student_number' => $student_number]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if student data was found
        if ($student) {
            // Display student information
            echo "<h1>Student Information</h1>";
            echo "<p><strong>Student Number:</strong> " . htmlspecialchars($student['student_number']) . "</p>";
            echo "<p><strong>Firstname:</strong> " . htmlspecialchars($student['firstname']) . "</p>";
            echo "<p><strong>Middlename:</strong> " . htmlspecialchars($student['middlename']) . "</p>";
            echo "<p><strong>Lastname:</strong> " . htmlspecialchars($student['lastname']) . "</p>";
            echo "<p><strong>Suffix:</strong> " . htmlspecialchars($student['suffix']) . "</p>";
            echo "<p><strong>Student Type:</strong> " . htmlspecialchars($student['student_type']) . "</p>";
            echo "<p><strong>Sex:</strong> " . htmlspecialchars($student['sex']) . "</p>";
            echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($student['dob']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($student['email']) . "</p>";
            echo "<p><strong>Contact No:</strong> " . htmlspecialchars($student['contact_no']) . "</p>";
            echo "<p><strong>Address:</strong> " . htmlspecialchars($student['address']) . "</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($student['status']) . "</p>";
            echo "<p><strong>Number of Units:</strong> " . htmlspecialchars($student['number_of_units']) . "</p>";
            echo "<p><strong>Amount per Unit:</strong> " . htmlspecialchars($student['amount_per_unit']) . "</p>";
            echo "<p><strong>Miscellaneous Fee:</strong> " . htmlspecialchars($student['miscellaneous_fee']) . "</p>";
            echo "<p><strong>Total Payment:</strong> " . htmlspecialchars($student['total_payment']) . "</p>";
            echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($student['payment_method']) . "</p>";
            echo "<p><strong>Section ID:</strong> " . htmlspecialchars($student['section_id']) . "</p>";
            echo "<p><strong>Department ID:</strong> " . htmlspecialchars($student['department_id']) . "</p>";
            echo "<p><strong>Course ID:</strong> " . htmlspecialchars($student['course_id']) . "</p>";
            echo "<p><strong>Subject ID:</strong> " . htmlspecialchars($student['subject_id']) . "</p>";
            echo "<p><strong>Schedule ID:</strong> " . htmlspecialchars($student['schedule_id']) . "</p>";
            echo "<p><strong>Semester:</strong> " . htmlspecialchars($student['semester']) . "</p>";
            echo "<p><strong>School Year:</strong> " . htmlspecialchars($student['school_year']) . "</p>";
        } else {
            echo "<p>No student found with the given student number.</p>";
        }
    } else {
        echo "<p>No student number found in session. Please log in.</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
