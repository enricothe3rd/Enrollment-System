<?php
require '../db/db_connection3.php';

class Schedule {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Handle create schedule request
    public function handleCreateScheduleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if all required fields are present
            $requiredFields = ['subject_id', 'section_id', 'day_of_week', 'start_time', 'end_time', 'room'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (empty($missingFields)) {
                $subject_id = $_POST['subject_id'];
                $section_id = $_POST['section_id'];
                $day_of_week = $_POST['day_of_week'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $room = $_POST['room'];

                // Validate fields
                if ($this->create($subject_id, $section_id, $day_of_week, $start_time, $end_time, $room)) {
                    header('Location: read_schedules.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to create schedule.';
                }
            } else {
                // echo 'Please fill in all required fields: ' . implode(', ', $missingFields);
            }
        }
    }

    // Create a new schedule
    public function create($subject_id, $section_id, $day_of_week, $start_time, $end_time, $room) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO schedules (subject_id, section_id, day_of_week, start_time, end_time, room) VALUES (:subject_id, :section_id, :day_of_week, :start_time, :end_time, :room)');
            $stmt->bindParam(':subject_id', $subject_id);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->bindParam(':day_of_week', $day_of_week);
            $stmt->bindParam(':start_time', $start_time);
            $stmt->bindParam(':end_time', $end_time);
            $stmt->bindParam(':room', $room);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Handle update schedule request
    public function handleUpdateScheduleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if all required fields are present
            $requiredFields = ['id', 'subject_id', 'section_id', 'day_of_week', 'start_time', 'end_time', 'room'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (empty($missingFields)) {
                $id = $_POST['id'];
                $subject_id = $_POST['subject_id'];
                $section_id = $_POST['section_id'];
                $day_of_week = $_POST['day_of_week'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $room = $_POST['room'];

                // Validate fields
                if ($this->update($id, $subject_id, $section_id, $day_of_week, $start_time, $end_time, $room)) {
                    header('Location: read_schedules.php'); // Redirect to a success page
                    exit();
                } else {
                    echo 'Failed to update schedule.';
                }
            } else {
                echo 'Please fill in all required fields: ' . implode(', ', $missingFields);
            }
        }
    }

    // Update a schedule
    public function update($id, $subject_id, $section_id, $day_of_week, $start_time, $end_time, $room) {
        try {
            $stmt = $this->pdo->prepare('UPDATE schedules SET subject_id = :subject_id, section_id = :section_id, day_of_week = :day_of_week, start_time = :start_time, end_time = :end_time, room = :room WHERE id = :id');
            $stmt->bindParam(':subject_id', $subject_id);
            $stmt->bindParam(':section_id', $section_id);
            $stmt->bindParam(':day_of_week', $day_of_week);
            $stmt->bindParam(':start_time', $start_time);
            $stmt->bindParam(':end_time', $end_time);
            $stmt->bindParam(':room', $room);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Get a schedule by ID
    public function getScheduleById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM schedules WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Get all schedules
    public function getAllSchedules() {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT s.id, subj.title AS subject_name, sec.name AS section_name, s.day_of_week, s.start_time, s.end_time, s.room
                 FROM schedules s
                 JOIN subjects subj ON s.subject_id = subj.id
                 JOIN sections sec ON s.section_id = sec.id'
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error and return an empty array
            error_log("Database query error: " . $e->getMessage());
            return [];
        }
    }

    // Get all subjects
    public function getAllSubjects() {
        try {
            $stmt = $this->pdo->query('SELECT * FROM subjects');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    // Delete a schedule
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM schedules WHERE id = :id');
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Get all sections
    public function getAllSections() {
        $stmt = $this->pdo->query("SELECT * FROM sections");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get subjects by section
    public function getSubjectsBySection($sectionId) {
        $stmt = $this->pdo->prepare("SELECT * FROM subjects WHERE section_id = :section_id");
        $stmt->bindParam(':section_id', $sectionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
