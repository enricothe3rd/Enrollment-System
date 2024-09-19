<?php
require '../db/db_connection3.php'; // Adjust the path if necessary

class InstructorSubject {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getDepartments() {
        $stmt = $this->db->query("SELECT * FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursesByDepartment($department_id) {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE department_id = :department_id");
        $stmt->bindParam(':department_id', $department_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSectionsByCourse($course_id) {
        $stmt = $this->db->prepare("SELECT * FROM sections WHERE course_id = :course_id");
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjectsBySectionAndSemester($section_id, $semester_id) {
        $stmt = $this->db->prepare("SELECT * FROM subjects WHERE section_id = :section_id AND semester_id = :semester_id");
        $stmt->bindParam(':section_id', $section_id);
        $stmt->bindParam(':semester_id', $semester_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInstructors() {
        $stmt = $this->db->query("SELECT * FROM instructors");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubjects() {
        $stmt = $this->db->query("SELECT * FROM subjects");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSemesters() {
        $stmt = $this->db->query("SELECT * FROM semesters"); // Adjust based on your actual table structure
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSubjectToInstructor($instructor_id, $subject_id, $semester_id) {
        $stmt = $this->db->prepare("INSERT INTO instructor_subjects (instructor_id, subject_id, semester_id) VALUES (:instructor_id, :subject_id, :semester_id)");
        $stmt->bindParam(':instructor_id', $instructor_id);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':semester_id', $semester_id);
        $stmt->execute();
    }

   
    public function read() {
        $sql = "
            SELECT 
                ins_sub.id, 
                CONCAT(i.first_name, ' ', i.last_name) AS instructor_name, 
                s.title AS subject_name, 
                sm.semester_name
            FROM instructor_subjects ins_sub
            JOIN instructors i ON ins_sub.instructor_id = i.id
            JOIN subjects s ON ins_sub.subject_id = s.id
            JOIN semesters sm ON ins_sub.semester_id = sm.id
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAssignmentById($id) {
        $stmt = $this->db->prepare("
            SELECT 
                ins_sub.id, 
                ins_sub.instructor_id, 
                CONCAT(i.first_name, ' ', i.last_name) AS instructor_name, 
                ins_sub.subject_id, 
                s.title AS subject_name, 
                ins_sub.semester_id, 
                sm.semester_name
            FROM instructor_subjects ins_sub
            JOIN instructors i ON ins_sub.instructor_id = i.id
            JOIN subjects s ON ins_sub.subject_id = s.id
            JOIN semesters sm ON ins_sub.semester_id = sm.id
            WHERE ins_sub.id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function updateAssignment($id, $instructor_id, $subject_id, $semester_id) {
        $stmt = $this->db->prepare("UPDATE instructor_subjects SET instructor_id = :instructor_id, subject_id = :subject_id, semester_id = :semester_id WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':instructor_id', $instructor_id);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':semester_id', $semester_id);
        $stmt->execute();
    }
    
    public function deleteAssignment($id) {
        $stmt = $this->db->prepare("DELETE FROM instructor_subjects WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
    
}
?>
