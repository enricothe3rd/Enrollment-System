<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Course Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Select Department, Course, Section, and Subject</h2>

    <!-- Department Dropdown -->
    <label for="department" class="block font-semibold mb-2">Department:</label>
    <select id="department" name="department" class="block w-full p-3 border border-gray-300 rounded mb-4">
        <option value="">Select a department</option>
    </select>

    <!-- Course Dropdown -->
    <label for="course" class="block font-semibold mb-2">Course:</label>
    <select id="course" name="course" disabled class="block w-full p-3 border border-gray-300 rounded mb-4">
        <option value="">Select a course</option>
    </select>

    <!-- Section Dropdown -->
    <label for="section" class="block font-semibold mb-2">Section:</label>
    <select id="section" name="section" disabled class="block w-full p-3 border border-gray-300 rounded mb-4">
        <option value="">Select a section</option>
    </select>

    <!-- Subject Dropdown -->
    <label for="subject" class="block font-semibold mb-2">Subject:</label>
    <select id="subject" name="subject" disabled class="block w-full p-3 border border-gray-300 rounded mb-4">
        <option value="">Select a subject</option>
    </select>

    <!-- Amount Input -->
    <label for="amount" class="block font-semibold mb-2">Amount:</label>
    <input type="number" id="amount" name="amount" class="block w-full p-3 border border-gray-300 rounded mb-4" placeholder="Enter amount" disabled>

    <!-- Submit Button -->
    <button id="submit" class="bg-blue-500 text-white p-2 rounded" disabled>Submit Amount</button>

    <!-- Update Button -->
    <button id="update" class="bg-yellow-500 text-white p-2 rounded mt-2" disabled>Update Amount</button>

    <!-- Delete Button -->
    <button id="delete" class="bg-red-500 text-white p-2 rounded mt-2" disabled>Delete Subject</button>

    <!-- Display Subject Amounts -->
    <h3 class="text-xl font-semibold mt-6">Subject Amounts</h3>
    <div id="subject-amounts" class="mt-4"></div>
</div>

<!-- jQuery for AJAX requests -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JavaScript for Dynamic Loading -->
<script>
$(document).ready(function() {
    loadDepartments();

    $('#department').on('change', function() {
        const departmentId = $(this).val();
        departmentId ? loadCourses(departmentId) : resetDropdowns(['course', 'section', 'subject']);
    });

    $('#course').on('change', function() {
        const courseId = $(this).val();
        courseId ? loadSections(courseId) : resetDropdowns(['section', 'subject']);
    });

    $('#section').on('change', function() {
        const sectionId = $(this).val();
        sectionId ? loadSubjects(sectionId) : $('#subject').prop('disabled', true).html('<option value="">Select a subject</option>');
    });

    $('#subject').on('change', function() {
        $('#amount').prop('disabled', false); // Enable amount input
        $('#submit').prop('disabled', false); // Enable submit button
        $('#update').prop('disabled', false); // Enable update button
        $('#delete').prop('disabled', false); // Enable delete button
        loadSubjectAmounts(); // Load subject amounts when a subject is selected
    });

   // Submit amount
   $('#submit').on('click', function() {
        const subjectId = $('#subject').val();
        const amount = $('#amount').val();

        if (subjectId && amount) {
            $.ajax({
                url: 'submit_amount.php',
                method: 'POST',
                data: { subject_id: subjectId, amount: amount },
                success: function(response) {
                    alert('Amount submitted successfully: ' + response);
                    $('#amount').val(''); // Clear the input
                    loadSubjectAmounts(); // Reload subject amounts
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error submitting amount: " + textStatus, errorThrown);
                }
            });
        } else {
            alert('Please select a subject and enter an amount.');
        }
    });


// Update Amount
$('#update').on('click', function() {
    const subjectId = $('#subject').val();
    const amount = $('#amount').val();

    if (subjectId && amount) {
        $.ajax({
            url: 'update_subject_amount.php',
            method: 'POST',
            data: { subject_id: subjectId, amount: amount },
            success: function(response) {
                alert(response);
                loadSubjectAmounts(); // Reload subject amounts
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error updating amount: " + textStatus, errorThrown);
            }
        });
    } else {
        alert('Please select a subject and enter an amount.');
    }
});

// Delete Subject
$('#delete').on('click', function() {
    const subjectId = $('#subject').val();

    if (subjectId) {
        $.ajax({
            url: 'delete_subject_amount.php',
            method: 'POST',
            data: { subject_id: subjectId },
            success: function(response) {
                alert(response);
                loadSubjectAmounts(); // Reload subject amounts
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error deleting subject: " + textStatus, errorThrown);
            }
        });
    } else {
        alert('Please select a subject to delete.');
    }
});

    // Load Subject Amounts
// Load Subject Amounts
function loadSubjectAmounts() {
    const subjectId = $('#subject').val();

    if (subjectId) {
        $.ajax({
            url: 'read_subject_amount.php',
            method: 'GET',
            data: { subject_id: subjectId },
            success: function(data) {
                const subject = JSON.parse(data);
                const html = `
                    <div>
                        <strong>Title:</strong> ${subject.title}<br>
                        <strong>Code:</strong> ${subject.code}<br>
                        <strong>Units:</strong> ${subject.units}<br>
                        <strong>Semester:</strong> ${subject.semester}<br>
                        <strong>Amount:</strong> ${subject.price}
                    </div>
                `;
                $('#subject-amounts').html(html);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error loading subject amounts: " + textStatus, errorThrown);
            }
        });
    }
}



    // Functions to load data
    function loadDepartments() {
        $.ajax({
            url: 'fetch_departments.php',
            method: 'GET',
            success: function(data) {
                $('#department').append(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching departments: " + textStatus, errorThrown);
            }
        });
    }

    function loadCourses(departmentId) {
        $.ajax({
            url: 'fetch_courses.php',
            method: 'POST',
            data: { department_id: departmentId },
            success: function(data) {
                $('#course').html('<option value="">Select a course</option>').append(data);
                $('#course').prop('disabled', false);
                resetDropdowns(['section', 'subject']);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching courses: " + textStatus, errorThrown);
            }
        });
    }

    function loadSections(courseId) {
        $.ajax({
            url: 'fetch_sections.php',
            method: 'POST',
            data: { course_id: courseId },
            success: function(data) {
                $('#section').html('<option value="">Select a section</option>').append(data);
                $('#section').prop('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching sections: " + textStatus, errorThrown);
            }
        });
    }

    function loadSubjects(sectionId) {
        $.ajax({
            url: 'fetch_subjects.php',
            method: 'POST',
            data: { section_id: sectionId },
            success: function(data) {
                $('#subject').html('<option value="">Select a subject</option>').append(data);
                $('#subject').prop('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching subjects: " + textStatus, errorThrown);
            }
        });
    }

    function resetDropdowns(dropdowns) {
        dropdowns.forEach(function(dropdown) {
            $('#' + dropdown).prop('disabled', true).html('<option value="">Select a ' + dropdown + '</option>');
        });
        $('#amount').val(''); // Clear amount input
        $('#submit, #update, #delete').prop('disabled', true); // Disable buttons
    }
});
</script>

</body>
</html>
