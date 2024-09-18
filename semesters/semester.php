<?php
require '../db/db_connection3.php';

class Semester {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Method to create a new semester
    public function handleCreateSemesterRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $semester_name = $_POST['semester_name'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            if ($this->createSemester($semester_name, $start_date, $end_date)) {
                header('Location: read_semesters.php'); // Redirect after creation
                exit();
            } else {
                echo 'Failed to create semester.';
            }
        }
    }

    public function createSemester($semester_name, $start_date, $end_date) {
        try {
            $sql = "INSERT INTO semesters (semester_name, start_date, end_date) VALUES (:semester_name, :start_date, :end_date)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':semester_name' => $semester_name, ':start_date' => $start_date, ':end_date' => $end_date]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to update an existing semester
    public function handleUpdateSemesterRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $semester_name = $_POST['semester_name'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            if ($this->updateSemester($id, $semester_name, $start_date, $end_date)) {
                header('Location: read_semesters.php'); // Redirect after update
                exit();
            } else {
                echo 'Failed to update semester.';
            }
        }
    }

    public function updateSemester($id, $semester_name, $start_date, $end_date) {
        try {
            $sql = "UPDATE semesters SET semester_name = :semester_name, start_date = :start_date, end_date = :end_date WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':semester_name' => $semester_name,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to read all semesters
    public function getSemesters() {
        $sql = "SELECT * FROM semesters";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get a semester by ID
    public function getSemesterById($id) {
        $sql = "SELECT * FROM semesters WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Method to delete a semester
    public function deleteSemester($id) {
        try {
            $sql = "DELETE FROM semesters WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
