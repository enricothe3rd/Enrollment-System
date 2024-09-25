<?php
session_start();
require '../db/db_connection3.php'; // Your database connection

// Check if student number is set
if (!isset($_SESSION['student_number'])) {
    echo "<div class='text-red-500'>Student number not found.</div>";
    exit;
}

$pdo = Database::connect();
$student_number = $_SESSION['student_number'];
$query = $pdo->prepare("SELECT * FROM payments WHERE student_number = :student_number");
$query->execute(['student_number' => $student_number]);
$paymentDetails = $query->fetch(PDO::FETCH_ASSOC);

if (!$paymentDetails) {
    echo "<div class='text-red-500'>No payment details found for this student.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
        <div class="text-center">
            <img src="../assets/images/school-logo/bcc-icon.png" alt="School Logo" class="w-16 h-16 mx-auto">
            <h1 class="text-2xl font-bold mt-4">Payment Receipt</h1>
        </div>
        <hr class="my-4">
        
        <div class="text-center">
            <p class="text-lg font-semibold">Student Number: <span class="font-normal"><?php echo htmlspecialchars($paymentDetails['student_number']); ?></span></p>
            <p class="text-lg font-semibold">Transaction ID: <span class="font-normal"><?php echo htmlspecialchars($paymentDetails['transaction_id']); ?></span></p>
            <p class="text-lg font-semibold">Amount Paid: <span class="font-normal">₱<?php echo htmlspecialchars(number_format($paymentDetails['total_payment'], 2)); ?> PHP</span></p>
            <p class="text-lg font-semibold">Date: <span class="font-normal"><?php echo htmlspecialchars(date("F j, Y", strtotime($paymentDetails['created_at']))); ?></span></p>
        </div>
        
        <hr class="my-4">
        
        <div class="text-center">
            <p class="italic">Thank you for your payment!</p>
        </div>

        <a href="receipt_print.php" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 inline-block mt-4">
            Print Receipt
        </a>

        
        <a href="print_cor.php" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 inline-block mt-4">
            Print COR
        </a>
    </div>
</body>
</html>
