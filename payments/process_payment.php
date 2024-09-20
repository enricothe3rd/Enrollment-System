<?php
require_once 'Payments.php'; // Adjust the path as needed


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $enrollment_fee = $_POST['enrollment_fee'];
    $miscellaneous_fee = $_POST['miscellaneous_fee'];
    $research_fee = $_POST['research_fee'];
    $overload_fee = $_POST['overload_fee'];
    $payment_method = $_POST['payment_method'];
    $installment_scheme = $_POST['installment_scheme'];

    $payment = new Payment($db);
    $payment_id = $payment->addPayment($student_id, $enrollment_fee, $miscellaneous_fee, $research_fee, $overload_fee, $payment_method, $installment_scheme);

    echo "Payment recorded successfully! Payment ID: " . $payment_id;
}

?>
