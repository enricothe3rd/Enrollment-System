<?php

require_once '../classes/Subject.php';

class SubjectController {
    private $pdo;
    private $subject;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->subject = new Subject($pdo);
    }

    public function getSubjectsBySection($section_id) {
        return $this->subject->getSubjectsBySection($section_id);
    }

    public function getScheduleBySubject($subject_id) {
        return $this->subject->getScheduleBySubject($subject_id);
    }
}
?>
