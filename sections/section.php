<?php
require '../db/db_connection3.php';

class Section {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

        // Method to handle the section creation request
        public function handleCreateSectionRequest() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $course_id = $_POST['course_id'];

                if ($this->create($name, $course_id)) {
                    header('Location: read_sections.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to create section.';
                }
            }
        }

        // create_section.php
        public function create($name, $course_id) {
            try {
                $stmt = $this->pdo->prepare('INSERT INTO sections (name, course_id) VALUES (:name, :course_id)');
                return $stmt->execute([':name' => $name, ':course_id' => $course_id]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        // create_section.php
        public function getAllCourses() {
            try {
                $stmt = $this->pdo->query('SELECT * FROM courses');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }

        // Method to handle the section update request
        public function handleUpdateSectionRequest() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];  // Retrieve the section ID from POST data
                $name = $_POST['name'];
                $course_id = $_POST['course_id'];
        
                if ($this->update($id, $name, $course_id)) {
                    header('Location: read_sections.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to update section.';
                }
            }
        }
    
        // For update_section.php
        public function update($id, $name, $course_id) {
            try {
                $stmt = $this->pdo->prepare('UPDATE sections SET name = :name, course_id = :course_id WHERE id = :id');
                return $stmt->execute([':name' => $name, ':course_id' => $course_id, ':id' => $id]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        // For update_section.php
        public function getSectionById($id) {
            $stmt = $this->pdo->prepare('SELECT * FROM sections WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }
    
        // read_sections.php
        public function getCourseName($courseId) {
            $query = "SELECT course_name FROM courses WHERE id = :course_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':course_id', $courseId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['course_name'] ?? 'Unknown';
        }

        // read_sections.php
        public function getAllSections() {
            try {
                $stmt = $this->pdo->query('SELECT * FROM sections');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return [];
            }
        }

        // delete_section.php
        public function delete($id) {
            try {
                $stmt = $this->pdo->prepare('DELETE FROM sections WHERE id = :id');
                return $stmt->execute([':id' => $id]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

}
?>
