<?php
require '../db/db_connection3.php'; // Adjust the path if necessary

class Instructor {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Read all instructors with department name
    public function read($id) {
        $sql = 'SELECT i.id, i.first_name, i.middle_name, i.last_name, i.suffix, i.email, i.department_id, i.course_id, i.section_id,
                        d.name AS department_name, c.course_name, s.name AS section_name
                 FROM instructors i
                 LEFT JOIN departments d ON i.department_id = d.id
                 LEFT JOIN courses c ON i.course_id = c.id
                 LEFT JOIN sections s ON i.section_id = s.id
                 WHERE i.id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readAll() {
        $sql = 'SELECT i.id, i.first_name, i.middle_name, i.last_name, i.suffix, i.email, i.department_id, i.course_id, i.section_id,
                        d.name AS department_name, c.course_name, s.name AS section_name, i.created_at, i.updated_at
                 FROM instructors i
                 LEFT JOIN departments d ON i.department_id = d.id
                 LEFT JOIN courses c ON i.course_id = c.id
                 LEFT JOIN sections s ON i.section_id = s.id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId) {
        $sql = 'UPDATE instructors
                SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, suffix = :suffix, email = :email, department_id = :department_id, course_id = :course_id, section_id = :section_id
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'suffix' => $suffix,
            'email' => $email,
            'department_id' => $departmentId,
            'course_id' => $courseId,
            'section_id' => $sectionId,
            'id' => $id
        ]);
    }
// Create a new instructor
public function handleCreateInstructorRequest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = $_POST['first_name'];
        $middleName = $_POST['middle_name'];
        $lastName = $_POST['last_name'];
        $suffix = $_POST['suffix'];
        $email = $_POST['email'];
        $departmentId = $_POST['department_id'];
        $courseId = $_POST['course_id'];
        $sectionId = $_POST['section_id'];

        // Validate the instructor's first and last names to allow only alphabets and spaces
        if (!preg_match('/^[a-zA-Z ]+$/', $firstName) || !preg_match('/^[a-zA-Z ]+$/', $lastName)) {
            header('Location: create_instructor.php?message=invalid_name');
            exit();
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: create_instructor.php?message=invalid_email');
            exit();
        }

        // Store inputs in session for retrieval after redirect
        $_SESSION['last_first_name'] = $firstName;
        $_SESSION['last_middle_name'] = $middleName;
        $_SESSION['last_last_name'] = $lastName;
        $_SESSION['last_suffix'] = $suffix;
        $_SESSION['last_email'] = $email;
        $_SESSION['last_department_id'] = $departmentId;
        $_SESSION['last_course_id'] = $courseId;
        $_SESSION['last_section_id'] = $sectionId;

        // Check if the instructor already exists (assuming email must be unique)
        if ($this->instructorExists($email)) {
            header('Location: create_instructor.php?message=exists');
            exit();
        }

        // If it doesn't exist, create the instructor
        if ($this->create($firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId)) {
            header('Location: create_instructor.php?message=success');
            exit();
        } else {
            header('Location: create_instructor.php?message=failure');
            exit();
        }
    }
}

// Method to check if the instructor already exists
public function instructorExists($email) {
    try {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM instructors WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();
        return $count > 0; // Returns true if the instructor exists
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Method to create a new instructor
public function create($firstName, $middleName, $lastName, $suffix, $email, $departmentId, $courseId, $sectionId) {
    try {
        $sql = "INSERT INTO instructors (first_name, middle_name, last_name, suffix, email, department_id, course_id, section_id)
                VALUES (:first_name, :middle_name, :last_name, :suffix, :email, :department_id, :course_id, :section_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':first_name' => $firstName,
            ':middle_name' => $middleName,
            ':last_name' => $lastName,
            ':suffix' => $suffix,
            ':email' => $email,
            ':department_id' => $departmentId,
            ':course_id' => $courseId,
            ':section_id' => $sectionId,
        ]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}















    // Delete an instructor by ID
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM instructors WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $stmt->rowCount() > 0; // Returns true if a row was deleted
            }
        } catch (PDOException $e) {
            error_log('Error in delete(): ' . $e->getMessage());
            return false; // Return false on failure
        }
    }

    // Fetch departments for dropdown
    public function getDepartments() {
        $query = "SELECT id, name FROM departments";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch courses for dropdown
    public function getCourses() {
        $query = "SELECT id, course_name FROM courses";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch sections for dropdown
    public function getSections() {
        $query = "SELECT id, name FROM sections";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch courses based on department
    public function getCoursesByDepartment($departmentId) {
        $query = "SELECT id, course_name FROM courses WHERE department_id = :department_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':department_id' => $departmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch sections based on course
    public function getSectionsByCourse($courseId) {
        $query = "SELECT id, name FROM sections WHERE course_id = :course_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':course_id' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllEmails() {
        $stmt = $this->pdo->prepare("SELECT email FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
