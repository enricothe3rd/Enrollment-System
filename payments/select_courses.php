<?php
session_start();
require '../db/db_connection3.php'; // Adjust the filename as needed

try {
    $db = Database::connect();

    // Prepare and execute the query to fetch departments
    $stmt = $db->prepare("SELECT id, name FROM departments");
    $stmt->execute();

    // Fetch all departments
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

$user_email = $_SESSION['user_email'] ?? '';
if (empty($user_email)) {
    echo "User email is not set in the session.";
    exit;
}

// Check if student_number is set in the session
if (isset($_SESSION['student_number'])) {
    $student_number = $_SESSION['student_number'];
} else {
    echo "Student number is not set in the session.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-lg mx-auto mt-10 p-8 border border-gray-300 rounded-lg shadow-md bg-white">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Select Your Course</h2>
    <form id="selectionForm" method="POST" action="submit_selection.php">
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($student_number) ?>" required class="mt-1 block w-full h-12 border border-gray-300 opacity-75 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" placeholder="example@example.com" readonly>
        </div>
        <label for="department" class="block text-sm font-medium text-gray-700">Select Department</label>
        <select name="department" id="department" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300" onchange="fetchCourses(this.value)">
            <option value="">Select Department</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?php echo htmlspecialchars($department['id']); ?>">
                    <?php echo htmlspecialchars($department['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="mb-4">
            <label for="course" class="block text-sm font-medium text-gray-700">Select Course</label>
            <select name="course" id="course" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300" onchange="fetchSections(this.value)">
                <option value="">Select Course</option>
            </select>
        </div>

        <div id="sectionsDisplay" class="mb-4"></div> <!-- Section to display corresponding sections -->
        <div id="subjectsDisplay" class="mt-4"></div> <!-- Placeholder for subjects -->
        <div id="scheduleDisplay" class="mt-4"></div> <!-- Container for displaying schedules -->

        <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">Submit</button>
    </form>
</div>

<script>
    function fetchCourses(departmentId) {
        const courseDropdown = document.getElementById('course');
        courseDropdown.innerHTML = '<option value="">Loading...</option>';

        if (departmentId !== "") {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_courses.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const courses = JSON.parse(xhr.responseText);
                        courseDropdown.innerHTML = '<option value="">Select Course</option>';

                        courses.forEach(function (course) {
                            const option = document.createElement('option');
                            option.value = course.id;
                            option.text = course.course_name;
                            courseDropdown.appendChild(option);
                        });
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        courseDropdown.innerHTML = '<option value="">Error loading courses</option>';
                    }
                }
            };
            xhr.send("department_id=" + departmentId);
        } else {
            courseDropdown.innerHTML = '<option value="">Select Course</option>';
            document.getElementById('sectionsDisplay').innerHTML = ''; // Clear sections
            document.getElementById('subjectsDisplay').innerHTML = ''; // Clear subjects
        }
    }

    function fetchSections(courseId) {
    const sectionsDisplay = document.getElementById('sectionsDisplay');
    sectionsDisplay.innerHTML = 'Loading sections...'; // Show loading text

    if (courseId !== "") {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_sections.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const sections = JSON.parse(xhr.responseText);
                    sectionsDisplay.innerHTML = ''; // Clear loading text

                    if (sections.error) {
                        sectionsDisplay.innerHTML = sections.error; // Show any error message
                    } else {
                        sections.forEach(function (section) {
                            const div = document.createElement('div');
                            div.className = "mb-4"; // Margin for spacing

                            // Create a checkbox for the section
                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'sections[]'; // Array for multiple selections
                            checkbox.value = section.id; // Set value to section ID
                            checkbox.id = `section-${section.id}`; // Unique ID for the checkbox

                            // Create a label for the checkbox
                            const label = document.createElement('label');
                            label.htmlFor = `section-${section.id}`;
                            label.textContent = `Section: ${section.name} - ID: ${section.id}`;
                            label.className = "ml-2"; // Margin for spacing

                            // Create a container for subjects
                            const subjectsContainer = document.createElement('div');
                            subjectsContainer.id = `subjects-${section.id}`; // Unique ID for subjects container
                            subjectsContainer.className = "ml-4"; // Indentation for subjects

                            div.appendChild(checkbox);
                            div.appendChild(label);
                            div.appendChild(subjectsContainer); // Append subjects container
                            sectionsDisplay.appendChild(div);

                            // Fetch and display subjects for each section
                            fetchSubjects(section.id, subjectsContainer);

                            // Add event listener to toggle subjects
                            checkbox.addEventListener('change', function() {
                                const subjectCheckboxes = subjectsContainer.querySelectorAll('input[type="checkbox"]');
                                subjectCheckboxes.forEach(subjectCheckbox => {
                                    subjectCheckbox.checked = checkbox.checked; // Check/uncheck subject checkboxes
                                });
                            });
                        });
                    }
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    sectionsDisplay.innerHTML = 'Error loading sections';
                }
            }
        };
        xhr.send("course_id=" + courseId);
    } else {
        sectionsDisplay.innerHTML = ''; // Clear sections if no course is selected
    }
}
function fetchSubjects(sectionId, subjectsContainer) {
    subjectsContainer.innerHTML = 'Loading subjects...'; // Show loading text

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_subjects.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const subjects = JSON.parse(xhr.responseText);
                subjectsContainer.innerHTML = ''; // Clear loading text

                if (subjects.error) {
                    subjectsContainer.innerHTML = subjects.error; // Show any error message
                } else {
                    subjects.forEach(function (subject) {
                        const div = document.createElement('div');
                        div.className = "mb-2"; // Margin for spacing

                        // Create a checkbox for each subject
                        const subjectCheckbox = document.createElement('input');
                        subjectCheckbox.type = 'checkbox';
                        subjectCheckbox.name = 'subjects[' + sectionId + '][]'; // Array for multiple selections
                        subjectCheckbox.value = subject.id; // Set value to subject ID
                        subjectCheckbox.id = `subject-${subject.id}`;

                        // Create a label for the subject checkbox
                        const subjectLabel = document.createElement('label');
                        subjectLabel.htmlFor = `subject-${subject.id}`;
                        subjectLabel.textContent = `Subject: ${subject.title} - Code: ${subject.code} - Units: ${subject.units}`;
                        subjectLabel.className = "ml-2"; // Margin for spacing

                        div.appendChild(subjectCheckbox);
                        div.appendChild(subjectLabel);
                        subjectsContainer.appendChild(div);

                        // Fetch and display the schedule for the subject
                        fetchSchedule(subject.id, div); // Pass subject ID to fetchSchedule
                    });
                }
            } catch (e) {
                console.error("Error parsing JSON:", e);
                subjectsContainer.innerHTML = 'Error loading subjects';
            }
        }
    };
    xhr.send("section_id=" + sectionId);
}
function fetchSchedule(subjectId, container) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_schedule.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const schedules = JSON.parse(xhr.responseText);
                const scheduleContainer = document.createElement('div');
                scheduleContainer.className = "ml-4 mt-2"; // Indentation for schedules

                if (schedules.error) {
                    scheduleContainer.innerHTML = schedules.error; // Show any error message
                } else {
                    schedules.forEach(schedule => {
                        const scheduleInfo = document.createElement('p');
                        scheduleInfo.textContent = `Day: ${schedule.day_of_week}, Time: ${schedule.start_time} - ${schedule.end_time}, Room: ${schedule.room}`;

                        // Create a checkbox for each schedule
                        const scheduleCheckbox = document.createElement('input');
                        scheduleCheckbox.type = 'checkbox';
                        scheduleCheckbox.name = `schedules[${subjectId}][]`; // Use subjectId as key
                        scheduleCheckbox.value = schedule.id; // Assume schedule has an 'id' field
                        scheduleCheckbox.id = `schedule-${schedule.id}`;

                        const scheduleLabel = document.createElement('label');
                        scheduleLabel.htmlFor = `schedule-${schedule.id}`;
                        scheduleLabel.textContent = ` ${scheduleInfo.textContent}`;
                        scheduleLabel.className = "ml-2"; // Margin for spacing

                        scheduleContainer.appendChild(scheduleCheckbox);
                        scheduleContainer.appendChild(scheduleLabel);
                    });
                }

                container.appendChild(scheduleContainer); // Append the schedule info under the subject
            } catch (e) {
                console.error("Error parsing JSON:", e);
                const errorMessage = document.createElement('p');
                errorMessage.textContent = 'Error loading schedule';
                container.appendChild(errorMessage);
            }
        }
    };
    xhr.send("subject_id=" + subjectId); // Ensure subject ID is sent
}

</script>

</body>
</html>
