<?php
require '../db/db_connection3.php'; // Ensure this path is correct

class Department {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect(); // Use the static connect method
    }

    public function create($name, $established, $dean, $email, $phone, $location, $student_count) {
        $sql = "INSERT INTO departments (name, established, dean, email, phone, location, student_count) 
                VALUES (:name, :established, :dean, :email, :phone, :location, :student_count)";
        $stmt = $this->pdo->prepare($sql);
        
        try {
            $stmt->execute([
                'name' => $name,
                'established' => $established,
                'dean' => $dean,
                'email' => $email,
                'phone' => $phone,
                'location' => $location,
                'student_count' => $student_count
            ]);
            return true; // Indicate success
        } catch (PDOException $e) {
            // Log the error message
            error_log($e->getMessage());
            return false; // Indicate failure
        }
    }
    
    public function read() {
        $sql = "SELECT *, (SELECT COUNT(*) FROM instructors WHERE department_id = departments.id) as faculty_count FROM departments";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update($id, $name, $established, $dean, $email, $phone, $location, $faculty_count) {
        $sql = "UPDATE departments SET name = :name, established = :established, dean = :dean, 
                email = :email, phone = :phone, location = :location, faculty_count = :faculty_count 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'established' => $established,
            'dean' => $dean,
            'email' => $email,
            'phone' => $phone,
            'location' => $location,
            'faculty_count' => $faculty_count, // Updated to faculty_count
            'id' => $id
        ]);
    }
    
    public function delete($id) {
        // Check if the department is associated with any courses
        $checkSql = "SELECT COUNT(*) FROM courses WHERE department_id = :id";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute(['id' => $id]);
        $count = $checkStmt->fetchColumn();
    
        // If there are associated courses, do not allow deletion
        if ($count > 0) {
            return "Cannot delete department: it's associated with existing courses.";
        }
    
        // Proceed to delete the department
        $sql = "DELETE FROM departments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute(['id' => $id])) {
            return "Department deleted successfully.";
        }
    
        return "Error deleting department.";
    }
    
    
    

    public function find($id) {
        $sql = "SELECT * FROM departments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFacultyCount($departmentId) {
        $sql = "SELECT COUNT(*) as faculty_count FROM instructors WHERE department_id = :department_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['faculty_count'];
    }

    public function getFacultyCountByDepartment($departmentId) {
        $query = "SELECT COUNT(*) FROM instructors WHERE department_id = :department_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['department_id' => $departmentId]);
        return $stmt->fetchColumn(); // Returns the count
    }

    public function departmentExists($name) {
        $pdo = Database::connect(); // Assume you have a method to connect to your database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE name = :name");
        $stmt->execute([':name' => $name]);
        return $stmt->fetchColumn() > 0; // Returns true if department exists, false otherwise
    }
}
?>
