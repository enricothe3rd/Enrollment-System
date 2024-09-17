<?php
require '../db/db_connection3.php';

class Subject {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function handleCreateSubjectRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $title = $_POST['title'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            $units = $_POST['units'] ?? '';

            // Basic validation (you can enhance this as needed)
            if (!empty($code) && !empty($title) && !empty($section_id) && is_numeric($units)) {
                $this->create($code, $title, $section_id, $units);
                header('Location: read_subjects.php'); // Redirect to a s
            } else {
                echo 'Invalid input.';
            }
        } else {
            echo 'Invalid request method.';
        }
    }
    
    public function create($code, $title, $section_id, $units) {
        $stmt = $this->pdo->prepare('INSERT INTO subjects (code, title, section_id, units) VALUES (:code, :title, :section_id, :units)');
        $stmt->execute(['code' => $code, 'title' => $title, 'section_id' => $section_id, 'units' => $units]);
    }

    public function read() {
        $stmt = $this->pdo->query('
            SELECT subjects.id, subjects.code, subjects.title, sections.name AS section_name, subjects.units
            FROM subjects
            JOIN sections ON subjects.section_id = sections.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Method to handle the subject update request
    public function handleUpdateSubjectRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $code = $_POST['code'] ?? '';
            $title = $_POST['title'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            $units = $_POST['units'] ?? '';

            // Basic validation
            if (!empty($id) && !empty($code) && !empty($title) && !empty($section_id) && is_numeric($units)) {
                if ($this->update($id, $code, $title, $section_id, $units)) {
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

    // Method to get all sections
    public function getAllSections() {
        $stmt = $this->pdo->query('SELECT * FROM sections');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to find a subject by ID
    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM subjects WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to update a subject
    public function update($id, $code, $title, $section_id, $units) {
        $stmt = $this->pdo->prepare('UPDATE subjects SET code = :code, title = :title, section_id = :section_id, units = :units WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'code' => $code,
            'title' => $title,
            'section_id' => $section_id,
            'units' => $units
        ]);
    }






    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM subjects WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
?>
