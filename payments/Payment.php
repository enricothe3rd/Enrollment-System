<?php
require '../db/db_connection3.php'; // Include your database connection

class Payment {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect(); // Assuming you have a Database class
    }

    public function create($data) {
        // Prepare the SQL statement
        $stmt = $this->conn->prepare("INSERT INTO payments (student_number, number_of_units, amount_per_unit, miscellaneous_fee, payment_method) 
                                      VALUES (:student_number, :number_of_units, :amount_per_unit, :miscellaneous_fee, :payment_method)");

        // Bind the parameters
        $stmt->bindParam(':student_number', $data['student_number']);
        $stmt->bindParam(':number_of_units', $data['number_of_units']);
        $stmt->bindParam(':amount_per_unit', $data['amount_per_unit']);
        $stmt->bindParam(':miscellaneous_fee', $data['miscellaneous_fee']);
        $stmt->bindParam(':payment_method', $data['payment_method']);

        // Execute the statement
        return $stmt->execute();
    }
    

    public function read() {
        $sql = "SELECT * FROM payments";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $sql = "UPDATE payments SET
                number_of_units = :number_of_units,
                amount_per_unit = :amount_per_unit,
                miscellaneous_fee = :miscellaneous_fee,
                total_payment = :total_payment,
                payment_method = :payment_method
                WHERE student_number = :student_number"; // Update where student_number
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $sql = "DELETE FROM payments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getEnrollmentDetails() {
        $sql = "SELECT units_price, miscellaneous_fee, months_of_payments FROM enrollment_payments LIMIT 1"; // Modify as needed
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getNumberOfSubjects($student_number) {
        $sql = "SELECT COUNT(subject_id) AS number_of_subjects FROM subject_enrollments WHERE student_number = :student_number"; // Using the correct column name
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_number' => $student_number]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['number_of_subjects'];
    }

    public function getTotalUnitsBySubject($subject_id) {
        $sql = "SELECT units AS total_units 
                FROM subjects 
                WHERE id = :subject_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':subject_id' => $subject_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_units'] ?? 0; // Return 0 if no units found
    }

    public function getSubjectIdsByStudentNumber($student_number) {
        $sql = "SELECT subject_id 
                FROM subject_enrollments 
                WHERE student_number = :student_number";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_number' => $student_number]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all subject IDs
    }

    public function getTotalUnitsForStudent($student_number) {
        // Get all subject IDs for the student
        $subjectIds = $this->getSubjectIdsByStudentNumber($student_number);
        $totalUnits = 0;

        // Iterate through each subject ID and accumulate the total units
        foreach ($subjectIds as $subject) {
            $totalUnits += $this->getTotalUnitsBySubject($subject['subject_id']);
        }

        return $totalUnits; // Return the total units
    }
    
    // Method to get the school year based on student number
    public function getSchoolYear($student_number) {
        $stmt = $this->conn->prepare("SELECT school_year FROM enrollments WHERE student_number = ?");
        $stmt->execute([$student_number]);
        return $stmt->fetchColumn();
    }
    
    public function getMonthsOfPayments() {
        try {
            $pdo = Database::connect(); // Assuming you have a Database class for connection
            $query = "SELECT months_of_payments FROM enrollment_payments";
            $stmt = $pdo->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result; // Return all months of payments
        } catch (PDOException $e) {
            echo "Error fetching months of payments: " . $e->getMessage();
            return [];
        }
    }

    // Method to check if a payment already exists for a student
    private function getPaymentByStudentNumber($student_number) {
        $sql = "SELECT * FROM payments WHERE student_number = :student_number";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_number' => $student_number]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
