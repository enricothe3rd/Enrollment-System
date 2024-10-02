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
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
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
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('enrollment_fee')"><i class="fas fa-user-plus mr-3"></i> Enrollments Payment </a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('ojt_fee')"><i class="fas fa-user mr-3"></i> OJT Fees</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('research_fee')"><i class="fas fa-building mr-3"></i> Research Fees</a></li>
                  
                  
</ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-hidden ">
            <div id="home" class="content-section">
            <iframe src="payments/enrollment_payments_crud/view_all_payments.php" title="all_payments"></iframe>
            </div>
            <div id="profile" class="content-section">

<iframe src="profile/student_profile.php" title="My Profile"></iframe>
</div>

            <div id="enrollment_fee" class="content-section">
                <iframe src="payments/enrollment_payments_crud/enrollment_payments.php" title="enrollment_fee"></iframe>
            </div>
            <div id="research_fee" class="content-section">
            <iframe src="payments/enrollment_payments_crud/research_fees.php" title="ojt_fee"></iframe>
   
   </div>
            <div id="ojt_fee" class="content-section">
            <iframe src="payments/enrollment_payments_crud/ojt_fees.php" title="research_fee"></iframe>
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
