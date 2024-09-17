<?php
require '../db/db_connection3.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function createCourse($name, $department_id) {
        try {
            $sql = "INSERT INTO courses (course_name, department_id) VALUES (:name, :department_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':name' => $name, ':department_id' => $department_id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getCourses() {
        $sql = "SELECT courses.id, courses.course_name, departments.name AS department_name
                FROM courses
                JOIN departments ON courses.department_id = departments.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id) {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateCourse($id, $name, $department_id) {
        $sql = "UPDATE courses SET course_name = :name, department_id = :department_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $name, ':department_id' => $department_id, ':id' => $id]);
    }

    public function deleteCourse($id) {
        $sql = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // New method to get departments
    public function getDepartments() {
        try {
            $sql = "SELECT id, name FROM departments";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
?>
