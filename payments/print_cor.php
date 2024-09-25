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

    // Add the logo image at the top left (adjust the path and size as needed)
    $pdf->Image('../assets/images/school-logo/bcc-icon.png', 10, 10, 30); // X, Y, Width

    // Set title
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, 'Binangonan Catholic College', 0, 1, 'C');
    // $pdf->Ln(8); // Add a line break

    // Set title
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, 'Binangonan Rizal', 0, 1, 'C');
    $pdf->Ln(8); // Add a line break

    // Add the Algerian font files
    $pdf->AddFont('Algerian', '', 'Algerian.ttf'); // Add Algerian font
    $pdf->AddFont('Algerian', 'B', 'Algerian.ttf'); // Add Algerian Bold font if available
    
    // Check if the font is added correctly
    if ($pdf->FontExists('Algerian')) {
        $pdf->SetFont('Algerian', 'B', 15); // Use the font
    } else {
        die("Failed to add Algerian font");
    }
    
    $pdf->Cell(0, 10, 'Certificate of Registration', 0, 1, 'C');
    $pdf->Ln(8); // Add a line break

    // Add the font files for Times New Roman
    $pdf->AddFont('TimesNewRoman', '', 'times.php'); // Add Times New Roman
    $pdf->AddFont('TimesNewRoman', 'B', 'times.php'); // Add Times New Roman Bold

    // Set font to bold and increase the size
    $pdf->SetFont('TimesNewRoman', 'B', 9); // Use the newly added font

    // Define fields to display
    $fields = [
        'Student Number' => htmlspecialchars($student_number),
        'Department' => htmlspecialchars($enrollmentData['department_name']),
        'School Year' => htmlspecialchars($enrollmentData['school_year']),
        'First Name' => htmlspecialchars($enrollmentData['firstname']),
        'Middle Name' => htmlspecialchars($enrollmentData['middlename']),
        'Last Name' => htmlspecialchars($enrollmentData['lastname']),
        'Suffix' => htmlspecialchars($enrollmentData['suffix']),
        'Student Type' => htmlspecialchars($enrollmentData['student_type']),
        'Sex' => htmlspecialchars($enrollmentData['sex']),
        'Date of Birth' => htmlspecialchars($enrollmentData['dob']),
        'Email' => htmlspecialchars($enrollmentData['email']),
        'Contact No' => htmlspecialchars($enrollmentData['contact_no']),
        'Address' => htmlspecialchars($enrollmentData['address']),
        'Status' => htmlspecialchars($enrollmentData['status']),
        'Course' => htmlspecialchars($enrollmentData['course_name']),
        'Section' => htmlspecialchars($enrollmentData['section_name']),
    ];

    // Define line height
    $lineHeight = 2; // Height of each line (increased for better readability)
    $pdf->SetY($pdf->GetY() + 5); // Start Y position with some space

    // Define width for labels and values
    $labelWidth = 24; // Width for labels
    $valueWidth = 35; // Width for values
    $xStart = 10; // Starting X position

    // Output the fields in the desired format (3 fields per row)
    $fieldCount = 0;
    foreach ($fields as $label => $value) {
        // Calculate the X position based on the field count
        $xLabel = $xStart + ($fieldCount % 3) * ($labelWidth + $valueWidth + 10); // Total spacing
        $pdf->SetX($xLabel);
        $pdf->Cell($labelWidth, $lineHeight, "$label:", 0);

        // Set the X position for the value
        $xValue = $xLabel + $labelWidth; // Value starts right after the label
        $pdf->SetX($xValue);
        $pdf->Cell($valueWidth, $lineHeight, $value, 0); // Limited width to avoid overflow

        // Increment field count
        $fieldCount++;

        // Move to the next line if three fields are displayed
        if ($fieldCount % 3 == 0) {
            $pdf->Ln($lineHeight + 5); // Move to the next line with some extra space
        }
    }

    // Check if there are any remaining fields to print that are less than 3
    if ($fieldCount % 3 != 0) {
        $pdf->Ln($lineHeight + 5); // Add an extra line if needed
    }

    // Output the PDF document and display in browser
    $pdf->Output('I', 'enrollment_details.pdf'); // 'I' for inline display
} else {
    echo "No enrollment data found for this student.";
}
