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
    // Handle the case where the student number is not set
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

        <label for="course" class="block text-sm font-medium text-gray-700 mt-4">Select Course</label>
        <select name="course" id="course" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300" onchange="fetchSections(this.value)">
            <option value="">Select Course</option>
        </select>

        <label for="section" class="block text-sm font-medium text-gray-700 mt-4">Select Section</label>
        <select name="section" id="section" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300" onchange="fetchSubjects(this.value)">
            <option value="">Select Section</option>
        </select>

        <label for="subject" class="block text-sm font-medium text-gray-700 mt-4">Select Subject</label>
        <select name="subject" id="subject" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300">
            <option value="">Select Subject</option>
        </select>

        <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">Submit</button>
    </form>
</div>

<script>
async function fetchCourses(departmentId) {
    const courseDropdown = document.getElementById('course');
    const sectionDropdown = document.getElementById('section');
    const subjectDropdown = document.getElementById('subject');

    // Reset dropdowns
    courseDropdown.innerHTML = '<option value="">Select Course</option>';
    sectionDropdown.innerHTML = '<option value="">Select Section</option>';
    subjectDropdown.innerHTML = '<option value="">Select Subject</option>';

    if (!departmentId) return; // If no department selected, stop here

    try {
        const response = await fetch(`fetch_courses.php?department_id=${departmentId}`);
        const data = await response.json();

        if (data.error) {
            console.error('Error fetching courses:', data.error);
            alert('Failed to fetch courses');
        } else {
            data.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = course.course_name;
                courseDropdown.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error fetching courses:', error);
        alert('Error fetching courses');
    }
}

async function fetchSections(courseId) {
    const sectionDropdown = document.getElementById('section');
    const subjectDropdown = document.getElementById('subject');

    // Reset dropdowns
    sectionDropdown.innerHTML = '<option value="">Select Section</option>';
    subjectDropdown.innerHTML = '<option value="">Select Subject</option>';

    if (!courseId) return;

    try {
        const response = await fetch(`fetch_sections.php?course_id=${courseId}`);
        const data = await response.json();

        if (data.error) {
            console.error('Error fetching sections:', data.error);
            alert('Failed to fetch sections');
        } else {
            data.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionDropdown.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error fetching sections:', error);
        alert('Error fetching sections');
    }
}

async function fetchSubjects(sectionId) {
    const subjectDropdown = document.getElementById('subject');
    subjectDropdown.innerHTML = '<option value="">Select Subject</option>';

    if (!sectionId) return;

    try {
        const response = await fetch(`fetch_subjects.php?section_id=${sectionId}`);
        const data = await response.json();

        if (data.error) {
            console.error('Error fetching subjects:', data.error);
            alert('Failed to fetch subjects');
        } else {
            data.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = `Subject Code: ${subject.code} - Title: ${subject.title} (${subject.units} units, Semester: ${subject.semester_id})`;
                subjectDropdown.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error fetching subjects:', error);
        alert('Error fetching subjects');
    }
}
</script>

</body>
</html>
