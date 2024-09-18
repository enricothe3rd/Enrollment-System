<?php
require '../db/db_connection3.php';

class Enrollment {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Method to create a new enrollment
    public function handleCreateEnrollmentRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_number = $this->generateStudentNumber();
            $firstname = $_POST['firstname'];
            $middlename = $_POST['middlename'];
            $lastname = $_POST['lastname'];
            $suffix = $_POST['suffix'];
            $student_type = $_POST['student_type']; // regular, new student, irregular, summer
            $sex = $_POST['sex'];
            $dob = $_POST['dob'];
            $email = $_POST['email'];
            $contact_no = $_POST['contact_no'];
            $course_id = $_POST['course_id'];
            $section_id = $_POST['section_id']; // Selected section after course
            $subject_ids = $_POST['subject_ids']; // Selected subjects within the section

            if ($this->createEnrollment($student_number, $firstname, $middlename, $lastname, $suffix, $student_type, $sex, $dob, $email, $contact_no, $course_id, $section_id, $subject_ids)) {
                header('Location: read_enrollments.php'); // Redirect after creation
                exit();
            } else {
                echo 'Failed to create enrollment.';
            }
        }
    }

    // Method to generate a unique student number
    private function generateStudentNumber() {
        // Implement student number generation logic (e.g., based on current year and unique ID)
        return 'SN' . time(); // Example: SN + current timestamp
    }

    public function createEnrollment($student_number, $firstname, $middlename, $lastname, $suffix, $student_type, $sex, $dob, $email, $contact_no, $course_id, $section_id, $subject_ids) {
        try {
            $sql = "INSERT INTO enrollments (student_number, firstname, middlename, lastname, suffix, student_type, sex, dob, email, contact_no, course_id, section_id, created_at, updated_at) 
                    VALUES (:student_number, :firstname, :middlename, :lastname, :suffix, :student_type, :sex, :dob, :email, :contact_no, :course_id, :section_id, NOW(), NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':student_number' => $student_number,
                ':firstname' => $firstname,
                ':middlename' => $middlename,
                ':lastname' => $lastname,
                ':suffix' => $suffix,
                ':student_type' => $student_type,
                ':sex' => $sex,
                ':dob' => $dob,
                ':email' => $email,
                ':contact_no' => $contact_no,
                ':course_id' => $course_id,
                ':section_id' => $section_id
            ]);

            // Insert subjects for this enrollment
            $enrollment_id = $this->db->lastInsertId();
            foreach ($subject_ids as $subject_id) {
                $this->addSubjectToEnrollment($enrollment_id, $subject_id);
            }

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    private function addSubjectToEnrollment($enrollment_id, $subject_id) {
        try {
            $sql = "INSERT INTO enrollment_subjects (enrollment_id, subject_id) VALUES (:enrollment_id, :subject_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':enrollment_id' => $enrollment_id, ':subject_id' => $subject_id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Method to update an existing enrollment
    public function handleUpdateEnrollmentRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'];
            $middlename = $_POST['middlename'];
            $lastname = $_POST['lastname'];
            $suffix = $_POST['suffix'];
            $student_type = $_POST['student_type'];
            $sex = $_POST['sex'];
            $dob = $_POST['dob'];
            $email = $_POST['email'];
            $contact_no = $_POST['contact_no'];
            $course_id = $_POST['course_id'];
            $section_id = $_POST['section_id'];
            $subject_ids = $_POST['subject_ids'];

            if ($this->updateEnrollment($id, $firstname, $middlename, $lastname, $suffix, $student_type, $sex, $dob, $email, $contact_no, $course_id, $section_id, $subject_ids)) {
                header('Location: read_enrollments.php'); // Redirect after update
                exit();
            } else {
                echo 'Failed to update enrollment.';
            }
        }
    }

    public function updateEnrollment($id, $firstname, $middlename, $lastname, $suffix, $student_type, $sex, $dob, $email, $contact_no, $course_id, $section_id, $subject_ids) {
        try {
            $sql = "UPDATE enrollments SET firstname = :firstname, middlename = :middlename, lastname = :lastname, suffix = :suffix, student_type = :student_type, sex = :sex, dob = :dob, email = :email, contact_no = :contact_no, course_id = :course_id, section_id = :section_id, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':firstname' => $firstname,
                ':middlename' => $middlename,
                ':lastname' => $lastname,
                ':suffix' => $suffix,
                ':student_type' => $student_type,
                ':sex' => $sex,
                ':dob' => $dob,
                ':email' => $email,
                ':contact_no' => $contact_no,
                ':course_id' => $course_id,
                ':section_id' => $section_id,
                ':id' => $id
            ]);

            // Update subjects for this enrollment
            $this->updateEnrollmentSubjects($id, $subject_ids);

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    private function updateEnrollmentSubjects($enrollment_id, $subject_ids) {
        try {
            // First, remove all existing subjects for the enrollment
            $sql = "DELETE FROM enrollment_subjects WHERE enrollment_id = :enrollment_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':enrollment_id' => $enrollment_id]);

            // Add new subjects
            foreach ($subject_ids as $subject_id) {
                $this->addSubjectToEnrollment($enrollment_id, $subject_id);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Method to read all enrollments
    public function getEnrollments() {
        $sql = "SELECT e.*, c.course_name, s.name as section_name FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                JOIN sections s ON e.section_id = s.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get an enrollment by ID
    public function getEnrollmentById($id) {
        $sql = "SELECT * FROM enrollments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to delete an enrollment
    public function deleteEnrollment($id) {
        try {
            $sql = "DELETE FROM enrollments WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to get sections based on the selected course
    public function getSectionsByCourse($course_id) {
        $sql = "SELECT * FROM sections WHERE course_id = :course_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':course_id' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get subjects based on the selected section
    public function getSubjectsBySection($section_id) {
        $sql = "SELECT * FROM subjects WHERE section_id = :section_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':section_id' => $section_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get all courses
    public function getCourses() {
        $sql = "SELECT * FROM courses";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSchedulesBySection($section_id) {
        $query = "SELECT * FROM schedules WHERE section_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$section_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
