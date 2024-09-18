<?php

class Enrollment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Generate a new student number
    public function generateStudentNumber() {
        $stmt = $this->pdo->query("SELECT student_number FROM enrollments ORDER BY id DESC LIMIT 1");
        $lastStudent = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lastStudent) {
            $lastNumber = intval(substr($lastStudent['student_number'], -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return date('Y') . '-' . $newNumber;
    }

    // Insert enrollment data
    public function enrollStudent($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO enrollments (student_number, firstname, middlename, lastname, suffix, student_type, sex, dob, email, contact_no, course_id, section_id, type_of_student) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['student_number'], $data['firstname'], $data['middlename'], $data['lastname'], 
            $data['suffix'], $data['student_type'], $data['sex'], $data['dob'], 
            $data['email'], $data['contact_no'], $data['course_id'], $data['section_id'], 
            $data['type_of_student']
        ]);
    }



    public function updateEnrollment($data) {
        $stmt = $this->pdo->prepare("
            UPDATE enrollments 
            SET student_number = ?, firstname = ?, middlename = ?, lastname = ?, suffix = ?, 
                student_type = ?, sex = ?, dob = ?, email = ?, contact_no = ?, 
                course_id = ?, section_id = ?, type_of_student = ?
            WHERE id = ?
        ");
    
        return $stmt->execute([
            $data['student_number'], $data['firstname'], $data['middlename'], $data['lastname'], 
            $data['suffix'], $data['student_type'], $data['sex'], $data['dob'], 
            $data['email'], $data['contact_no'], $data['course_id'], $data['section_id'], 
            $data['type_of_student'], $data['id']
        ]);
    }
    
}
?>
