<?php

require_once '../classes/Enrollment.php';
require_once '../classes/Course.php';

class EnrollmentController {
    private $pdo;
    private $enrollment;
    private $course;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->enrollment = new Enrollment($pdo);
        $this->course = new Course($pdo);
    }

    public function generateStudentNumber() {
        return $this->enrollment->generateStudentNumber();
    }

    public function enrollStudent($data) {
        return $this->enrollment->enrollStudent($data);
    }

    public function getAllCourses() {
        return $this->course->getAllCourses();
    }
}
?>
