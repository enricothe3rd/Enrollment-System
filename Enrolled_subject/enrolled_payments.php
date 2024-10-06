<?php
session_start();
require '../db/db_connection3.php';

// Establish database connection
$pdo = Database::connect();

// Initialize variables
$payments = [];
$student_number = '';

// Check if the session variable for student_number is set
if (isset($_SESSION['student_number'])) {
    $student_number = $_SESSION['student_number'];

    $sql = "
        SELECT p.*
        FROM payments p
        WHERE p.student_number = :student_number;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_number', $student_number);

    // Execute and check for errors
    if (!$stmt->execute()) {
        // Handle query error
        die("Error executing query: " . implode(", ", $stmt->errorInfo()));
    }

    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns all matching records
} else {
    echo "<p class='text-red-500'>Session variable for student number is not set. Please log in.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Records</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center text-red-800">Payment Records</h1>

    <?php if ($payments): ?>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white shadow-md rounded-lg ">
                <thead class="bg-gray-200">
                    <tr class="bg-red-800 text-white">
                        <th class="px-4 py-4 border-b text-left text-white">ID</th>
                        <th class="px-4 py-4 border-b text-left text-white">Student Number</th>
                        <th class="px-4 py-4 border-b text-left text-white">Number of Units</th>
                        <th class="px-4 py-4 border-b text-left text-white">Amount per Unit</th>
                        <th class="px-4 py-4 border-b text-left text-white">Miscellaneous Fee</th>
                        <th class="px-4 py-4 border-b text-left text-white">Total Payment</th>
                        <th class="px-4 py-4 border-b text-left text-white">Payment Method</th>
                        <th class="px-4 py-4 border-b text-left text-white">Transaction ID</th>
                        <th class="px-4 py-4 border-b text-left text-white">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr class="border-b bg-red-50 hover:bg-red-200 block sm:table-row">
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['id']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['student_number']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['number_of_units']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['amount_per_unit']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['miscellaneous_fee']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['total_payment']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                            <td class="border-t px-6 py-3 block sm:table-cell"><?php echo htmlspecialchars($payment['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-red-500 text-center mt-4">No payment records found for the provided student number.</p>
    <?php endif; ?>
</div>

</body>
</html>
