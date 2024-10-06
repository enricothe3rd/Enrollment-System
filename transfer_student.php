<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=token_db1'; // Change to your database name
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch student numbers with completed payments (cash or installment)
    $query = "
        SELECT p.student_number, p.payment_method, e.*, se.*, 
               p.number_of_units, p.amount_per_unit, 
               p.miscellaneous_fee, p.total_payment
        FROM payments p
        JOIN enrollments e ON p.student_number = e.student_number
        JOIN subject_enrollments se ON p.student_number = se.student_number
        WHERE p.payment_method IN ('cash', 'installment');
    ";

    $stmt = $pdo->query($query);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare insert statement for students table
    $insertQuery = "
        INSERT INTO students (
            student_number, firstname, middlename, lastname, suffix, 
            student_type, sex, dob, email, contact_no, 
            address, status, number_of_units, amount_per_unit, 
            miscellaneous_fee, total_payment, payment_method, 
            section_id, department_id, course_id, subject_id, 
            schedule_id, semester, school_year
        ) VALUES (
            :student_number, :firstname, :middlename, :lastname, :suffix,
            :student_type, :sex, :dob, :email, :contact_no,
            :address, :status, :number_of_units, :amount_per_unit,
            :miscellaneous_fee, :total_payment, :payment_method,
            :section_id, :department_id, :course_id, :subject_id,
            :schedule_id, :semester, :school_year
        );
    ";

    $insertStmt = $pdo->prepare($insertQuery);

    // Loop through each student and insert into students table
    foreach ($students as $student) {
        // Check if student_number already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_number = :student_number");
        $checkStmt->execute([':student_number' => $student['student_number']]);
        $exists = $checkStmt->fetchColumn();

        if (!$exists) { // If student does not exist, insert
            $insertStmt->execute([
                ':student_number' => $student['student_number'],
                ':firstname' => $student['firstname'],
                ':middlename' => $student['middlename'],
                ':lastname' => $student['lastname'],
                ':suffix' => $student['suffix'],
                ':student_type' => $student['student_type'],
                ':sex' => $student['sex'],
                ':dob' => $student['dob'],
                ':email' => $student['email'],
                ':contact_no' => $student['contact_no'],
                ':address' => $student['address'],
                ':status' => $student['status'],
                ':number_of_units' => $student['number_of_units'],
                ':amount_per_unit' => $student['amount_per_unit'],
                ':miscellaneous_fee' => $student['miscellaneous_fee'],
                ':total_payment' => $student['total_payment'],
                ':payment_method' => $student['payment_method'],
                ':section_id' => $student['section_id'],
                ':department_id' => $student['department_id'],
                ':course_id' => $student['course_id'],
                ':subject_id' => $student['subject_id'],
                ':schedule_id' => $student['schedule_id'],
                ':semester' => $student['semester'],
                ':school_year' => $student['school_year']
            ]);
        } else {
            // Optionally handle existing student_number case (e.g., update instead)
            echo "Student with student_number " . $student['student_number'] . " already exists.<br>";
        }
    }

    echo "Data has been successfully transferred to the students table.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
