<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment Dashboard</title>
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
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-200 text-red-500 flex-shrink-0">
            <!-- Logo -->
            <div class="p-6 text-center">
                <img src="assets/images/school-logo/bcc-icon.png" alt="Logo" class="mx-auto">
            </div>

            <!-- Navigation -->
            <nav class="mt-4">
                <ul>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-300" onclick="showContent('home')"><i class="fas fa-home mr-3"></i> Home</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-300" onclick="showContent('messages')"><i class="fas fa-building mr-3"></i> Messages</a></li>
                    <li><a href="#" class="flex items-center py-2 px-4 hover:bg-gray-300" onclick="showContent('logout')"><i class="fas fa-building mr-3"></i> Logout</a></li>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-hidden  rounded-lg shadow-lg">
            <div id="home" class="content-section" style="height:100vh">
                <iframe src="payments/enrollments/create_enrollment.php" title="Home"></iframe>
            </div>
            <div id="messages" class="content-section">
                <iframe src="enrollments/create_enrollment.php" title="Messages"></iframe>
            </div>
            <div id="logout" class="content-section">
                <iframe src="departments/read_departments.php" title="Log out"></iframe>
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
        localStorage.setItem('currentSection', id);
    }

    // Load the last viewed section from localStorage on page load
    window.onload = function() {
        const lastViewedSection = localStorage.getItem('currentSection') || 'home';
        showContent(lastViewedSection);
    };
    </script>
</body>
</html>
