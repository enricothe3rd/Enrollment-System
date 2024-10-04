<?php
require 'Department.php';

if (isset($_GET['id'])) {
    $departmentId = $_GET['id'];

    $department = new Department();
    if ($department->delete($departmentId)) {
        header('Location: read_departments.php?deletion=success');
    } else {
        header('Location: read_departments.php?deletion=failed');
    }
    exit;
} else {
    header('Location: read_departments.php?deletion=failed');
    exit;
}
?>
