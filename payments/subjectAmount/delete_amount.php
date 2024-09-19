<?php
require 'Subject.php';

$subject = new Subject();
$id = $_GET['id'] ?? null;
if ($id) {
    if ($subject->delete($id)) {
        header('Location: SubjectAmounts.php');
        exit();
    } else {
        echo 'Failed to delete subject.';
    }
} else {
    header('Location: SubjectAmounts.php');
    exit();
}
?>
