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
                   e.suffix,
                   e.sex  -- Include sex in the SELECT statement
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
    $pdf->Image('../assets/images/school-logo/bcc-icon.png', 33, 12, 15); // X, Y, Width

    // Set title
    $pdf->SetFont('Courier', 'B', 17);
    $pdf->Cell(0, 10, 'BINANGONAN CATHOLIC COLLEGE', 0, 1, 'C');
    
    // Add school details
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, 'Binangonan Rizal', 0, 1, 'C');
    $pdf->Ln(2); // Add a line break

     // Add school details
     $pdf->SetFont('Courier', 'I', 15);
     $pdf->Cell(0, 4, 'COLLEGE DEPARTMENT', 0, 1, 'C');
     $pdf->Ln(6); // Add a line break


    // Add school details
    $pdf->SetFont('Helvetica', 'B', 10);
    $pdf->Cell(0, 4, 'Registration Form', 0, 1, 'L');
    $pdf->Ln(-1); // Add a line break
    



    // Add the font files for Times New Roman
    $pdf->AddFont('TimesNewRoman', '', 'times.php'); // Regular Times New Roman
    $pdf->AddFont('TimesNewRoman', 'B', 'timesb.php'); // Bold Times New Roman

    // Set font to bold for the labels and increase the size
    $pdf->SetFont('TimesNewRoman', 'B', 9); // Use the newly added bold font

    // Define fields to display (with combined full name)
    $fields = [
        'Student' => $fullname, // Combined full name
        'Year' => htmlspecialchars($enrollmentData['school_year']),
        'Course' => htmlspecialchars($enrollmentData['course_name']),
        'Data of Birth' => htmlspecialchars($enrollmentData['dob']),
        'Address' => htmlspecialchars($enrollmentData['address']),
        'Email' => htmlspecialchars($enrollmentData['email']),
        'Contact No' => htmlspecialchars($enrollmentData['contact_no']),
        'Status' => htmlspecialchars($enrollmentData['status']),
        'Section' => htmlspecialchars($enrollmentData['section_name']),
    ];



// Define line height
$lineHeight = 5; // Height of each line (increased for better readability)
$pdf->SetY($pdf->GetY()); // Start Y position with some space

// Calculate position for the rectangle
$x = 145; // X position for the rectangle
$y = $pdf->GetY() - 10; // Y position for the rectangle (adjust as necessary)
$width = 50; // Width of the rectangle
$height = 10; // Height of the rectangle (adjusted for better fit)

// Draw bold rectangle by overlapping multiple rectangles
$boldOffset = 0.5; // Adjust this value for desired boldness

// Draw the outer rectangle
$pdf->SetLineWidth(0.5); // Set line width for outer rectangle
$pdf->Rect($x - $boldOffset, $y - $boldOffset, $width + 2 * $boldOffset, $height + 2 * $boldOffset); // Outer rectangle

// Draw the inner rectangle
$pdf->SetLineWidth(0.3); // Set line width for inner rectangle
$pdf->Rect($x, $y, $width, $height); // Inner rectangle

// Add "STUDENT'S COPY" label on the top right corner
$pdf->SetXY($x, $y); // Adjust Y position for the first label
$pdf->SetFont('TimesNewRoman', 'B', 9);
$pdf->Cell($width, $lineHeight, "STUDENT'S COPY", 0, 1, 'C'); // Center aligned within the rectangle

// Add "FIRST SEMESTER, A.Y. 2024-2025" label below "STUDENT'S COPY"
$pdf->SetXY($x, $y + 5); // Adjust Y position for the second label
$pdf->SetFont('TimesNewRoman', 'B', 7);
$pdf->Cell($width, $lineHeight, "FIRST SEMESTER, A.Y. 2024-2025", 0, 1, 'C'); // Center aligned within the rectangle




// Define line height
$lineHeight = 3; // Height of each line (increased for better readability)
$pdf->SetY($pdf->GetY() + 5); // Start Y position with some space

// Output the Student Number on its own row
$pdf->SetFont('Helvetica', 'B', 9);
$pdf->Cell(30, $lineHeight, "Student No:", 0);
$pdf->SetFont('TimesNewRoman', '', 9);
$pdf->SetX(29); // Change 10 to the desired left margin
$pdf->Cell(50, $lineHeight, htmlspecialchars($student_number),);

// Get the current X and Y positions
$currentX = $pdf->GetX();
$currentY = $pdf->GetY();

// Set the Y position for the underline
$pdf->SetY($currentY + 3); // Slightly below the text
$pdf->SetX($currentX -49); // Move to the right to align with the student number text

// Draw the underline
$pdf->Cell(20, 0, '', 'T'); // The 'T' parameter draws a top border (underline)

