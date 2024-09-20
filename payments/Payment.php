<?php
// Payment.php
include_once '../db/db_connection3.php';



class Payments {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    
        public function calculateTotal($enrollment_fee, $miscellaneous_fee, $research_fee, $overload_fee) {
            return $enrollment_fee + $miscellaneous_fee + $research_fee + $overload_fee;
        }
    
        public function addPayment($student_id, $enrollment_fee, $miscellaneous_fee, $research_fee, $overload_fee, $payment_method, $installment_scheme) {
            $total_fee = $this->calculateTotal($enrollment_fee, $miscellaneous_fee, $research_fee, $overload_fee);
            
            $stmt = $this->db->prepare("INSERT INTO payments (student_id, enrollment_fee, miscellaneous_fee, research_fee, overload_fee, total_fee, payment_method, installment_scheme) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$student_id, $enrollment_fee, $miscellaneous_fee, $research_fee, $overload_fee, $total_fee, $payment_method, $installment_scheme]);
            
            return $this->db->lastInsertId();
        }
    }
    
?>
