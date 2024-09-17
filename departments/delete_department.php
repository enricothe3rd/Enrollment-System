<?php
require 'Department.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $department = new Department();
    $department->delete($id);
    header('Location: read_departments.php');
}
?>
