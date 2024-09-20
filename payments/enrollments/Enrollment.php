<?php
require '../../db/db_connection3.php'; // Ensure you have a database connection file

class Enrollment {
    private $db;

    public function __construct() {
        $this->db = Database::connect(); // Assuming Database class handles the connection
    }

    public function createEnrollment($data) {
        $sql = "INSERT INTO enrollments (lastname, firstname, middlename, dob, address, contact_no, sex, suffix, semester, school_year, status) 
                VALUES (:lastname, :firstname, :middlename, :dob, :address, :contact_no, :sex, :suffix, :semester, :school_year, :status)";
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':lastname', $data['lastname']);
        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':middlename', $data['middlename']);
        $stmt->bindParam(':dob', $data['dob']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':contact_no', $data['contact_no']);
        $stmt->bindParam(':sex', $data['sex']);
        $stmt->bindParam(':suffix', $data['suffix']);
        $stmt->bindParam(':semester', $data['semester']);
        $stmt->bindParam(':school_year', $data['school_year']);
        $stmt->bindParam(':status', $data['status']);

        return $stmt->execute();
    }

    public function getEnrollments() {
        $sql = "SELECT * FROM enrollments";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSchoolYears() {
        $stmt = $this->db->prepare("SELECT year FROM school_years ORDER BY year DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStatusOptions() {
        $stmt = $this->db->prepare("SELECT status_name FROM status_options");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSexOptions() {
        $stmt = $this->db->prepare("SELECT sex_name FROM sex_options");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
}
?>
