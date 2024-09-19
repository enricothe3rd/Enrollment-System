<?php
require_once 'Payments.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment = new Payments();

    $data = [
        'student_id' => $_POST['student_id'],
        'subject_id' => $_POST['subject_id'],
        'amount_paid' => $_POST['amount_paid'],
        'miscellaneous_fee' => $_POST['miscellaneous_fee'] ?? 0,
        'research_fee' => $_POST['research_fee'] ?? 0,
        'transfer_fee' => $_POST['transfer_fee'] ?? 0,
        'overload_fee' => $_POST['overload_fee'] ?? 0,
        'payment_type' => $_POST['payment_type'],
        'bank_number' => $_POST['payment_type'] === 'installment' ? $_POST['bank_number'] : null,
        'installment_type' => $_POST['payment_type'] === 'installment' ? $_POST['installment_type'] : null,
        'monthly_amount' => $_POST['installment_type'] === 'monthly' ? $_POST['monthly_amount'] : null,
        'quarterly_amount' => $_POST['installment_type'] === 'quarterly' ? $_POST['quarterly_amount'] : null,
        'flexible_details' => $_POST['installment_type'] === 'flexible' ? $_POST['flexible_details'] : null,
        'payment_status' => $_POST['payment_status']
    ];

    $payment->createPayment($data);

    // Redirect or show a success message
    echo "Payment recorded successfully.";
}
?>
