<?php
require '../db/db_connection2.php';

class Subject {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    public function create($code, $title, $course_id, $units) {
        $stmt = $this->pdo->prepare('INSERT INTO subjects (code, title, course_id, units) VALUES (:code, :title, :course_id, :units)');
        $stmt->execute(['code' => $code, 'title' => $title, 'course_id' => $course_id, 'units' => $units]);
    }

    public function read() {
        $stmt = $this->pdo->query('SELECT * FROM subjects');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM subjects WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $code, $title, $course_id, $units) {
        $stmt = $this->pdo->prepare('UPDATE subjects SET code = :code, title = :title, course_id = :course_id, units = :units WHERE id = :id');
        $stmt->execute(['code' => $code, 'title' => $title, 'course_id' => $course_id, 'units' => $units, 'id' => $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM subjects WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
?>
