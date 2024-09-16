<?php
require 'db/db_connection1.php'; // Adjust the path as needed

class SubjectEnrollment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function updatePaymentStatus($studentNumber, $paymentStatus) {
        if (!in_array($paymentStatus, ['pending', 'completed'])) {
            return "Invalid payment status.";
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE subject_enrollments 
                SET payment_status = :payment_status 
                WHERE student_id IN (
                    SELECT id 
                    FROM users 
                    WHERE student_number = :student_number
                )
            ");
            $stmt->bindParam(':payment_status', $paymentStatus, PDO::PARAM_STR);
            $stmt->bindParam(':student_number', $studentNumber, PDO::PARAM_STR);
            $stmt->execute();
            return "Payment status updated successfully.";
        } catch (PDOException $e) {
            return "Error updating payment status: " . $e->getMessage();
        }
    }
}

// Check if the database connection is successful
if (!isset($pdo)) {
    die("Database connection failed.");
}

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentNumber = $_POST['student_number'] ?? '';
    $paymentStatus = $_POST['payment_status'] ?? '';

    $enrollment = new SubjectEnrollment($pdo);
    $message = $enrollment->updatePaymentStatus($studentNumber, $paymentStatus);
    echo $message;
} else {
    echo "Invalid request method.";
}

// Close connection
$pdo = null;
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment Status</title>
</head>
<body>
<form action="update_payment_status.php" method="post">
    Student Number: <input type="text" name="student_number" required><br>
    Payment Status: 
    <select name="payment_status">
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
    </select><br>
    <input type="submit" value="Update Payment Status">
</form>
</body>
</html>
