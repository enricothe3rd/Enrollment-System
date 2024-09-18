<?php

class Enrollment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

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

    public function enrollStudent($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO enrollments 
            (student_number, firstname, middlename, lastname, suffix, student_type, sex, dob, email, contact_no, course_id, section_id, type_of_student) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['student_number'], $data['firstname'], $data['middlename'], $data['lastname'], 
            $data['suffix'], $data['student_type'], $data['sex'], $data['dob'], 
            $data['email'], $data['contact_no'], $data['course_id'], $data['section_id'], 
            $data['type_of_student']
        ]);
    }

    public function updateEnrollment($data)
    {
        $sql = "UPDATE enrollments SET
                    student_number = :student_number,
                    firstname = :firstname,
                    middlename = :middlename,
                    lastname = :lastname,
                    suffix = :suffix,
                    student_type = :student_type,
                    sex = :sex,
                    dob = :dob,
                    email = :email,
                    contact_no = :contact_no,
                    course_id = :course_id,
                    section_id = :section_id,
                    type_of_student = :type_of_student
                WHERE id = :id";
                
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $data['id'],
            ':student_number' => $data['student_number'],
            ':firstname' => $data['firstname'],
            ':middlename' => $data['middlename'],
            ':lastname' => $data['lastname'],
            ':suffix' => $data['suffix'],
            ':student_type' => $data['student_type'],
            ':sex' => $data['sex'],
            ':dob' => $data['dob'],
            ':email' => $data['email'],
            ':contact_no' => $data['contact_no'],
            ':course_id' => $data['course_id'],
            ':section_id' => $data['section_id'],
            ':type_of_student' => $data['type_of_student']
        ]);
    }

    public function getEnrollmentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM enrollments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

}
?>
