<?php
require '../db/db_connection3.php';

class Classroom {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Handle Create Request
    public function handleCreateClassroomRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_number = $_POST['room_number'];
            $capacity = $_POST['capacity'];
            $building = $_POST['building'];

            if ($this->createClassroom($room_number, $capacity, $building)) {
                header('Location: read_classrooms.php'); // Redirect to the list of classrooms
                exit();
            } else {
                echo 'Failed to create classroom.';
            }
        }
    }

    public function createClassroom($room_number, $capacity, $building) {
        try {
            $sql = "INSERT INTO classrooms (room_number, capacity, building) VALUES (:room_number, :capacity, :building)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':room_number' => $room_number,
                ':capacity' => $capacity,
                ':building' => $building
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Handle Update Request
    public function handleUpdateClassroomRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_number = $_POST['room_number'];
            $capacity = $_POST['capacity'];
            $building = $_POST['building'];

            if ($this->updateClassroom($id, $room_number, $capacity, $building)) {
                header('Location: read_classrooms.php'); // Redirect to the list of classrooms
                exit();
            } else {
                echo 'Failed to update classroom.';
            }
        }
    }

    public function updateClassroom($id, $room_number, $capacity, $building) {
        try {
            $sql = "UPDATE classrooms SET room_number = :room_number, capacity = :capacity, building = :building WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':room_number' => $room_number,
                ':capacity' => $capacity,
                ':building' => $building,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Retrieve All Classrooms
    public function getClassrooms() {
        $sql = "SELECT * FROM classrooms";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a Classroom by ID
    public function getClassroomById($id) {
        $sql = "SELECT * FROM classrooms WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Delete a Classroom
    public function deleteClassroom($id) {
        try {
            $sql = "DELETE FROM classrooms WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