// Move to the next line with some extra space
$pdf->Ln($lineHeight );




    // Define width for labels and values
    $labelWidth = 20; // Width for labels (increased for better readability)
    $valueWidth = 30; // Width for values
    $xStart = 10; // Starting X position

    // Output the first three fields ('Student', 'Year', 'Course') in the first row
    $pdf->SetX($xStart);

    // Set font to bold for label
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Student:", 0);
    $pdf->SetX(26);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, $fullname, 0);

    $pdf->SetX(72); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Year:", 0);
    $pdf->SetX(81 );
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['school_year']), 0);

    $pdf->SetX(97 ); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Course:", 0);
    $pdf->SetX(111);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['course_name']), 0);


    $pdf->SetX(140 ); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Sex:", 0);
    $pdf->SetX(148);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['sex']), 0);


    $underlineX = $pdf->GetX(); // Left to Right
    $underlineY = $pdf->GetY(); //Top to Buttom
    
    //Student Underline
    $pdf->SetY($underlineY + 3.5);
    $pdf->SetX($currentX -53); 
    $pdf->Cell(44, 0, '', 'T'); // The 'T' parameter draws a top border (underline)

    // Year Underline
    $pdf->SetY($currentY + 9.5); 
    $pdf->SetX($currentX + 1 ); 
    $pdf->Cell(14, 0, '', 'T'); 
    
    // Course Underline
    $pdf->SetY($underlineY + 3.5);
    $pdf->SetX(112  ); 
    // Draw the underline
    $pdf->Cell(26, 0, '', 'T'); // The 'T' parameter draws a top border (underline)    
   

    // Sex Underline
    $pdf->SetY($underlineY + 3.5);
    $pdf->SetX(148  ); 
    // Draw the underline
    $pdf->Cell(11, 0, '', 'T'); // The 'T' parameter draws a top border (underline)   


   // Set the font style and size
    $pdf->SetFont('Courier', 'I', 5); // Font family: Arial, style: Bold, size: 12
    // Set the x position
    $pdf->SetX(28); // Set this to your desired left margin
    // Create the first cell of the new row with the set font
    $pdf->Cell(50, $lineHeight, htmlspecialchars("LAST NAME, GIVEN NAME, MIDDLE NAME")); // First cell of the new row



    
    $pdf->Ln(4); // Move to the next line with some extra space


    // Output the next row for 'Data of Birth', 'Address', and 'Email' in the same row
    $pdf->SetX($xStart);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Data of Birth:", 0);
    $pdf->SetX($xStart + $labelWidth + .7);
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['dob']), 0);
    
    $pdf->SetX($xStart + $labelWidth + $labelWidth - 2); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Present Address:", 0);
    $pdf->SetX($xStart + $labelWidth + $valueWidth  + $labelWidth - 4.5);
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['address']), 0);
    
    $pdf->SetX($xStart + $labelWidth + $valueWidth + 10 + $labelWidth + $valueWidth + $labelWidth - 2); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Email Address:", 0);
    $pdf->SetX($xStart + $labelWidth + $valueWidth + 10 + $labelWidth + $valueWidth + $labelWidth + $labelWidth + 2);
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['email']), 0);

    $underlineX = $pdf->GetX(); // Left to Right
    $underlineY = $pdf->GetY(); //Top to Buttom
    
    //Date of Birth Underline

    $pdf->SetY($underlineY + 3.5);
    $pdf->SetX(31); // Set this to your desired left margin
    $pdf->Cell(16, 0, '', 'T'); // The 'T' parameter draws a top border (underline)

    // Address Underline
    $pdf->SetY($underlineY + 3.5);
    $pdf->SetX( 76  ); 
    // Draw the underline
    $pdf->Cell(60, 0, '', 'T'); // The 'T' parameter draws a top border (underline)    


    // Email Underline
    $pdf->SetY($underlineY + 3.5);
    $pdf->SetX($underlineX - 30  ); 
    // Draw the underline
    $pdf->Cell(35, 0, '', 'T'); // The 'T' parameter draws a top border (underline)   



    $pdf->Ln($lineHeight ); // Move to the next line with some extra space

    // Output the remaining fields in a similar manner

    $pdf->SetX(10);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Status:", 0);
    $pdf->SetX(22 );
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['status']), 0);

    $pdf->SetX(138);
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Contact No:", 0);
    $pdf->SetX(158);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['contact_no']), 0);

    $pdf->SetX($xStart + $labelWidth + $valueWidth + 10); // Move to next position


    $pdf->SetX(40); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Section:", 0);
    $pdf->SetX(55);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['section_name']), 0);

    
    $pdf->SetX(65); // Move to next position
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->Cell($labelWidth, $lineHeight, "Department:", 0);
    $pdf->SetX(85);
    $pdf->SetFont('Helvetica', '', 9);
    $pdf->Cell($valueWidth, $lineHeight, htmlspecialchars($enrollmentData['department_name']), 0);

    //Status underline
    $pdf->SetY($underlineY + 9.5);
    $pdf->SetX(22); // Set this to your desired left margin
    $pdf->Cell(16, 0, '', 'T'); // The 'T' parameter draws a top border (underline)

    //Section underline
    $pdf->SetY($underlineY + 9.5);
    $pdf->SetX(54); // Set this to your desired left margin
    $pdf->Cell(11, 0, '', 'T'); // The 'T' parameter draws a top border (underline)


    //Department underline
    $pdf->SetY($underlineY + 9.5);
    $pdf->SetX(85); // Set this to your desired left margin
    $pdf->Cell(30, 0, '', 'T'); // The 'T' parameter draws a top border (underline)

    //Contact underline
    $pdf->SetY($underlineY + 9.5);
    $pdf->SetX(158); // Set this to your desired left margin
    $pdf->Cell(22, 0, '', 'T'); // The 'T' parameter draws a top border (underline)

















    
    // Output the PDF
    $pdf->Output();
} else {
    echo "No enrollment data found.";
}

// Disconnect from the database
Database::disconnect();
?>

