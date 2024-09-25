<?php
require 'Payment.php';

header('Content-Type: application/json');

$payment = new Payment();
$data = json_decode(file_get_contents('php://input'), true);

// Check if the required data is present
if (!isset($data['student_number'], $data['amount_per_unit'], $data['miscellaneous_fee'], $data['payment_method'], $data['number_of_units'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

// Calculate total payment
$totalPayment = ($data['amount_per_unit'] * $data['number_of_units']) + $data['miscellaneous_fee'];

// Prepare data for insertion
$paymentData = [
    'student_number' => $data['student_number'],
    'number_of_units' => $data['number_of_units'],
    'amount_per_unit' => $data['amount_per_unit'],
    'miscellaneous_fee' => $data['miscellaneous_fee'],
    'total_payment' => $totalPayment, // Include total payment
    'payment_method' => $data['payment_method'],
    'transaction_id' => $data['transaction_id'] ?? null, // Optional field
];

// Insert payment into the database
if ($payment->create($paymentData)) {
    echo json_encode(['success' => true]);
} else {
    error_log("Failed to insert payment data: " . print_r($paymentData, true));
    echo json_encode(['success' => false, 'message' => 'Failed to record payment.']);
}
?>
