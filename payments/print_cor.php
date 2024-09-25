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
                   d.name AS department_name,
                   e.firstname,
                   e.middlename,
                   e.lastname,
                   e.suffix
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
    // Combine first name, middle name, last name, and suffix
    $fullname = htmlspecialchars($enrollmentData['firstname']);
    if (!empty($enrollmentData['middlename'])) {
        $fullname .= ' ' . htmlspecialchars($enrollmentData['middlename']);
    }
    $fullname .= ' ' . htmlspecialchars($enrollmentData['lastname']);
    if (!empty($enrollmentData['suffix'])) {
        $fullname .= ' ' . htmlspecialchars($enrollmentData['suffix']);
    }

    // Create new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add the logo image at the top left (adjust the path and size as needed)
    $pdf->Image('../assets/images/school-logo/bcc-icon.png', 10, 10, 30); // X, Y, Width

    // Set title
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, 'Binangonan Catholic College', 0, 1, 'C');
    
    // Add school details
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, 'Binangonan Rizal', 0, 1, 'C');
    $pdf->Ln(8); // Add a line break

    // Add the custom font (Algerian)
    $pdf->AddFont('Algerian', '', 'Algerian.php');
    $pdf->SetFont('Algerian', '', 12);
    
    $text = 'Certificate of Registration';
    $letter_spacing = 2; // Adjust this value to increase/decrease spacing
    $character_width = 2; // Width of each character in the current font
    $text_length = strlen($text);
    
    // Calculate total width of the text including the spacing
    $total_text_width = ($character_width * $text_length) + ($letter_spacing * ($text_length - 1));
    
    // Get the current page width
    $page_width = $pdf->GetPageWidth();
    
    // Calculate starting X position to center the text
    $x_start = ($page_width - $total_text_width) / 2;
    
    $pdf->SetY(35); // Set Y position (optional)
    $pdf->SetX($x_start); // Set starting X position for centered text
    
    // Loop through each letter in the string
    for ($i = 0; $i < strlen($text); $i++) {
        $pdf->Cell($character_width, 5, $text[$i], 0, 0, 'C'); // Print each character
        $pdf->Cell($letter_spacing); // Add the space between characters
    }    
    $pdf->Ln(12); // Add a line break

    // Add the font files for Times New Roman
    $pdf->AddFont('TimesNewRoman', '', 'times.php'); // Regular Times New Roman
    $pdf->AddFont('TimesNewRoman', 'B', 'timesb.php'); // Bold Times New Roman

    // Set font to bold for the labels and increase the size
    $pdf->SetFont('TimesNewRoman', 'B', 9); // Use the newly added bold font

    // Define fields to display (with combined full name)
    $fields = [
        'Student Number' => htmlspecialchars($student_number),
        'Full Name' => $fullname, // Combined full name
        'Department' => htmlspecialchars($enrollmentData['department_name']),
        'School Year' => htmlspecialchars($enrollmentData['school_year']),
        'Email' => htmlspecialchars($enrollmentData['email']),
        'Contact No' => htmlspecialchars($enrollmentData['contact_no']),
        'Address' => htmlspecialchars($enrollmentData['address']),
        'Status' => htmlspecialchars($enrollmentData['status']),
        'Course' => htmlspecialchars($enrollmentData['course_name']),
        'Section' => htmlspecialchars($enrollmentData['section_name']),
    ];

    // Define line height
    $lineHeight = 3; // Height of each line (increased for better readability)
    $pdf->SetY($pdf->GetY() + 5); // Start Y position with some space

    // Define width for labels and values
    $labelWidth = 25; // Width for labels (increased for better readability)
    $valueWidth = 35; // Width for values
    $xStart = 10; // Starting X position

    // Output the fields in the desired format (3 fields per row)
    $fieldCount = 0;
    foreach ($fields as $label => $value) {
        // Calculate the X position based on the field count
        $xLabel = $xStart + ($fieldCount % 3) * ($labelWidth + $valueWidth + 10); // Total spacing
        $pdf->SetX($xLabel);

        // Set font to bold for the label
        $pdf->SetFont('TimesNewRoman', 'B', 9);
        $pdf->Cell($labelWidth, $lineHeight, "$label:", 0);

        // Set the X position for the value
        $xValue = $xLabel + $labelWidth; // Value starts right after the label
        $pdf->SetX($xValue);
        
        // Set font to regular for the value
        $pdf->SetFont('TimesNewRoman', '', 9);
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
?>
