<?php
require 'Department.php';
session_start(); // Start the session for using $_SESSION
// Include the success modal
include '../message/success_modal.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $established = $_POST['established'];
    $dean = $_POST['dean'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    // Store the entered data in session to retain input values
    $_SESSION['form_data'] = [
        'name' => $name,
        'established' => $established,
        'dean' => $dean,
        'email' => $email,
        'phone' => $phone,
        'location' => $location
    ];

    // Create a new department instance
    $department = new Department();

    // Trim the input to remove unnecessary spaces
    $name = trim($name);

    // Validation checks
    if (empty($name)) {
        $_SESSION['error_message'] = "Department name is required.";
        header('Location: create_department.php'); // Redirect back to the form
        exit;
    }

    // Check if the name contains only letters and spaces
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $_SESSION['error_message'] = "Department name must contain only letters and spaces.";
        header('Location: create_department.php'); // Redirect back to the form
        exit;
    }

    // Check if the department name already exists
    if ($department->departmentExists($name)) {
        $_SESSION['error_message'] = "Department name already exists.";
        header('Location: create_department.php'); // Redirect back to the form
        exit;
    }

    if (empty($established) || !is_numeric($established) || strlen($established) !== 4) {
        $_SESSION['error_message'] = "A valid established year (4 digits) is required.";
        header('Location: create_department.php');
        exit;
    }

    if (empty($dean) || !preg_match("/^[a-zA-Z\s]+$/", $dean)) {
        $_SESSION['error_message'] = "Dean name is required and must contain only letters.";
        header('Location: create_department.php');
        exit;
    }


    // List of valid email providers
    $valid_providers = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];

    // Check if email is empty or invalid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "A valid email is required.";
        header('Location: create_department.php');
        exit;
    }

    // Extract the domain from the email address
    $email_domain = substr(strrchr($email, "@"), 1);

    // Check if the domain is in the list of valid providers
    if (!in_array($email_domain, $valid_providers)) {
        $_SESSION['error_message'] = "Email format must be one of the following: " . implode(', ', $valid_providers) . ".";
        header('Location: create_department.php');
        exit;
    }

    // Proceed with your logic if the email is valid and from an allowed provider

    $phone = trim($_POST['phone']); // Trim to remove any extra spaces

    // Validate phone number: 
    // - It should be either in the format of 11 digits (with optional country code), 
    // - or landline format of xxxx-xxxx.
    if (empty($phone) || 
        !preg_match('/^(?:\+63[9]\d{9}|09\d{9}|(?:\+63|0)?[9]\d{10}|^\d{4}-\d{4})$/', $phone)) {
        $_SESSION['error_message'] = "A valid phone number is required.";
        header('Location: create_department.php');
        exit;
    }

    // Validate location
    $location = trim($location); // Trim whitespace

    if (empty($location)) {
        $_SESSION['error_message'] = "Location is required.";
        header('Location: create_department.php');
        exit;
    }

    if (!preg_match("/^[a-zA-Z0-9\s,.'-]+$/", $location)) {
        $_SESSION['error_message'] = "Location can only contain letters, numbers, spaces, and the following characters: ,.'-";
        header('Location: create_department.php');
        exit;
    }

    if (strlen($location) < 3 || strlen($location) > 100) {
        $_SESSION['error_message'] = "Location must be between 3 and 100 characters.";
        header('Location: create_department.php');
        exit;
    }

    // Get the faculty count from the instructors table (optional, adjust according to your logic)
    $faculty_count = $department->getFacultyCountByDepartment($name);

    // Attempt to create the department
    if ($department->create($name, $established, $dean, $email, $phone, $location, $faculty_count)) {
        // Set a flag to show the modal and success message
        $_SESSION['success_message'] = "Department successfully created.";
        $_SESSION['show_modal'] = true; // Set a flag to show the modal

        // Clear form data on success
        unset($_SESSION['form_data']);

        // Redirect to the form page
        header('Location: create_department.php'); // Redirect to the form page
        exit;
    } else {
        $_SESSION['error_message'] = "Failed to create department.";
        header('Location: create_department.php'); // Redirect back to the form if creation fails
        exit;
    }
}

