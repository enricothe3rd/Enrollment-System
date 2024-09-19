<?php
require '../../db/db_connection3.php';

class Subject {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function create($code, $title, $section_id, $units, $semester_id, $price) {
        $sql = 'INSERT INTO subjects (code, title, section_id, units, semester_id, price) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$code, $title, $section_id, $units, $semester_id, $price]);
    }

    public function getAll() {
        $sql = 'SELECT * FROM subjects';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = 'SELECT * FROM subjects WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePrice($id, $price) {
        $sql = 'UPDATE subjects SET price = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$price, $id]);
    }

    public function delete($id) {
        $sql = 'DELETE FROM subjects WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Method to get sections
    public function getSections() {
        $sql = 'SELECT id, name FROM sections';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get courses
    public function getCourses() {
        $sql = 'SELECT id, name FROM courses';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
