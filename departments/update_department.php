<?php
session_start(); // Start the session to store error messages
require 'Department.php';

$department = new Department();

// Initialize variables to hold form data
$id = '';
$name = '';
$established = '';
$dean = '';
$email = '';
$phone = '';
$location = '';



// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $established = trim($_POST['established']);
    $dean = trim($_POST['dean']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);

    // Validation checks
    if (empty($name)) {
        $_SESSION['error_message'] = "Department name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $_SESSION['error_message'] = "Department name must contain only letters and spaces.";
    } elseif ($department->departmentExists($name, $id)) {
        $_SESSION['error_message'] = "Department name already exists.";
    } elseif (empty($established) || !is_numeric($established) || strlen($established) !== 4) {
        $_SESSION['error_message'] = "A valid established year (4 digits) is required.";
    } elseif (empty($dean) || !preg_match("/^[a-zA-Z\s]+$/", $dean)) {
        $_SESSION['error_message'] = "Dean name is required and must contain only letters.";
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "A valid email is required.";
    } elseif (empty($phone) || !preg_match('/^(?:\+63[9]\d{9}|09\d{9}|(?:\+63|0)?[9]\d{10}|^\d{4}-\d{4})$/', $phone)) {
        $_SESSION['error_message'] = "A valid phone number is required.";
    } elseif (empty($location)) {
        $_SESSION['error_message'] = "Location is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9\s,.'-]+$/", $location)) {
        $_SESSION['error_message'] = "Location can only contain letters, numbers, spaces, and the following characters: ,.'-";
    } elseif (strlen($location) < 3 || strlen($location) > 100) {
        $_SESSION['error_message'] = "Location must be between 3 and 100 characters.";
    } else {
        // If validation passes, update the department
        $faculty_count = $department->getFacultyCountByDepartment($id); // Get the faculty count from the department
        $department->update($id, $name, $established, $dean, $email, $phone, $location, $faculty_count);
        $_SESSION['success_message'] = 'Department updated successfully.';
    }

    // Redirect back to the form or the same page to display messages
    header('Location: update_department.php?id=' . $id); // Redirect back with ID
    exit();
}


// Check if 'id' is set in the URL query parameters
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $dept = $department->find($id);

    // Check if department was found
    if (!$dept) {
        echo 'Department not found.';
        exit();
    }
} else {
    echo 'No department ID provided.';
    exit();
}

// Set form variables if a department was found
if (isset($dept)) {
    $name = $dept['name'];
    $established = $dept['established'];
    $dean = $dept['dean'];
    $email = $dept['email'];
    $phone = $dept['phone'];
    $location = $dept['location'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Department</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">
        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-700 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
        </button>
        <h1 class="text-3xl font-bold text-red-800 mb-6 text-center">Update Department</h1>
        
   
        <?php

// Check if there's a success or error message
if (isset($_SESSION['success_message'])) {
    echo '<div class="bg-green-500 text-white p-4 rounded mb-4 animate__animated animate__fadeIn">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Clear the message after displaying

    // JavaScript redirect after 3 seconds (3000 milliseconds)
    echo '<script>
            setTimeout(function() {
                window.location.href = "read_departments.php";
            }, 3000);
          </script>';
} elseif (isset($_SESSION['error_message'])) {
    echo '<div id="error-message" class="bg-red-100 mb-2 text-red-700 border border-red-400 rounded px-4 py-3 mt-4 animate__animated animate__fadeIn">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Clear the error message after displaying

    // JavaScript to hide the error message after 5 seconds (5000 milliseconds)
    echo '<script>
            setTimeout(function() {
                var errorMessage = document.getElementById("error-message");
                if (errorMessage) {
                    errorMessage.classList.add("animate__fadeOut");
                    setTimeout(function() {
                        errorMessage.style.display = "none";
                    }, 1000); // Match the duration of the fade-out animation
                }
            }, 5000);
          </script>';
}
?>

        <form action="update_department.php" method="post" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="name" class="text-red-700 font-medium mr-2"><i class="fas fa-building"></i></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required placeholder="Department Name" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="established" class="text-red-700 font-medium mr-2"><i class="fas fa-calendar"></i></label>
                <input type="number" id="established" name="established" value="<?php echo htmlspecialchars($established); ?>" required placeholder="Established Year" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="dean" class="text-red-700 font-medium mr-2"><i class="fas fa-user-tie"></i></label>
                <input type="text" id="dean" name="dean" value="<?php echo htmlspecialchars($dean); ?>" required placeholder="Dean" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="email" class="text-red-700 font-medium mr-2"><i class="fas fa-envelope"></i></label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="Contact Email" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="phone" class="text-red-700 font-medium mr-2"><i class="fas fa-phone"></i></label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required placeholder="Phone" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500">
            </div>
            <div class="flex items-center border-b border-red-300 py-2">
                <label for="location" class="text-red-700 font-medium mr-2"><i class="fas fa-map-marker-alt"></i></label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required placeholder="Location" class="mt-1 block w-full px-3 py-2 border-none focus:outline-none focus:ring-0 focus:border-b-2 focus:border-red-500">
            </div>
            <div class="flex justify-center">
                <button type="submit" class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200">Update Department</button>
            </div>
        </form>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
