
<?php
require '../db/db_connection3.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }
// Method to handle the course creation request
public function handleCreateCourseRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $course_name = $_POST['course_name'];
        $department_id = $_POST['department_id'];

        // Validate course name to allow only alphanumeric characters and spaces
        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $course_name)) {
            // Redirect with an error message if validation fails
            header('Location: create_course.php?message=invalid_name');
            exit();
        }

        // Check if the course already exists
        if ($this->courseExists($course_name, $department_id)) {
            // Redirect with an error message if the course already exists
            header('Location: create_course.php?message=course_exists');
            exit();
        }

        // If it doesn't exist, create the course
        if ($this->createCourse($course_name, $department_id)) {
            // Redirect to a success page
            header('Location: read_courses.php?message=success');
            exit();
        } else {
            // Redirect with an error message
            header('Location: create_course.php?message=failure');
            exit();
        }
    }
}

// Method to check if a course already exists
public function courseExists($name, $department_id) {
    try {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM courses WHERE course_name = :name AND department_id = :department_id');
        $stmt->execute([':name' => $name, ':department_id' => $department_id]);
        $count = $stmt->fetchColumn();
        return $count > 0; // Returns true if the course exists
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Method to create a new course
public function createCourse($name, $department_id) {
    try {
        $sql = "INSERT INTO courses (course_name, department_id) VALUES (:name, :department_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $name, ':department_id' => $department_id]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}



















    

    // Method to handle the course update request
    public function handleUpdateCourseRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $course_name = $_POST['course_name'];
            $department_id = $_POST['department_id'];

            if ($this->updateCourse($id, $course_name, $department_id)) {
                header('Location: read_courses.php'); // Redirect to a success page
                exit();
            } else {
                echo 'Failed to update course.';
            }
        }
    }

    public function updateCourse($id, $name, $department_id) {
        $sql = "UPDATE courses SET course_name = :name, department_id = :department_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $name, ':department_id' => $department_id, ':id' => $id]);
    }

    public function getCourses() {
        $sql = "SELECT courses.id, courses.course_name, departments.name AS department_name
                FROM courses
                JOIN departments ON courses.department_id = departments.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id) {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }


    public function deleteCourse($id) {
        $sql = "DELETE FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // New method to get departments
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
