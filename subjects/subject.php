<?php
require '../db/db_connection3.php';

class Subject {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Handle create subject request
    public function handleCreateSubjectRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $title = $_POST['title'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            $units = $_POST['units'] ?? '';
            $semester_id = $_POST['semester_id'] ?? ''; // Add semester_id
            $school_year_id = $_POST['school_year_id'] ?? ''; // Add school_year_id

            // Basic validation (you can enhance this as needed)
            if (!empty($code) && !empty($title) && !empty($section_id) && is_numeric($units) && !empty($semester_id) && !empty($school_year_id)) {
                if ($this->create($code, $title, $section_id, $units, $semester_id, $school_year_id)) {
                    header('Location: read_subjects.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to create subject.';
                }
            } else {
                echo 'Invalid input.';
            }
        } else {
            echo 'Invalid request method.';
        }
    }

    // Create a new subject
    public function create($code, $title, $section_id, $units, $semester_id, $school_year_id) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO subjects (code, title, section_id, units, semester_id, school_year_id) VALUES (:code, :title, :section_id, :units, :semester_id, :school_year_id)');
            return $stmt->execute([
                'code' => $code,
                'title' => $title,
                'section_id' => $section_id,
                'units' => $units,
                'semester_id' => $semester_id,
                'school_year_id' => $school_year_id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function find($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM subjects WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    // Handle update subject request
    public function handleUpdateSubjectRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $code = $_POST['code'] ?? '';
            $title = $_POST['title'] ?? '';
            $section_id = $_POST['section_id'] ?? '';
            $units = $_POST['units'] ?? '';
            $semester_id = $_POST['semester_id'] ?? ''; // Add semester_id
            $school_year_id = $_POST['school_year_id'] ?? ''; // Add school_year_id

            // Basic validation
            if (!empty($id) && !empty($code) && !empty($title) && !empty($section_id) && is_numeric($units) && !empty($semester_id) && !empty($school_year_id)) {
                if ($this->update($id, $code, $title, $section_id, $units, $semester_id, $school_year_id)) {
                    header('Location: read_subjects.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to update subject.';
                }
            } else {
                echo 'Invalid input.';
            }
        } else {
            echo 'Invalid request method.';
        }
    }

    // Update a subject
    public function update($id, $code, $title, $section_id, $units, $semester_id, $school_year_id) {
        try {
            $stmt = $this->pdo->prepare('UPDATE subjects SET code = :code, title = :title, section_id = :section_id, units = :units, semester_id = :semester_id, school_year_id = :school_year_id WHERE id = :id');
            return $stmt->execute([
                'id' => $id,
                'code' => $code,
                'title' => $title,
                'section_id' => $section_id,
                'units' => $units,
                'semester_id' => $semester_id,
                'school_year_id' => $school_year_id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }



    
    // Get all school years
    public function getAllSchoolYears() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM school_years');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Get all sections
public function getAllSections() {
    try {
        $stmt = $this->pdo->query('SELECT * FROM sections');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Get all semesters
public function getAllSemesters() {
    try {
        $stmt = $this->pdo->query('SELECT * FROM semesters');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

    // Read all subjects with section names, semester names, and school year names
    public function read() {
        try {
            $stmt = $this->pdo->query('
                SELECT subjects.id, subjects.code, subjects.title, sections.name AS section_name, subjects.units, semesters.semester_name, school_years.year
                FROM subjects
                LEFT JOIN sections ON subjects.section_id = sections.id
                LEFT JOIN semesters ON subjects.semester_id = semesters.id
                LEFT JOIN school_years ON subjects.school_year_id = school_years.id
            ');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
    


    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM subjects WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false; // Return false on error
        }
    }
}
?>
