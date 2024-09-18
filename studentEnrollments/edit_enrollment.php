<?php
require 'Enrollment.php';
$enrollment = new Enrollment();

$id = $_GET['id'];
$enrollmentData = $enrollment->getEnrollmentById($id);

// Fetch courses for the dropdown
$courses = $enrollment->getCourses();

// Fetch sections related to the selected course
$sections = $enrollment->getSectionsByCourseId($enrollmentData['course_id']);

// Fetch subjects related to the selected section
$subjects = $enrollment->getSubjectsBySectionId($enrollmentData['section_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment->handleEditEnrollmentRequest($id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="w-full max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-6">Edit Enrollment</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                    <input type="text" name="firstname" value="<?= $enrollmentData['firstname']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Middle Name</label>
                    <input type="text" name="middlename" value="<?= $enrollmentData['middlename']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                    <input type="text" name="lastname" value="<?= $enrollmentData['lastname']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Suffix</label>
                    <input type="text" name="suffix" value="<?= $enrollmentData['suffix']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Student Type</label>
                    <select name="student_type" class="w-full px-3 py-2 border rounded-md">
                        <option value="regular" <?= $enrollmentData['student_type'] === 'regular' ? 'selected' : ''; ?>>Regular</option>
                        <option value="new student" <?= $enrollmentData['student_type'] === 'new student' ? 'selected' : ''; ?>>New Student</option>
                        <option value="irregular" <?= $enrollmentData['student_type'] === 'irregular' ? 'selected' : ''; ?>>Irregular</option>
                        <option value="summer" <?= $enrollmentData['student_type'] === 'summer' ? 'selected' : ''; ?>>Summer</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Sex</label>
                    <select name="sex" class="w-full px-3 py-2 border rounded-md">
                        <option value="Male" <?= $enrollmentData['sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= $enrollmentData['sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date of Birth</label>
                    <input type="date" name="dob" value="<?= $enrollmentData['dob']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="<?= $enrollmentData['email']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Contact Number</label>
                    <input type="text" name="contact_no" value="<?= $enrollmentData['contact_no']; ?>" class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Course</label>
                    <select name="course_id" id="course" class="w-full px-3 py-2 border rounded-md">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id']; ?>" <?= $course['id'] === $enrollmentData['course_id'] ? 'selected' : ''; ?>>
                                <?= $course['course_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                    <select name="section_id" id="section" class="w-full px-3 py-2 border rounded-md">
                        <?php foreach ($sections as $section): ?>
                            <option value="<?= $section['id']; ?>" <?= $section['id'] === $enrollmentData['section_id'] ? 'selected' : ''; ?>>
                                <?= $section['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Subjects</label>
                    <select name="subject_ids[]" id="subjects" multiple class="w-full px-3 py-2 border rounded-md">
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= $subject['id']; ?>" <?= in_array($subject['id'], $enrollmentData['subject_ids']) ? 'selected' : ''; ?>>
                                <?= $subject['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Enrollment</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('course').addEventListener('change', function () {
            const courseId = this.value;
            fetchSections(courseId);
        });

        function fetchSections(courseId) {
            fetch('fetch_sections.php?course_id=' + courseId)
                .then(response => response.json())
                .then(data => {
                    const sectionSelect = document.getElementById('section');
                    sectionSelect.innerHTML = '';
                    data.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.text = section.name;
                        sectionSelect.appendChild(option);
                    });
                });
        }

        document.getElementById('section').addEventListener('change', function () {
            const sectionId = this.value;
            fetchSubjects(sectionId);
        });

        function fetchSubjects(sectionId) {
            fetch('fetch_subjects.php?section_id=' + sectionId)
                .then(response => response.json())
                .then(data => {
                    const subjectsSelect = document.getElementById('subjects');
                    subjectsSelect.innerHTML = '';
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.text = subject.title;
                        subjectsSelect.appendChild(option);
                    });
                });
        }
    </script>
</body>
</html>
