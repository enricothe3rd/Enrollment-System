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
$details = $payment->getEnrollmentDetails($student_number);
if ($details) {
    $unitPrice = $details['units_price'];
    $miscellaneousFee = $details['miscellaneous_fee'];
    $monthsOfPayments = $details['months_of_payments']; // Fetching months_of_payments
} else {
    echo "No enrollment details found.";
    exit;
}

$totalPayment = ($totalUnits * $unitPrice) + $miscellaneousFee;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate POST data
    $amountPerUnit = filter_input(INPUT_POST, 'amount_per_unit', FILTER_VALIDATE_FLOAT);
    $miscellaneousFee = filter_input(INPUT_POST, 'miscellaneous_fee', FILTER_VALIDATE_FLOAT);
    $paymentMethod = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

    // Check if total_payment is set and valid
    if (isset($_POST['total_payment'])) {
        $totalPayment = filter_input(INPUT_POST, 'total_payment', FILTER_VALIDATE_FLOAT);
    } else {
        echo "<p class='text-red-600'>Total payment is not set. Please ensure all fields are filled out correctly.</p>";
        exit;
    }

    // Continue with validation
    if ($amountPerUnit === false || $miscellaneousFee === false || empty($paymentMethod) || $totalPayment === false) {
        echo "<p class='text-red-600'>Invalid input. Please ensure all fields are filled out correctly.</p>";
    } else {
        // Proceed with creating the payment record
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <!-- Include the PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=AeTnJCEfQ0MJolcWHGQSC8kwaMioTs_jWRC1mOJ05nqsy2zJe7ou1LvYQ88-EMm1vIIjImwRKvULNCT-&currency=PHP"></script>
    <script>
        const MONTHS_OF_PAYMENTS = <?php echo json_encode($monthsOfPayments); ?>;

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
    <div class="container mx-auto p-6 max-w-2xl">
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
            
            <!-- PayPal Button Container -->
            <div id="paypal-button-container" class="mt-4"></div>
            
            <!-- Submit Payment Button (for cash payments or other methods) -->
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

    <script>
    // PayPal Button Logic
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $totalPayment; ?>' // Total payment amount dynamically
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);

                // Prepare data to send to the server
                const paymentData = {
                    student_number: '<?php echo $student_number; ?>',
                    amount_per_unit: document.getElementById('amount_per_unit').value,
                    miscellaneous_fee: document.getElementById('miscellaneous_fee').value,
                    payment_method: document.getElementById('payment_method').value,
                    number_of_units: document.getElementById('number_of_units').value,
                    transaction_id: data.orderID, // PayPal transaction ID
                };

                // Send AJAX request to store the payment
                fetch('record_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(paymentData),
                })
                .then(response => response.json()) // Expecting JSON response
                .then(result => {
                    if (result.success) {
                        // Redirect to receipt.php
                        window.location.href = 'receipt.php';
                    } else {
                        alert('Failed to record payment. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
