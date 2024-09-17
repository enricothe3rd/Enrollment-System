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
            $subject_id = $_POST['subject_id'];
            $day_of_week = $_POST['day_of_week'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $room = $_POST['room'];

            if ($this->create($subject_id, $day_of_week, $start_time, $end_time, $room)) {
                header('Location: read_schedules.php'); // Redirect to a success page
                exit();
            } else {
                echo 'Failed to create schedule.';
            }
        }
    }

    // Create a new schedule
    public function create($subject_id, $day_of_week, $start_time, $end_time, $room) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO schedules (subject_id, day_of_week, start_time, end_time, room) VALUES (:subject_id, :day_of_week, :start_time, :end_time, :room)');
            return $stmt->execute([
                ':subject_id' => $subject_id,
                ':day_of_week' => $day_of_week,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':room' => $room
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Handle update schedule request
    public function handleUpdateScheduleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $subject_id = $_POST['subject_id'];
            $day_of_week = $_POST['day_of_week'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $room = $_POST['room'];

            if ($this->update($id, $subject_id, $day_of_week, $start_time, $end_time, $room)) {
                header('Location: read_schedules.php'); // Redirect to a success page
                exit();
            } else {
                echo 'Failed to update schedule.';
            }
        }
    }

    // Update a schedule
    public function update($id, $subject_id, $day_of_week, $start_time, $end_time, $room) {
        try {
            $stmt = $this->pdo->prepare('UPDATE schedules SET subject_id = :subject_id, day_of_week = :day_of_week, start_time = :start_time, end_time = :end_time, room = :room WHERE id = :id');
            return $stmt->execute([
                ':subject_id' => $subject_id,
                ':day_of_week' => $day_of_week,
                ':start_time' => $start_time,
                ':end_time' => $end_time,
                ':room' => $room,
                ':id' => $id
            ]);
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
                'SELECT s.*, subj.title AS subject_name
                 FROM schedules s
                 JOIN subjects subj ON s.subject_id = subj.id'
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log the error and return an empty array
            error_log("Database query error: " . $e->getMessage());
            return [];
        }
    }
    

    // In Schedule.php

    // Add this method to your Schedule class
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
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
