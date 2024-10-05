<?php
session_start(); // Start the session

// Include the Database class file (adjust the path if necessary)
require_once 'db/db_connection3.php'; // Adjust the path to where your Database class is defined

// Fetch the student_number and email from the session
$student_number = $_SESSION['student_number'] ?? null;
$email = $_SESSION['user_email'] ?? null; // Adjusted to use session variable directly

// Call the connect method to get PDO instance
$pdo = Database::connect();

$payment_method = null; // Initialize payment method

if ($student_number) {
    // Prepare the SQL query to fetch the payment method
    $sql = "SELECT payment_method FROM payments WHERE student_number = :student_number LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':student_number', $student_number, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Fetch the result
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a payment record was found
    if ($payment) {
        $payment_method = $payment['payment_method'];
    }
}

// Echo message based on payment method existence
// if ($payment_method) {
//     echo "<div class='text-green-600'>Payment method exists: $payment_method</div>";
// } else {
//     echo "<div class='text-red-600'>No payment method found for student number: $student_number</div>";
// }
?>

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
        nav a:hover {
            background-color: #f87171;
            color: #fff;
        }
        nav a {
            transition: all 0.3s ease;
        }
        .custom-logo-size {
            height: 9rem;
            width: 8.6rem;
            margin: auto;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-red-800 text-white flex-shrink-0">
            <!-- Logo -->
            <div class="p-6 text-center">
                <img src="assets/images/school-logo/bcc-icon1.jpg" alt="Logo" class="custom-logo-size rounded-full">
            </div>

            <!-- Navigation -->
            <nav class="mt-4">
                <ul>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('home')"><i class="fas fa-home mr-3"></i> Home</a></li>
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('profile')"><i class="fas fa-home mr-3"></i> My Profile</a></li>
                    <?php if (empty($payment_method)): ?>
    <li>
        <a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('enrollment')">
            <i class="fas fa-user-plus mr-3"></i> Enrollments
        </a>
    </li>
<?php endif; ?>

  

                    
                    <li><a href="#" class="flex items-center py-3 px-4 hover:bg-red-500" onclick="showContent('department')"><i class="fas fa-building mr-3"></i> Research Fees</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-hidden">
            <div id="home" class="content-section">
            <iframe src="Enrolled_subject/enrolled_subject.php" title="My Subjects"></iframe>
            </div>

            <div id="profile" class="content-section">
                <iframe src="instructor/get_grades.php" title="My Profile"></iframe>
            </div>
            <?php if (empty($payment_method)): ?>
            <div id="enrollment" class="content-section">
                <iframe src="payments/enrollments/create_enrollment.php" title="New Enrollments"></iframe>
            </div>
            <?php endif; ?>
            <div id="department" class="content-section">
                <iframe src="Enrolled_subject/enrolled_subject" title="Research Fees"></iframe>
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
