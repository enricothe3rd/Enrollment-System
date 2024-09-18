<?php

class Course {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCourses() {
        $stmt = $this->pdo->query("SELECT id, course_name FROM courses");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
