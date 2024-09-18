<?php

class Section {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getSectionsByCourse($course_id) {
        $stmt = $this->pdo->prepare("SELECT id, name FROM sections WHERE course_id = ?");
        $stmt->execute([$course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
