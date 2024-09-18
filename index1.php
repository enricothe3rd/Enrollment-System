<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar with Multiple Content Sections</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .content-section {
            display: none;
            height: 100%;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .additional-buttons {
            display: none; /* Hide additional buttons by default */
        }
        .arrow-icon {
            transition: transform 0.3s ease;
        }
        .arrow-icon.expanded {
            transform: rotate(90deg); /* Rotate icon when expanded */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-200 text-red-500 flex-shrink-0">
            <!-- Logo -->
            <div class="p-4 text-center">
                <img src="https://via.placeholder.com/150x50.png?text=Logo" alt="Logo" class="mx-auto">
            </div>
            <!-- Sidebar Header -->
            <div class="p-4 text-center text-xl font-bold text-white">My Sidebar</div>
            <!-- Navigation -->
            <nav class="mt-4">
                <ul>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('home')"><i class="fas fa-home mr-3"></i> Home</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('enrollment')"><i class="fas fa-user-plus mr-3"></i> New Enrollments</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('student')"><i class="fas fa-user-plus mr-3"></i> Student</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('department')"><i class="fas fa-building mr-3"></i> Department</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('courses')"><i class="fas fa-graduation-cap mr-3"></i> Courses <i  ></i></a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('sections')"><i class="fas fa-graduation-cap mr-3"></i> Sections <i  ></i></a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('subjects')"><i class="fas fa-book mr-3"></i> Subjects</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('schedule')"><i class="fas fa-calendar-alt mr-3"></i> Schedule</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('students')"><i class="fas fa-users mr-3"></i> Students</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('instructor')"><i class="fas fa-chalkboard-teacher mr-3"></i> Instructor</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('set-semester')"><i class="fas fa-calendar-check mr-3"></i> Set Semester</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('classroom')"><i class="fas fa-school mr-3"></i> Classroom</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-400" onclick="showContent('report')"><i class="fas fa-file-alt mr-3"></i> Report</a></li>
                </ul>
            </nav>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-hidden">
            <div id="home" class="content-section">
                <iframe src="home.php" title="Home"></iframe>
            </div>
            <div id="enrollment" class="content-section">
                <iframe src="studentEnrollments/read_enrollments.php" title="New Enrollments"></iframe>
            </div>
            <div id="department" class="content-section">
                <iframe src="departments/read_departments.php" title="Department"></iframe>
            </div>
            <div id="subjects" class="content-section">
                <iframe src="subjects/read_subjects.php" title="Subjects"></iframe>
            </div>
            <div id="courses" class="content-section">
                <iframe src="courses/read_courses.php" title="Courses"></iframe>
            </div>
            <div id="sections" class="content-section">
                <iframe src="sections/read_sections.php" title="Courses"></iframe>
            </div>
            <div id="schedule" class="content-section">
                <iframe src="schedule/read_schedules.php" title="Schedule"></iframe>
            </div>
            <div id="students" class="content-section">
                <iframe src="students.php" title="Students"></iframe>
            </div>
            <div id="instructor" class="content-section">
                <iframe src="instructor/read_instructor.php" title="Instructor"></iframe>
            </div>
            <div id="set-semester" class="content-section">
                <iframe src="semesters/read_semesters.php" title="Set Semester"></iframe>
            </div>
            <div id="classroom" class="content-section">
                <iframe src="classrooms/read_classrooms.php" title="Classroom"></iframe>
            </div>
            <div id="student" class="content-section">
                <iframe src="students/read_students.php" title="student"></iframe>
            </div>
            <div id="report" class="content-section">
                <iframe src="report.php" title="Report"></iframe>
            </div>
        </main>
    </div>

    <script>
    function showContent(id) {
        // Hide all content sections
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show the selected content section
        document.getElementById(id).style.display = 'block';

        // Store the currently selected section in localStorage
        localStorage.setItem('currentSection', id);

        // Hide additional buttons when switching sections

    }


    // On page load, show the last opened section from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const lastSection = localStorage.getItem('currentSection') || 'home';
        showContent(lastSection);
    });
</script>

</body>
</html>