$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Department</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />



</head>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg max-w-md">

        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-700 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>

        <h1 class="text-3xl font-bold text-red-800 mb-6 text-center">Add New Department</h1>

        <?php include '../message/message_handler.php'; ?>




        <form action="create_department.php" method="post" class="space-y-6">
    <div class="flex flex-col">
        <label for="name" class="text-red-700 font-medium">Department Name</label>
        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
            <label for="name" class="px-3 text-red-700 font-medium"><i class="fas fa-building"></i></label>
            <input type="text" id="name" name="name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>" placeholder="Department Name" class="block w-full px-3 py-2 bg-red-50 text-red-800 border-none focus:outline-none focus:bg-red-100 focus:border-red-500 rounded-r-md">
        </div>
    </div>
    <div class="flex flex-col">
    <label for="established" class="text-red-700 font-medium">Established Year</label>
    <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <label for="established" class="px-3 text-red-700 font-medium"><i class="fas fa-calendar"></i></label>
        <input type="number" id="established" name="established" 
               value="<?php echo isset($form_data['established']) ? htmlspecialchars($form_data['established']) : ''; ?>" 
               placeholder="Enter Established Year (YYYY)" 
               class="block w-full px-3 py-2 bg-red-50 text-red-800 border-none focus:outline-none focus:bg-red-100 focus:border-red-500 rounded-r-md" 
               min="1900" max="2099" required>
    </div>
</div>

    <div class="flex flex-col">
        <label for="dean" class="text-red-700 font-medium">Dean</label>
        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
            <label for="dean" class="px-3 text-red-700 font-medium"><i class="fas fa-user-tie"></i></label>
            <input type="text" id="dean" name="dean" value="<?php echo isset($form_data['dean']) ? htmlspecialchars($form_data['dean']) : ''; ?>" placeholder="Dean" class="block w-full px-3 py-2 bg-red-50 text-red-800 border-none focus:outline-none focus:bg-red-100 focus:border-red-500 rounded-r-md">
        </div>
    </div>
    <div class="flex flex-col">
        <label for="email" class="text-red-700 font-medium">Contact Email</label>
        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
            <label for="email" class="px-3 text-red-700 font-medium"><i class="fas fa-envelope"></i></label>
            <input type="email" id="email" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" placeholder="Contact Email" class="block w-full px-3 py-2 bg-red-50 text-red-800 border-none focus:outline-none focus:bg-red-100 focus:border-red-500 rounded-r-md">
        </div>
    </div>
    <div class="flex flex-col">
    <label for="phone" class="text-red-700 font-medium">Phone</label>
    <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <label for="phone" class="px-3 text-red-700 font-medium"><i class="fas fa-phone"></i></label>
        <input type="text" id="phone" name="phone" value="<?php echo isset($form_data['phone']) ? htmlspecialchars($form_data['phone']) : ''; ?>" placeholder="e.g. +639123456789 or 021234567" class="block w-full px-3 py-2 bg-red-50 text-red-800 border-none focus:outline-none focus:bg-red-100 focus:border-red-500 rounded-r-md">
    </div>
</div>

    <div class="flex flex-col">
        <label for="location" class="text-red-700 font-medium">Location</label>
        <div class="flex items-center border border-red-300 rounded-md shadow-sm">
            <label for="location" class="px-3 text-red-700 font-medium"><i class="fas fa-map-marker-alt"></i></label>
            <input type="text" id="location" name="location" value="<?php echo isset($form_data['location']) ? htmlspecialchars($form_data['location']) : ''; ?>" placeholder="Location" class="block w-full px-3 py-2 bg-red-50 text-red-800 border-none focus:outline-none focus:bg-red-100 focus:border-red-500 rounded-r-md">
        </div>
    </div>

    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 flex items-center justify-center">
        <i class="fas fa-plus mr-2"></i> Create Department
    </button>
</form>

    </div>







    
    <script>
function goBack() {
    // Directly navigate to read_departments.php
    window.location.href = 'read_departments.php';

    // Optionally clear the session in the background
    fetch('../session/clear_session.php', { method: 'POST' })
}
</script>




</body>
</html>
