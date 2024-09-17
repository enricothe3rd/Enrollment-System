<?php
require 'Subject.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $subject = new Subject();
    $subject->delete($id);
    header('Location: read_subjects.php');
    exit();
} else {
    echo 'No subject ID provided.';
}
?>
