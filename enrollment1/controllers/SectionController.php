<?php

require_once '../classes/Section.php';

class SectionController {
    private $pdo;
    private $section;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->section = new Section($pdo);
    }

    public function getSectionsByCourse($course_id) {
        return $this->section->getSectionsByCourse($course_id);
    }
}
?>
