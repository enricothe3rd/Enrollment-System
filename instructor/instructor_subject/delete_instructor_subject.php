<?php
require 'Instructor_subject.php';

$instructorSubject = new InstructorSubject();

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    if (isset($data['id'])) {
        $id = $data['id'];
        $instructorSubject->deleteAssignment($id); // Make sure this method exists
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No ID provided']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
