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
            height: calc(100% - 64px); /* Adjust height considering sidebar header */
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
        /* Sidebar hover effect */
        nav a:hover {
            background-color: #f87171;
            color: #fff;
        }
        /* Make sidebar smoother */
        nav a {
            transition: all 0.3s ease;
        }
        .custom-logo-size {
    height: 9rem;
    width: 8.6rem;
    margin:auto;
}
.profile1-dropdown {
    position: absolute; /* Use absolute positioning */
    top: 10px; /* Distance from the top */
    right: 10px; /* Distance from the right */
    z-index: 1000; /* Ensure it appears above other elements */
}


    </style>
</head>
<body class="bg-gray-100 ">
    <div class="flex h-[120vh]">
        <!-- Sidebar -->
        <aside class="w-64 bg-red-800 h-[120vh]  text-white flex-shrink-0">
            <!-- Logo -->
            <div class="p-6 text-center">
            <img src="assets/images/school-logo/bcc-icon1.jpg" alt="Logo" class="custom-logo-size rounded-full">

            </div>

            <!-- Navigation -->
            <nav class="mt-4">
                <ul>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('home')"><i class="fas fa-home mr-3"></i> Home</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('profile')"><i class="fas fa-home mr-3"></i> Profile</a></li>
      
              
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('department')"><i class="fas fa-building mr-3"></i> Department</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('courses')"><i class="fas fa-graduation-cap mr-3"></i> Courses <i class="fas fa-chevron-right arrow-icon ml-auto"></i></a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('sections')"><i class="fas fa-list mr-3"></i> Sections</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('subjects')"><i class="fas fa-book mr-3"></i> Subjects</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('schedule')"><i class="fas fa-calendar-alt mr-3"></i> Schedule</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('instructor')"><i class="fas fa-chalkboard-teacher mr-3"></i> Instructor</a></li>
                    <li ><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('instructor-details')"><i class="fas fa-info-circle mr-3"></i> Instructor Subject Assignment</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('set-semester')"><i class="fas fa-calendar-check mr-3"></i> Set Semester</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('classroom')"><i class="fas fa-school mr-3"></i> Classroom</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('sex_option')"><i class="fas fa-file-alt mr-3"></i>Add Sex Options</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('status_option')"><i class="fas fa-file-alt mr-3"></i>Add Status Options</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('suffixes')"><i class="fas fa-file-alt mr-3"></i>Add Suffixes</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 mb-10 hover:bg-red-500" onclick="showContent('school_year')"><i class="fas fa-file-alt mr-3"></i>Add School Year</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-hidden ">
            <div id="home" class="content-section">
            <iframe src="profile/display_all_student.php" title="All Student"></iframe> 
            </div>

            <div id="profile" class="content-section">

                <iframe src="profile/student_profile.php" title="My Profile"></iframe>
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
                <iframe src="sections/read_sections.php" title="Sections"></iframe>
            </div>
            <div id="schedule" class="content-section">
                <iframe src="schedule/read_schedules.php" title="Schedule"></iframe>
            </div>
            <div id="students" class="content-section">
                <iframe src="students.php" title="Students"></iframe>
            </div>
            <div id="instructor" class="content-section">
                <iframe src="instructor/read_instructors.php" title="Instructor"></iframe>
            </div>
            <div id="instructor-details" class="content-section">
                <iframe src="instructor/instructor_subject/read_instructor_subject.php" title="Instructor Details"></iframe>
            </div>
            <div id="set-semester" class="content-section">
                <iframe src="semesters/read_semesters.php" title="Set Semester"></iframe>
            </div>
            <div id="classroom" class="content-section">
                <iframe src="classrooms/read_classrooms.php" title="Classroom"></iframe>
            </div>
            <div id="payment" class="content-section">
                <iframe src="payments/read_payments.php" title="Payment"></iframe>
            </div>
            <div id="sex_option" class="content-section">
                <iframe src="enrollment/sex_options.php" title="sex_option"></iframe>
            </div>
            <div id="school_year" class="content-section">
                <iframe src="enrollment/school_year.php" title="school_year"></iframe>
            </div>
            <div id="status_option" class="content-section">
                <iframe src="enrollment/status_options.php" title="status_option"></iframe>
            </div>
            <div id="suffixes" class="content-section">
                <iframe src="enrollment/suffixes.php" title="suffixes"></iframe>
            </div>
            <div id="school_year" class="content-section">
                <iframe src="enrollment/school_year.php" title="school_year"></iframe>
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

        // Toggle additional buttons visibility if instructor section is selected
        document.querySelectorAll('.additional-buttons').forEach(button => {
            if (id === 'instructor') {
                button.style.display = 'block';
            } else {
                button.style.display = 'none';
            }
        });

        // Store the currently selected section in localStorage
        localStorage.setItem('selectedSection', id);
    }

    // Check if there is a saved section in localStorage
    const savedSection = localStorage.getItem('selectedSection');
    if (savedSection) {
        showContent(savedSection);
    } else {
        // Default to showing home if no section is saved
        showContent('home');
    }


        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
  
    </script>
</body>
</html>
