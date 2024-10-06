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
    <style>
        .receipt-header {
            border-bottom: 2px solid #0070ba; /* PayPal blue */
        }
        .receipt-footer {
            border-top: 2px solid #0070ba; /* PayPal blue */
        }
        .button {
            transition: background-color 0.3s, transform 0.2s;
        }
        .button:hover {
            background-color: #005999; /* Darker shade for hover */
            transform: scale(1.05); /* Slight zoom effect */
        }
        .rounded-button {
            border-radius: 12px; /* Rounded corners */
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <div class="text-center receipt-header pb-4">
            <img src="../assets/images/school-logo/bcc-icon.png" alt="School Logo" class="w-16 h-16 mx-auto">
            <h1 class="text-3xl font-bold mt-2 text-gray-800">Payment Receipt</h1>
        </div>
        
        <div class="my-4">
            <p class="text-lg font-semibold text-gray-700">Student Number: <span class="font-normal text-gray-600"><?php echo htmlspecialchars($paymentDetails['student_number']); ?></span></p>
            <p class="text-lg font-semibold text-gray-700">Transaction ID: <span class="font-normal text-gray-600"><?php echo htmlspecialchars($paymentDetails['transaction_id']); ?></span></p>
            <p class="text-lg font-semibold text-gray-700">Amount Paid: <span class="font-normal text-gray-600">₱<?php echo htmlspecialchars(number_format($paymentDetails['total_payment'], 2)); ?> PHP</span></p>
            <p class="text-lg font-semibold text-gray-700">Date: <span class="font-normal text-gray-600"><?php echo htmlspecialchars(date("F j, Y", strtotime($paymentDetails['created_at']))); ?></span></p>
        </div>

        <div class="text-center receipt-footer py-4">
            <p class="italic text-gray-600">Thank you for your payment!</p>
        </div>

        <div class="flex justify-center space-x-4 mt-4">
            <a href="receipt_print.php" class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-button button">
                Print Receipt
            </a>
            <a class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-button button" onclick="openTwoFiles(event)">
                Print COR
            </a>
            <button onclick="window.top.location.reload();" class="bg-green-500 text-white font-semibold py-2 px-6 rounded-button button">
                Go to Dashboard
            </button>
        </div>
    </div>
</body>
</html>

<script>
            function openTwoFiles(event) {
                event.preventDefault();  // Prevent the default anchor action
                
                // Open first PDF file in a new tab
                window.open('print_cor.php', '_blank');
                
                // Open second PDF file in the same tab
                window.location.href = 'print_cor1.php';
            }
        </script>