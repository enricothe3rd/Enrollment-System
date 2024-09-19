<?php
require '../db/db_connection3.php'; // Adjust the path if necessary

class Instructor {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Read all instructors with department name
    public function read($id) {
        $sql = 'SELECT i.id, i.first_name, i.middle_name, i.last_name, i.suffix, i.email, i.department_id, i.course_id, i.section_id,
                        d.name AS department_name, c.course_name, s.name AS section_name
                 FROM instructors i
                 LEFT JOIN departments d ON i.department_id = d.id
                 LEFT JOIN courses c ON i.course_id = c.id
                 LEFT JOIN sections s ON i.section_id = s.id
                 WHERE i.id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readAll() {
        $sql = 'SELECT i.id, i.first_name, i.middle_name, i.last_name, i.suffix, i.email, i.department_id, i.course_id, i.section_id,
                        d.name AS department_name, c.course_name, s.name AS section_name, i.created_at, i.updated_at
                 FROM instructors i
                 LEFT JOIN departments d ON i.department_id = d.id
                 LEFT JOIN courses c ON i.course_id = c.id
                 LEFT JOIN sections s ON i.section_id = s.id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId) {
        $sql = 'UPDATE instructors
                SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, suffix = :suffix, email = :email, department_id = :department_id, course_id = :course_id, section_id = :section_id
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'suffix' => $suffix,
            'email' => $email,
            'department_id' => $departmentId,
            'course_id' => $courseId,
            'section_id' => $sectionId,
            'id' => $id
        ]);
    }
    // Create a new instructor
    public function create($firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId) {
        $sql = "INSERT INTO instructors (first_name, middle_name, last_name, suffix, email, department_id, course_id, section_id)
                VALUES (:first_name, :middle_name, :last_name, :suffix, :email, :department_id, :course_id, :section_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $firstName,
            ':middle_name' => $middleName,
            ':last_name' => $lastName,
            ':suffix' => $suffix,
            ':email' => $email,
            ':department_id' => $departmentId,
            ':course_id' => $courseId,
            ':section_id' => $sectionId,
        ]);
    }

    // Delete an instructor by ID
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM instructors WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $stmt->rowCount() > 0; // Returns true if a row was deleted
            }
        } catch (PDOException $e) {
            error_log('Error in delete(): ' . $e->getMessage());
            return false; // Return false on failure
        }
    }

    // Fetch departments for dropdown
    public function getDepartments() {
        $query = "SELECT id, name FROM departments";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch courses for dropdown
    public function getCourses() {
        $query = "SELECT id, course_name FROM courses";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch sections for dropdown
    public function getSections() {
        $query = "SELECT id, name FROM sections";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch courses based on department
    public function getCoursesByDepartment($departmentId) {
        $query = "SELECT id, course_name FROM courses WHERE department_id = :department_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':department_id' => $departmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch sections based on course
    public function getSectionsByCourse($courseId) {
        $query = "SELECT id, name FROM sections WHERE course_id = :course_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':course_id' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
