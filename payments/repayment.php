<?php
session_start(); // Start the session

// Include the Database class
require '../db/db_connection3.php'; // Ensure this path is correct

// Get the PDO instance
$db = Database::connect(); // Use the Database class to get the PDO instance

// Retrieve student_number from session
$student_number = isset($_SESSION['student_number']) ? $_SESSION['student_number'] : null;


if ($student_number) {

        try {
            // SQL query to get the sum of installment payments by student_number
            $sql = "SELECT SUM(installment_down_payment) AS total_installment
                    FROM payments
                    WHERE student_number = :student_number";

            $stmt1 = $db->prepare($sql);
            $stmt1->bindParam(':student_number', $student_number);
            $stmt1->execute();

            // Fetch result
            $result = $stmt1->fetch(PDO::FETCH_ASSOC);
            $total_already_payed = isset($result['total_installment']) ? $result['total_installment'] : 0;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }



        // Prepare the SQL statement
        $stmt = $db->prepare("
            SELECT number_of_months_payment, monthly_payment, next_payment_due_date, installment_down_payment
            FROM payments
            WHERE student_number = :student_number
        ");
        
        // Bind the student_number parameter
        $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);
        
        // Execute the query
        $stmt->execute();
        
        // Fetch the result as an associative array
        $paymentDetails = $stmt->fetch(PDO::FETCH_ASSOC);


       
        //Number of months payment
        $number_of_months = $paymentDetails['number_of_months_payment'];

        //Month Payment
        $monthly_payment = $paymentDetails['monthly_payment'];

        //Payment Due Date
        $payment_due_date = $paymentDetails['next_payment_due_date']; 

        // /Calculate the Total Installment 
        $total_installment = $number_of_months *  $monthly_payment;
        
        // Calculate next payment due date
        $next_payment_due_date = date('Y-m-d', strtotime($paymentDetails['next_payment_due_date'] . ' +1 month'));

        //Calculate that will deduct
        $remaining_balance = $total_installment -$total_already_payed;



 }
 else {
    echo "Student number is not set in the session.";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_monthly_payment = $_POST['total_amount1']; // Corrected from 'monthly_payment'
    $transaction_id = $_POST['transaction_id']; // Get transaction ID from hidden input

    try {
        // Assuming you have variables for $installment_down_payment and $next_payment_due_date
        $installment_down_payment = $student_monthly_payment; // Adjust this as per your logic
      // Add one month to the current due date
      $current_due_date = new DateTime($payment_due_date);
      $next_payment_due_date = $current_due_date->modify('+1 month')->format('Y-m-d');

        // Insert new payment record
        $insertStmt = $db->prepare("INSERT INTO payments (student_number, transaction_id, installment_down_payment, next_payment_due_date, payment_status) 
                                      VALUES (:student_number, :transaction_id, :installment_down_payment, :next_payment_due_date, 'completed')");
        $insertStmt->execute([
            ':student_number' => $_SESSION['student_number'],
            ':transaction_id' => $transaction_id,
            ':installment_down_payment' => $installment_down_payment,
            ':next_payment_due_date' => $next_payment_due_date
        ]);

        echo "Payment processed successfully! Transaction ID: $transaction_id. Next payment due date is " . $next_payment_due_date;

    } catch (Exception $e) {
        echo "Error processing payment: " . $e->getMessage();
    }
}







?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://www.paypal.com/sdk/js?client-id=AeTnJCEfQ0MJolcWHGQSC8kwaMioTs_jWRC1mOJ05nqsy2zJe7ou1LvYQ88-EMm1vIIjImwRKvULNCT-&currency=PHP"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            margin-top: 10px;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px;
            background-color: #0070ba;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
    </style>
</head>
<body>
    <form id="payment-form" method="post" action="">
        <input type="hidden" id="hidden_total" name="total_amount1" value="0.00">
        <div class="bg-gray-100 p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <!-- Payment Details -->
            <div class="mb-6">
                <p class="text-lg font-semibold text-gray-800">
                    Number of Installment: <span class="font-bold text-blue-600" id="installment-count"></span>
                </p>
                <p class="text-lg font-semibold text-gray-800 mt-2">
                    Total Tuition: <span class="font-bold text-green-600">₱<span id="total-amount"><?php echo htmlspecialchars($total_installment); ?></span></span>
                </p>
                <p class="text-lg font-semibold text-gray-800 mt-2">
                    Amount Already Payed: <span class="font-bold text-green-600">₱<span id="total_already_payed"><?php echo htmlspecialchars($total_already_payed); ?></span></span>
                </p>
                <p class="text-lg font-semibold text-gray-800 mt-2">
                    Remaining Balance: <span class="font-bold text-red-600">₱<span id="remaining-balance"><?php echo htmlspecialchars($remaining_balance); ?></span></span>
                </p>
            </div>

            <?php
// Calculate the dynamic monthly payment based on remaining balance and number of months
$monthly_payment = $remaining_balance / $number_of_months;

// Calculate the maximum months based on the remaining balance
$max_months = floor($remaining_balance / $monthly_payment);

// Loop to render checkboxes only for the available months
for ($i = 1; $i <= $number_of_months; $i++) {
    if ($i <= $max_months) {
?>
    <div class="flex items-center mb-4 p-3 bg-gray-50 border rounded-lg shadow-sm hover:bg-gray-100 transition duration-200 ease-in-out">
        <input type="checkbox" name="month_payment[]" value="<?= $monthly_payment ?>" 
               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
               id="month-<?= $i ?>" onchange="updateTotal(this); toggleCustomAmount()">
        <label class="ml-3 text-gray-700" for="month-<?= $i ?>">Month <?= $i ?> - Payment: 
            <span class="font-semibold text-green-600">₱<?= number_format($monthly_payment, 2) ?></span>
        </label>
    </div>
<?php
    }
}
?>

<!-- Custom Amount Input -->
<div class="mb-4">
    <label for="custom_amount">Custom Amount:</label>
    <input type="number" id="custom_amount" name="custom_amount" placeholder="Enter custom amount" min="0" oninput="updateCustomAmount(this)">
    <p class="text-lg font-semibold text-gray-800 mt-2">
        Amount Selected: <span class="font-bold text-green-600">₱<span id="total-amount1">0.00</span></span>
    </p>
</div>

<script>
function toggleCustomAmount() {
    // Get all checkboxes
    const checkboxes = document.querySelectorAll('input[name="month_payment[]"]');
    const customAmountInput = document.getElementById('custom_amount');

    // Check if any checkbox is checked
    const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    // Enable or disable the custom amount input based on checkbox state
    customAmountInput.disabled = isAnyChecked;
    
    // Optionally clear the custom amount if it's disabled
    if (isAnyChecked) {
        customAmountInput.value = '';
    }
}
</script>


        <script>
            // Display the number of installments allowed based on remaining balance
            document.getElementById('installment-count').innerText = Math.floor(parseFloat(document.getElementById('remaining-balance').innerText) / parseFloat(<?php echo json_encode($monthly_payment); ?>));

            function updateTotal(checkbox) {
    const totalAmountElement = document.getElementById('total-amount1');
    const hiddenTotalInput = document.getElementById('hidden_total');
    const remainingBalanceElement = document.getElementById('remaining-balance');
    
    let currentTotal = parseFloat(totalAmountElement.innerText) || 0;
    const remainingBalance = parseFloat(remainingBalanceElement.innerText) || 0;

    const monthlyPayment = parseFloat(checkbox.value) || 0;

    if (checkbox.checked) {
        if (currentTotal + monthlyPayment > remainingBalance) {
            alert("You cannot select more than the remaining balance.");
            checkbox.checked = false;
            return;
        }
        currentTotal += monthlyPayment;
    } else {
        currentTotal -= monthlyPayment;
    }

    // Update the total display and hidden input
    totalAmountElement.innerText = currentTotal.toFixed(2);
    hiddenTotalInput.value = currentTotal.toFixed(2);
}


function updateCustomAmount(input) {
    const totalAmountElement = document.getElementById('total-amount1');
    const hiddenTotalInput = document.getElementById('hidden_total');
    const remainingBalanceElement = document.getElementById('remaining-balance');

    const customAmount = parseFloat(input.value) || 0;
    const remainingBalance = parseFloat(remainingBalanceElement.innerText) || 0;

    if (customAmount > remainingBalance) {
        alert("Custom amount cannot exceed the remaining balance.");
        input.value = ''; // Clear the input if overpaid
        totalAmountElement.innerText = '0.00';
        hiddenTotalInput.value = '0.00';
        return;
    }

    // Reset checkboxes when a custom amount is entered
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });

    // Update total with custom amount
    totalAmountElement.innerText = customAmount.toFixed(2);
    hiddenTotalInput.value = customAmount.toFixed(2);
}

        </script>

        <input type="hidden" id="transaction_id" name="transaction_id">
        
        <div id="paypal-button-container"></div>
    </form>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                const amount = document.getElementById('total-amount1').innerText; // Use updated amount
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount // Use the calculated amount
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    document.getElementById('transaction_id').value = details.id; // Set transaction ID in hidden input
                    document.getElementById('payment-form').submit(); // Submit form after capturing the order
                });
            }
        }).render('#paypal-button-container'); // Display PayPal button
    </script>
</body>
</html>
