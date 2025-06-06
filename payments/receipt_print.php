<?php
session_start();
require '../db/db_connection3.php'; // Your database connection
require_once '../vendor/fpdf.php'; // Include FPDF library

// Check if student number is set
if (!isset($_SESSION['student_number'])) {
    echo "Student number not found.";
    exit;
}

$pdo = Database::connect();
$student_number = $_SESSION['student_number'];
$query = $pdo->prepare("SELECT * FROM payments WHERE student_number = :student_number");
$query->execute(['student_number' => $student_number]);
$paymentDetails = $query->fetch(PDO::FETCH_ASSOC);

if (!$paymentDetails) {
    echo "No payment details found for this student.";
    exit;
}

// Create new PDF document
$pdf = new FPDF();
$pdf->AddPage();

// Add school logo
$logoPath = '../assets/images/school-logo/bcc-icon.png'; // Specify the path to your logo
$pdf->Image($logoPath, 15, 10, 40, 15); // Adjust the dimensions as needed

// Set font for title
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 20, 'Payment Receipt', 0, 1, 'C'); // Centered title

// Add a horizontal line
$pdf->Line(10, 40, 200, 40);

// Set font for content
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10); // Add some space before content

// Add content with proper centering
$pdf->Cell(0, 10, 'Student Number: ' . htmlspecialchars($paymentDetails['student_number']), 0, 1, 'C');
$pdf->Cell(0, 10, 'Transaction ID: ' . htmlspecialchars($paymentDetails['transaction_id']), 0, 1, 'C'); // Added Transaction ID
$pdf->Cell(0, 10, 'Amount Paid: ' . htmlspecialchars(number_format($paymentDetails['total_payment'], 2)) . ' PHP', 0, 1, 'C');
$pdf->Cell(0, 10, 'Date: ' . htmlspecialchars(date("F j, Y", strtotime($paymentDetails['created_at']))), 0, 1, 'C');

// Add more details as necessary

// Add footer
$pdf->Ln(10); // Add some space before footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your payment!', 0, 1, 'C'); // Centered footer text

// Output the PDF document and force download
$pdf->Output('D', 'payment_receipt.pdf'); // 'D' for download
?>
