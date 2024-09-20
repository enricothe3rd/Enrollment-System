<?php
require 'SubjectAmounts.php'; // Assuming this connects to the database

$subjectAmount = new SubjectAmount();

if (isset($_POST['section_id'])) {
    $sectionId = $_POST['section_id'];
    $subjects = $subjectAmount->getSubjectsBySection($sectionId);

    foreach ($subjects as $subject) {
        echo "<option value='{$subject['id']}' 
                      data-code='{$subject['code']}' 
                      data-units='{$subject['units']}' 
                      data-semester='{$subject['semester_id']}'>
                Code: {$subject['code']} - Title: {$subject['title']} - Units: {$subject['units']} - Semester: {$subject['semester_id']}
              </option>";
    }
}
?>
