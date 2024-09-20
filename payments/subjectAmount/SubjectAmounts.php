<?php
require '../../db/db_connection3.php';  // Assuming this connects to the database

class SubjectAmount {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();  // Create the connection
    }

    // Fetch all departments
    public function getDepartments() {
        $stmt = $this->pdo->query("SELECT id, name FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // Fetch courses by department
    public function getCoursesByDepartment($department_id) {
        $stmt = $this->pdo->prepare("SELECT id, course_name FROM courses WHERE department_id = :department_id");
        $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch sections by course
    public function getSectionsByCourse($course_id) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM sections WHERE course_id = :course_id");
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Fetch subjects by section
// Fetch subjects by section
public function getSubjectsBySection($section_id) {
    $stmt = $this->pdo->prepare("SELECT id, code, title, units, semester_id FROM subjects WHERE section_id = :section_id");
    $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Add an amount to a subject
    public function addAmount($subject_id, $amount) {
        $stmt = $this->pdo->prepare("INSERT INTO subject_amounts (subject_id, amount) VALUES (:subject_id, :amount)");
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>
