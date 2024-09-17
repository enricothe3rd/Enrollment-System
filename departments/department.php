<?php
require '../db/db_connection3.php'; // Ensure this path is correct

class Department {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect(); // Use the static connect method
    }

    public function create($name) {
        $sql = "INSERT INTO departments (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
    }

    public function read() {
        $sql = "SELECT * FROM departments";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name) {
        $sql = "UPDATE departments SET name = :name WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'id' => $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM departments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function find($id) {
        $sql = "SELECT * FROM departments WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
