<?php
require 'SubjectAmounts.php'; // Include the SubjectAmount class

$subjectAmount = new SubjectAmount(); // Create a new instance
$departments = $subjectAmount->getAllDepartments(); // Fetch all departments
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject Amount</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery for AJAX -->
</head>
<body>

    <form id="subjectAmountForm" method="POST">
        <!-- Department Dropdown -->
        <select id="department" name="department" required>
            <option value="">Select Department</option>
            <?php
            if ($departments) {
                foreach ($departments as $department) {
                    echo "<option value='{$department['id']}'>{$department['name']}</option>";
                }
            } else {
                echo "<option value=''>No departments found</option>";
            }
            ?>
        </select>

        <!-- Course Dropdown -->
        <select id="course" name="course" required>
            <option value="">Select Course</option>
        </select>

        <!-- Section Dropdown -->
        <select id="section" name="section" required>
            <option value="">Select Section</option>
        </select>

        <!-- Subject Dropdown -->
        <select id="subject" name="subject" required>
            <option value="">Select Subject</option>
        </select>

        <!-- Amount Input -->
        <input type="number" name="amount" placeholder="Enter Amount" required>

        <!-- Submit Button -->
        <button type="submit">Add Amount</button>
    </form>

    <script>
        // Handle department change
        $('#department').on('change', function() {
            var department_id = $(this).val();
            $('#course').html('<option value="">Loading...</option>');
            $('#section').html('<option value="">Select Section</option>');
            $('#subject').html('<option value="">Select Subject</option>');

            if (department_id) {
                $.ajax({
                    type: 'POST',
                    url: 'SubjectAmounts.php',
                    data: { action: 'fetch_courses', department_id: department_id },
                    success: function(response) {
                        $('#course').html(response);
                    }
                });
            } else {
                $('#course').html('<option value="">Select Course</option>');
            }
        });

        // Handle course change
        $('#course').on('change', function() {
            var course_id = $(this).val();
            $('#section').html('<option value="">Loading...</option>');
            $('#subject').html('<option value="">Select Subject</option>');

            if (course_id) {
                $.ajax({
                    type: 'POST',
                    url: 'SubjectAmounts.php',
                    data: { action: 'fetch_sections', course_id: course_id },
                    success: function(response) {
                        $('#section').html(response);
                    }
                });
            } else {
                $('#section').html('<option value="">Select Section</option>');
            }
        });

        // Handle section change
        $('#section').on('change', function() {
            var section_id = $(this).val();
            $('#subject').html('<option value="">Loading...</option>');

            if (section_id) {
                $.ajax({
                    type: 'POST',
                    url: 'SubjectAmounts.php',
                    data: { action: 'fetch_subjects', section_id: section_id },
                    success: function(response) {
                        $('#subject').html(response);
                    }
                });
            } else {
                $('#subject').html('<option value="">Select Subject</option>');
            }
        });

        // Handle form submission
        $('#subjectAmountForm').on('submit', function(e) {
            e.preventDefault(); // Prevent traditional form submission
            
            $.ajax({
                type: 'POST',
                url: 'SubjectAmounts.php',
                data: $(this).serialize() + '&action=add_amount',
                success: function(response) {
                    alert(response); // Show a message after amount is added
                    $('#subjectAmountForm')[0].reset(); // Reset the form
                }
            });
        });
    </script>

</body>
</html>
