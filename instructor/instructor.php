<?php
require '../db/db_connection3.php';

class Instructor {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Method to handle the instructor creation request
    public function handleCreateInstructorRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $department_id = $_POST['department_id'];

            if ($this->createInstructor($first_name, $last_name, $email, $department_id)) {
                header('Location: read_instructor.php'); // Redirect to a success page
                exit();
            } else {
                echo 'Failed to create instructor.';
            }
        }
    }

    public function createInstructor($first_name, $last_name, $email, $department_id) {
        try {
            $sql = "INSERT INTO instructors (first_name, last_name, email, department_id) VALUES (:first_name, :last_name, :email, :department_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':first_name' => $first_name,
                ':last_name'  => $last_name,
                ':email'      => $email,
                ':department_id' => $department_id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to handle the instructor update request
    public function handleUpdateInstructorRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $department_id = $_POST['department_id'];

            if ($this->updateInstructor($id, $first_name, $last_name, $email, $department_id)) {
                header('Location: read_instructor.php'); // Redirect to a success page
                exit();
            } else {
                echo 'Failed to update instructor.';
            }
        }
    }

    public function updateInstructor($id, $first_name, $last_name, $email, $department_id) {
        try {
            $sql = "UPDATE instructors SET first_name = :first_name, last_name = :last_name, email = :email, department_id = :department_id WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':first_name' => $first_name,
                ':last_name'  => $last_name,
                ':email'      => $email,
                ':department_id' => $department_id,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getInstructors() {
        try {
            $sql = "SELECT instructors.id, instructors.first_name, instructors.last_name, instructors.email, departments.name AS department_name
                    FROM instructors
                    JOIN departments ON instructors.department_id = departments.id";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getInstructorById($id) {
        try {
            $sql = "SELECT * FROM instructors WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function deleteInstructor($id) {
        try {
            $sql = "DELETE FROM instructors WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to retrieve departments for the form dropdown
    public function getDepartments() {
        try {
            $sql = "SELECT id, name FROM departments";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
?>
