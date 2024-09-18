<?php
include '../db/db_connection3.php';

// Handle AJAX requests
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $pdo = Database::connect(); // Ensure the PDO instance is retrieved

    switch ($action) {
        case 'get_sections':
            $course_id = intval($_GET['course_id']);
            $query = "SELECT * FROM sections WHERE course_id = :course_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->execute();
            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['sections' => $sections]);
            break;

        case 'get_subjects':
            $section_id = intval($_GET['section_id']);
            $query = "SELECT * FROM subjects WHERE section_id = :section_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);
            $stmt->execute();
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['subjects' => $subjects]);
            break;

        case 'get_schedules':
            $subject_id = intval($_GET['subject_id']);
            $query = "SELECT * FROM schedules WHERE subject_id = :subject_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();
            $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['schedules' => $schedules]);
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
    exit;
}

// Fetch courses for the dropdown
$pdo = Database::connect(); // Ensure the PDO instance is retrieved
$courses_query = "SELECT * FROM courses";
$courses_stmt = $pdo->query($courses_query);
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseSelect = document.getElementById('course');
            const container = document.getElementById('data-container');

            courseSelect.addEventListener('change', function() {
                const courseId = this.value;

                if (courseId) {
                    // Fetch sections
                    fetch(`create_enrollment.php?action=get_sections&course_id=${courseId}`)
                        .then(response => response.json())
                        .then(data => {
                            container.innerHTML = '';

                            data.sections.forEach(section => {
                                // Create a container for each section
                                const sectionContainer = document.createElement('div');
                                sectionContainer.className = 'bg-white shadow-md rounded-lg p-4 mb-4';

                                // Add section details
                                sectionContainer.innerHTML = `
                                    <div class="font-semibold text-lg mb-2">Section: ${section.name}</div>
                                    <div id="subjects-container-${section.id}" class="space-y-4"></div>
                                `;

                                container.appendChild(sectionContainer);

                                // Fetch subjects for each section
                                fetch(`create_enrollment.php?action=get_subjects&section_id=${section.id}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const subjectsContainer = document.getElementById(`subjects-container-${section.id}`);
                                        data.subjects.forEach(subject => {
                                            // Create a container for each subject and its schedules
                                            const subjectContainer = document.createElement('div');
                                            subjectContainer.className = 'bg-gray-100 p-3 rounded-lg shadow-md mb-2';

                                            // Add subject details and schedules
                                            subjectContainer.innerHTML = `
                                                <div class="font-semibold text-lg">Subject: ${subject.code} - ${subject.title}</div>
                                                <div class="mt-2">
                                                    <div class="font-semibold">Schedules:</div>
                                                    <div id="schedules-${subject.id}" class="flex flex-wrap gap-2"></div>
                                                </div>
                                            `;

                                            subjectsContainer.appendChild(subjectContainer);

                                            // Fetch schedules for each subject
                                            fetch(`create_enrollment.php?action=get_schedules&subject_id=${subject.id}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    const schedulesContainer = document.getElementById(`schedules-${subject.id}`);
                                                    data.schedules.forEach(schedule => {
                                                        const scheduleElement = document.createElement('div');
                                                        scheduleElement.className = 'bg-white border border-gray-200 rounded-lg p-2';
                                                        scheduleElement.innerHTML = `
                                                            Room: ${schedule.room}, Day: ${schedule.day_of_week}, Time: ${schedule.start_time} - ${schedule.end_time}
                                                        `;
                                                        schedulesContainer.appendChild(scheduleElement);
                                                    });
                                                })
                                                .catch(error => console.error('Error fetching schedules:', error));
                                        });
                                    })
                                    .catch(error => console.error('Error fetching subjects:', error));
                            });
                        })
                        .catch(error => console.error('Error fetching sections:', error));
                } else {
                    container.innerHTML = '';
                }
            });
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl mx-auto">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">Enrollment Form</h1>
            <form id="enrollmentForm" action="process_enrollment.php" method="POST">
                <div class="mb-4">
                    <label for="course" class="block text-gray-700 text-sm font-medium mb-2">Course:</label>
                    <select id="course" name="course" class="form-select block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="data-container" class="space-y-4"></div>

                <!-- Form fields for student details -->
                <div class="mb-4">
                    <label for="student_number" class="block text-gray-700 text-sm font-medium mb-2">Student Number:</label>
                    <input type="text" id="student_number" name="student_number" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5" required>
                </div>

                <div class="mb-4">
                    <label for="firstname" class="block text-gray-700 text-sm font-medium mb-2">First Name:</label>
                    <input type="text" id="firstname" name="firstname" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <div class="mb-4">
                    <label for="middlename" class="block text-gray-700 text-sm font-medium mb-2">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <div class="mb-4">
                    <label for="lastname" class="block text-gray-700 text-sm font-medium mb-2">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <div class="mb-4">
                    <label for="suffix" class="block text-gray-700 text-sm font-medium mb-2">Suffix:</label>
                    <input type="text" id="suffix" name="suffix" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <div class="mb-4">
                    <label for="student_type" class="block text-gray-700 text-sm font-medium mb-2">Student Type:</label>
                    <select id="student_type" name="student_type" class="form-select block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                        <option value="regular">Regular</option>
                        <option value="new student">New Student</option>
                        <option value="irregular">Irregular</option>
                        <option value="summer">Summer</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="sex" class="block text-gray-700 text-sm font-medium mb-2">Sex:</label>
                    <select id="sex" name="sex" class="form-select block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="dob" class="block text-gray-700 text-sm font-medium mb-2">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email:</label>
                    <input type="email" id="email" name="email" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <div class="mb-4">
                    <label for="contact_no" class="block text-gray-700 text-sm font-medium mb-2">Contact Number:</label>
                    <input type="text" id="contact_no" name="contact_no" class="form-input block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5">
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
