<?php

class Subject {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getSubjectsBySection($section_id) {
        $stmt = $this->pdo->prepare("SELECT id, code, title FROM subjects WHERE section_id = ?");
        $stmt->execute([$section_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleBySubject($subject_id) {
        $stmt = $this->pdo->prepare("SELECT day_of_week, start_time, end_time, room FROM schedules WHERE subject_id = ?");
        $stmt->execute([$subject_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
