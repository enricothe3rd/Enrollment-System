<?php
session_start(); // Start the session

// Include your database connection
require '../db/db_connection3.php'; // Adjust the path as necessary
require_once '../vendor/fpdf.php'; // Include FPDF library

// Create a new PDO instance
$pdo = Database::connect();

$enrollmentData = null; // Initialize variable to hold enrollment data

try {
    // Check if student_number is set in the session
    if (isset($_SESSION['student_number'])) {
        $student_number = $_SESSION['student_number'];

        // Prepare the SQL statement with JOINs to fetch course, section, and department info
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   c.course_name, 
                   s.name AS section_name, 
                   d.name AS department_name
            FROM enrollments e
            LEFT JOIN courses c ON e.course_id = c.id
            LEFT JOIN sections s ON e.section_id = s.id
            LEFT JOIN departments d ON c.department_id = d.id
            WHERE e.student_number = :student_number
        ");
        $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the results
        $enrollmentData = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Handle any errors
    $error_message = "Error: " . $e->getMessage();
}

// Check if enrollment data was retrieved successfully
if ($enrollmentData) {
    // Create new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set title
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'Enrollment Details', 0, 1, 'C');
    $pdf->Ln(10); // Add a line break

    // Set font for content
    $pdf->SetFont('Arial', '', 12);
    
    // Add student information
    $pdf->Cell(0, 10, 'Student Information', 0, 1, 'L');
    $pdf->Ln(5); // Add some space

    $fields = [
        'First Name' => $enrollmentData['firstname'],
        'Middle Name' => $enrollmentData['middlename'],
        'Last Name' => $enrollmentData['lastname'],
        'Suffix' => $enrollmentData['suffix'],
        'Student Type' => $enrollmentData['student_type'],
        'Sex' => $enrollmentData['sex'],
        'Date of Birth' => $enrollmentData['dob'],
        'Email' => $enrollmentData['email'],
        'Contact No' => $enrollmentData['contact_no'],
        'Address' => $enrollmentData['address'],
        'School Year' => $enrollmentData['school_year'],
        'Status' => $enrollmentData['status'],
        'Course' => $enrollmentData['course_name'],
        'Section' => $enrollmentData['section_name'],
        'Department' => $enrollmentData['department_name'],
    ];

    foreach ($fields as $label => $value) {
        $pdf->Cell(0, 10, "$label: " . htmlspecialchars($value), 0, 1);
    }

    // Output the PDF document and force download
    $pdf->Output('D', 'enrollment_details.pdf'); // 'D' for download
} else {
    // Handle no enrollment found scenario
    echo "No enrollment found for Student Number: " . htmlspecialchars($_SESSION['student_number']);
}
?>
