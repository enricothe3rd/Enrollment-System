<?php
require '../db/db_connection3.php';

class Subject {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Handle create subject request
    public function handleCreateSubjectRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $title = $_POST['title'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            $units = $_POST['units'] ?? '';
            $semester_id = $_POST['semester_id'] ?? ''; // Add semester_id

            // Basic validation (you can enhance this as needed)
            if (!empty($code) && !empty($title) && !empty($section_id) && is_numeric($units) && !empty($semester_id)) {
                if ($this->create($code, $title, $section_id, $units, $semester_id)) {
                    header('Location: read_subjects.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to create subject.';
                }
            } else {
                echo 'Invalid input.';
            }
        } else {
            echo 'Invalid request method.';
        }
    }
    
    // Create a new subject
    public function create($code, $title, $section_id, $units, $semester_id) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO subjects (code, title, section_id, units, semester_id) VALUES (:code, :title, :section_id, :units, :semester_id)');
            return $stmt->execute([
                'code' => $code,
                'title' => $title,
                'section_id' => $section_id,
                'units' => $units,
                'semester_id' => $semester_id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Read all subjects with section names and semester names
    public function read() {
        $stmt = $this->pdo->query('
            SELECT subjects.id, subjects.code, subjects.title, sections.name AS section_name, subjects.units, semesters.semester_name
            FROM subjects
            JOIN sections ON subjects.section_id = sections.id
            JOIN semesters ON subjects.semester_id = semesters.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Handle update subject request
    public function handleUpdateSubjectRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $code = $_POST['code'] ?? '';
            $title = $_POST['title'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            $units = $_POST['units'] ?? '';
            $semester_id = $_POST['semester_id'] ?? ''; // Add semester_id

            // Basic validation
            if (!empty($id) && !empty($code) && !empty($title) && !empty($section_id) && is_numeric($units) && !empty($semester_id)) {
                if ($this->update($id, $code, $title, $section_id, $units, $semester_id)) {
                    header('Location: read_subjects.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to update subject.';
                }
            } else {
                echo 'Invalid input.';
            }
        } else {
            echo 'Invalid request method.';
        }
    }

    // Update a subject
    public function update($id, $code, $title, $section_id, $units, $semester_id) {
        try {
            $stmt = $this->pdo->prepare('UPDATE subjects SET code = :code, title = :title, section_id = :section_id, units = :units, semester_id = :semester_id WHERE id = :id');
            return $stmt->execute([
                'id' => $id,
                'code' => $code,
                'title' => $title,
                'section_id' => $section_id,
                'units' => $units,
                'semester_id' => $semester_id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Delete a subject
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM subjects WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Get all sections
    public function getAllSections() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM sections');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Get all semesters
    public function getAllSemesters() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM semesters');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Find a subject by ID
    public function find($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM subjects WHERE id = :id');
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Get subjects by semester
    public function getSubjectsBySemester($semester_id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM subjects WHERE semester_id = :semester_id');
            $stmt->execute(['semester_id' => $semester_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Get subjects by semester and course
    public function getSubjectsBySemesterAndCourse($semesterId, $courseId) {
        try {
            $stmt = $this->pdo->prepare('
                SELECT s.code, s.title, s.units, s.room, s.day_of_week, s.start_time, s.end_time
                FROM subjects s
                JOIN sections sec ON s.section_id = sec.id
                JOIN courses c ON sec.course_id = c.id
                WHERE s.semester_id = :semester_id AND c.id = :course_id
            ');
            $stmt->execute(['semester_id' => $semesterId, 'course_id' => $courseId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
?>
