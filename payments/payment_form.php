<?php
require 'Payment.php';

session_start();
$user_email = $_SESSION['user_email'] ?? '';
if (empty($user_email)) {
    echo "User email is not set in the session.";
    exit;
}

// Check if student_number is set in the session
if (!isset($_SESSION['student_number'])) {
    echo "Student number is not set in the session.";
    exit;
}
$student_number = $_SESSION['student_number'];

$payment = new Payment();
$unitPrice = '';
$miscellaneousFee = '';
$totalUnits = $payment->getTotalUnitsForStudent($student_number); // Fetching total units

// Fetching enrollment details
$details = $payment->getEnrollmentDetails($student_number); // Pass student_number as parameter
if ($details) {
    $unitPrice = $details['units_price'];
    $miscellaneousFee = $details['miscellaneous_fee'];
    $monthsOfPayments = $details['months_of_payments']; // Fetching months_of_payments
} else {
    echo "No enrollment details found.";
    exit; // Exit if no details are found
}

// Initialize payment-related variables
$totalPayment = ($totalUnits * $unitPrice) + $miscellaneousFee;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate POST data
    $amountPerUnit = filter_input(INPUT_POST, 'amount_per_unit', FILTER_VALIDATE_FLOAT);
    $miscellaneousFee = filter_input(INPUT_POST, 'miscellaneous_fee', FILTER_VALIDATE_FLOAT);
    $paymentMethod = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);
    
    if ($amountPerUnit === false || $miscellaneousFee === false || empty($paymentMethod)) {
        echo "<p class='text-red-600'>Invalid input. Please ensure all fields are filled out correctly.</p>";
    } else {
        $data = [
            'student_number' => $student_number, // Updated here
            'number_of_subjects' => $payment->getNumberOfSubjects($student_number),
            'number_of_units' => $totalUnits,
            'amount_per_unit' => $amountPerUnit,
            'miscellaneous_fee' => $miscellaneousFee,
            'payment_method' => $paymentMethod,
        ];

        // Insert payment into the database
        if ($payment->create($data)) {
            echo "<p class='text-green-600'>Payment recorded successfully!</p>";
        } else {
            echo "<p class='text-red-600'>Failed to record payment. Please try again.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script>
        const MONTHS_OF_PAYMENTS = <?php echo json_encode($monthsOfPayments); ?>; // Use PHP variable in JS

        function calculateMonthlyPayments() {
            const unitPrice = parseFloat(document.getElementById('amount_per_unit').value) || 0;
            const miscellaneousFee = parseFloat(document.getElementById('miscellaneous_fee').value) || 0;
            const totalUnits = parseInt(document.getElementById('number_of_units').value) || 0;

            const totalPayment = (totalUnits * unitPrice) + miscellaneousFee;
            const monthlyPayment = MONTHS_OF_PAYMENTS > 0 ? totalPayment / MONTHS_OF_PAYMENTS : 0;

            const tableBody = document.getElementById('monthlyPaymentsTableBody');
            tableBody.innerHTML = ''; // Clear previous table entries

            for (let i = 1; i <= MONTHS_OF_PAYMENTS; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `<td class="border border-gray-300 p-2 text-center">${i}</td>
                                 <td class="border border-gray-300 p-2 text-center">₱${monthlyPayment.toFixed(2)}</td>`;
                tableBody.appendChild(row);
            }

            document.getElementById('monthlyPaymentsTable').style.display = 'table'; // Show the table
        }

        function handlePaymentMethodChange() {
            const paymentMethod = document.getElementById('payment_method').value;

            if (paymentMethod === 'installment') {
                calculateMonthlyPayments(); // Calculate and display the payments
            } else {
                document.getElementById('monthlyPaymentsTable').style.display = 'none'; // Hide the table
            }
        }
    </script>
</head>
<body>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Payment Form</h1>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="student_id" class="block text-sm font-medium">Student ID:</label>
                <input type="text" name="student_id" id="student_id" value="<?php echo htmlspecialchars($student_number); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" readonly>
            </div>
            <div class="mb-4">
                <label for="number_of_units" class="block text-sm font-medium">Your Total Number of Units</label>
                <input type="number" name="number_of_units" id="number_of_units" value="<?php echo htmlspecialchars($totalUnits); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" readonly>
            </div>
            <div class="mb-4 relative">
                <label for="amount_per_unit" class="block text-sm font-medium">Amount per Unit:</label>
                <div class="flex items-center border border-gray-300 rounded-md shadow-sm mt-1">
                    <span class="bg-gray-200 text-gray-700 px-3 py-2 rounded-l-md">₱</span>
                    <input type="number" name="amount_per_unit" id="amount_per_unit" value="<?php echo htmlspecialchars($unitPrice); ?>" required class="flex-1 p-2 border-l border-gray-300 rounded-r-md" placeholder="Enter amount" oninput="calculateMonthlyPayments()">
                </div>
            </div>
            <div class="mb-4 relative">
                <label for="miscellaneous_fee" class="block text-sm font-medium">Miscellaneous Fee:</label>
                <div class="flex items-center border border-gray-300 rounded-md shadow-sm mt-1">
                    <span class="bg-gray-200 text-gray-700 px-3 py-2 rounded-l-md">₱</span>
                    <input type="number" name="miscellaneous_fee" id="miscellaneous_fee" value="<?php echo htmlspecialchars($miscellaneousFee); ?>" required class="flex-1 p-2 border-l border-gray-300 rounded-r-md" placeholder="Enter fee" oninput="calculateMonthlyPayments()">
                </div>
            </div>
            <div class="mb-4 relative">
                <label for="total_payment" class="block text-sm font-medium">Total Payment:</label>
                <div class="flex items-center border border-gray-300 rounded-md shadow-sm mt-1">
                    <span class="bg-gray-200 text-gray-700 px-3 py-2 rounded-l-md">₱</span>
                    <input type="number" name="total_payment" id="total_payment" value="<?php echo htmlspecialchars($totalPayment); ?>" class="flex-1 p-2 border-l border-gray-300 rounded-r-md" readonly>
                </div>
            </div>
            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium">Payment Method:</label>
                <select name="payment_method" id="payment_method" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" onchange="handlePaymentMethodChange()">
                    <option value="cash">Cash</option>
                    <option value="installment">Installment</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Payment</button>
        </form>

        <table id="monthlyPaymentsTable" class="mt-4 w-full border border-gray-300" style="display: none;">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 p-2">Month</th>
                    <th class="border border-gray-300 p-2">Amount (₱)</th>
                </tr>
            </thead>
            <tbody id="monthlyPaymentsTableBody"></tbody>
        </table>
    </div>
</body>
</html>
