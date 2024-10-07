<?php
session_start(); // Start the session

// Include the Database class
require '../db/db_connection3.php'; // Make sure this path is correct

// Get the PDO instance
$db = Database::connect(); // Use the Database class to get the PDO instance

// Retrieve student_number from session
$student_number = isset($_SESSION['student_number']) ? $_SESSION['student_number'] : null;

// Initialize variables for payment breakdown
$monthly_payment = 0;
$number_of_months_payment = 0;
$next_due_date = null;

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the POST data
    $submitted_payment = $_POST['monthly_payment'];
    $transaction_id = $_POST['transaction_id'];

    // Fetch the latest payment record for the student to get the expected monthly payment
    $stmt = $db->prepare("SELECT monthly_payment, number_of_months_payment, next_payment_due_date FROM payments WHERE student_number = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$student_number]);
    $latest_payment = $stmt->fetch(PDO::FETCH_ASSOC);

    // If there's a previous payment, use its monthly payment for comparison
    if ($latest_payment) {
        $expected_payment = $latest_payment['monthly_payment'];
        $number_of_months_payment = $latest_payment['number_of_months_payment']; // Fetch the current number of months
        $next_due_date = $latest_payment['next_payment_due_date'];
    } else {
        // No previous payment, set default values
        $expected_payment = $submitted_payment; // Use submitted amount as expected payment
        $number_of_months_payment = 0; // Start with 0 months
        $next_due_date = null; // No previous due date
    }

    // Determine the next payment due date (e.g., adding 1 month from the current due date or current date)
    if ($next_due_date) {
        $next_due_date = date('Y-m-d', strtotime($next_due_date . ' +1 month')); // Update to next month
    } else {
        $next_due_date = date('Y-m-d', strtotime('+1 month')); // Set the due date to one month from now
    }

    // Prepare the insert statement for the new payment
    $stmt = $db->prepare("INSERT INTO payments (student_number, monthly_payment, transaction_id, next_payment_due_date) VALUES (?, ?, ?, ?)");

    // Execute the insert statement
    if ($stmt->execute([$student_number, $submitted_payment, $transaction_id, $next_due_date])) {
        echo "<script>alert('Payment of \$$submitted_payment processed successfully for student number: $student_number');</script>";

        // If the user paid the exact expected monthly payment, decrement the number_of_months_payment
        if ($submitted_payment == $expected_payment) {
            $number_of_months_payment = max(0, $number_of_months_payment - 1); // Decrement, but ensure it doesn't go below 0
        }
        // If the user paid less than the expected payment, do nothing (leave the number_of_months_payment unchanged)
        
        // Update the number_of_months_payment in the database after the payment
        $update_stmt = $db->prepare("UPDATE payments SET number_of_months_payment = ? WHERE student_number = ? AND transaction_id = ?");
        $update_stmt->execute([$number_of_months_payment, $student_number, $transaction_id]);

    } else {
        echo "<script>alert('Error processing payment.');</script>";
    }
}

// Fetch the breakdown of payments for the student
if ($student_number) {
    // Get the latest monthly payment and the total number of months for the payment plan
    $stmt = $db->prepare("SELECT monthly_payment, number_of_months_payment FROM payments WHERE student_number = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$student_number]);
    $latest_payment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($latest_payment) {
        $monthly_payment = $latest_payment['monthly_payment'];
        $total_months = $latest_payment['number_of_months_payment']; // Fetch total months from the latest payment record
    }

    // Get the total paid and number of months paid
    $stmt = $db->prepare("SELECT SUM(monthly_payment) AS total_paid, COUNT(*) AS number_of_months FROM payments WHERE student_number = ?");
    $stmt->execute([$student_number]);
    $payment_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($payment_info) {
        $total_paid = $payment_info['total_paid'];
        $number_of_months = $payment_info['number_of_months'];
        // Calculate the remaining months
        $remaining_months = $total_months - $number_of_months;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Payment</title>
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
    <h1>Make a Monthly Payment</h1>
    <p><strong>Student Number:</strong> <?php echo htmlspecialchars($student_number); ?></p> <!-- Display the student number -->
    <p><strong>Latest Monthly Payment Amount:</strong> PHP <?php echo htmlspecialchars($monthly_payment); ?></p> <!-- Display latest payment amount -->
    <p><strong>Number of Months Paid:</strong> <?php echo htmlspecialchars($number_of_months); ?></p> <!-- Display number of months paid -->
    <p><strong>Remaining Months:</strong> <?php echo htmlspecialchars($remaining_months); ?></p> <!-- Display remaining months -->

    <form id="payment-form" method="post" action=""> <!-- Updated action to post to itself -->
        <label for="monthly_payment">Monthly Payment Amount:</label>
        <input type="number" id="monthly_payment" name="monthly_payment" required>
        
        <input type="hidden" id="transaction_id" name="transaction_id"> <!-- Hidden input for transaction ID -->
        
        <div id="paypal-button-container"></div>
    </form>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                const amount = document.getElementById('monthly_payment').value; // Get the payment amount from the input

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: amount // Set the total amount to charge (monthly)
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);

                    // Set the transaction ID in the hidden input
                    document.getElementById('transaction_id').value = details.id;

                    // Submit the form
                    document.getElementById('payment-form').submit();
                });
            },
            onCancel: function(data) {
                alert('Transaction was canceled.');
            }
        }).render('#paypal-button-container'); // Display the PayPal button
    </script>
</body>
</html>
