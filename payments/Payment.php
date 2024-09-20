<?php
// Payment.php
include_once '../db/db_connection3.php';



class Payments {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function createPayment($data) {
        try {
            // Calculate total amount based on the subject price and other fees
            $totalAmount = $data['amount_paid'] + $data['miscellaneous_fee'] + $data['research_fee'] + $data['transfer_fee'] + $data['overload_fee'];

            // Prepare SQL query
            $sql = "INSERT INTO payments (student_id, subject_id, payment_type, amount_paid, miscellaneous_fee, research_fee, transfer_fee, overload_fee, total_amount, bank_number, installment_type, installment_amount, flexible_details, payment_status)
                    VALUES (:student_id, :subject_id, :payment_type, :amount_paid, :miscellaneous_fee, :research_fee, :transfer_fee, :overload_fee, :total_amount, :bank_number, :installment_type, :installment_amount, :flexible_details, :payment_status)";

            $stmt = $this->pdo->prepare($sql);

            // Execute the statement
            $stmt->execute([
                ':student_id' => $data['student_id'],
                ':subject_id' => $data['subject_id'],
                ':payment_type' => $data['payment_type'],
                ':amount_paid' => $data['amount_paid'],
                ':miscellaneous_fee' => $data['miscellaneous_fee'],
                ':research_fee' => $data['research_fee'],
                ':transfer_fee' => $data['transfer_fee'],
                ':overload_fee' => $data['overload_fee'],
                ':total_amount' => $totalAmount,
                ':bank_number' => $data['payment_type'] === 'installment' ? $data['bank_number'] : null,
                ':installment_type' => $data['payment_type'] === 'installment' ? $data['installment_type'] : null,
                ':installment_amount' => $this->getInstallmentAmount($data['installment_type'], $data),
                ':flexible_details' => $data['payment_type'] === 'installment' && $data['installment_type'] === 'flexible' ? $data['flexible_details'] : null,
                ':payment_status' => $data['payment_status']
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    private function getInstallmentAmount($type, $data) {
        switch ($type) {
            case 'monthly':
                return $data['monthly_amount'];
            case 'quarterly':
                return $data['quarterly_amount'];
            case 'flexible':
                return null; // Flexible payment amount may not be fixed
            default:
                return null;
        }
    }
}
?>
