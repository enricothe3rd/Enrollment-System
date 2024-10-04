<?php
require 'Department.php';

$department = new Department();
$departments = $department->read();
$message = ''; // Initialize message variable
$messageType = ''; // Initialize message type variable

// Example of deletion logic
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $departmentId = $_GET['id'];
    $message = $department->delete($departmentId); // Get the message from delete method

    // Determine message type based on deletion success or failure
    if (strpos($message, 'successfully') !== false) {
        $messageType = 'success';
    } else {
        $messageType = 'error';
    }

    header("Location: read_departments.php?message=" . urlencode($message) . "&type=" . urlencode($messageType)); // Redirect with message
    exit;
}

// Capture the message if it exists in the query string
if (isset($_GET['message']) && isset($_GET['type'])) {
    $message = $_GET['message'];
    $messageType = $_GET['type'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="font-sans leading-normal tracking-normal">
    <div class="mt-10">
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Departments</h1>
        <a href="create_department.php" class="inline-block mb-4 px-4 py-4 bg-red-700 text-white rounded hover:bg-red-800">Add New Department</a>

        <table class="w-full border-collapse shadow-md rounded-lg">
            <thead class="bg-red-800">
                <tr>
                    <th class="px-4 py-4 border-b text-left text-white">Name</th>
                    <th class="px-4 py-4 border-b text-left text-white">Established</th>
                    <th class="px-4 py-4 border-b text-left text-white">Dean</th>
                    <th class="px-4 py-4 border-b text-left text-white">Email</th>
                    <th class="px-4 py-4 border-b text-left text-white">Phone</th>
                    <th class="px-4 py-4 border-b text-left text-white">Location</th>
                    <th class="px-4 py-4 border-b text-left text-white">Faculty Count</th>
                    <th class="px-4 py-4 border-b text-left text-white">Student Count</th>
                    <th class="px-4 py-4 border-b text-left text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $dept): ?>
                <tr class="border-b bg-red-50 hover:bg-red-200">
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['name']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['established']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['dean']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['email']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['phone']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['location']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['faculty_count']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['student_count']); ?></td>
                    <td class="px-4 py-4">
                        <a href="update_department.php?id=<?php echo $dept['id']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded">Edit</a>
                        <button data-department-id="<?php echo $dept['id']; ?>" class="delete-button bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Confirm Delete</h2>
            <p class="text-gray-600">Are you sure you want to delete this department?</p>
            <div class="flex justify-end mt-6">
                <button id="cancelDelete" class="mr-2 bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 transition">Cancel</button>
                <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Delete</button>
            </div>
        </div>
    </div>

    <!-- Success Message Modal -->
    <div id="successModal" class="fixed inset-0 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm text-center">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Success</h2>
            <img src="../assets/images/modal-icons/checked.png" alt="Success Icon" class="mb-4 mx-auto w-25 h-25 object-cover rounded-full">
            <p id="successMessageText" class="text-gray-600"></p>
            <div class="flex justify-center mt-6">
                <button id="closeSuccessModal" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Close</button>
            </div>
        </div>
    </div>

<!-- Error Message Modal -->
<div id="errorModal" class="fixed inset-0 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm text-center">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Error</h2>
        <img src="../assets/images/modal-icons/cancel.png" alt="Error Icon" class="mb-4 mx-auto w-16 h-16 object-cover rounded-full">
        <p id="errorMessageText" class="text-gray-600"></p>
        <div class="flex justify-center mt-6">
            <button id="closeErrorModal" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300 ease-in-out">Close</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let departmentIdToDelete;

    // Open confirmation modal on delete button click
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            departmentIdToDelete = this.getAttribute('data-department-id');
            const modal = document.getElementById('confirmationModal');
            modal.classList.remove('hidden');
            modal.classList.add('animate__animated', 'animate__fadeIn'); // Add fade-in animation
        });
    });

    // Handle confirmation
    document.getElementById('confirmDelete').addEventListener('click', function() {
        // Redirect to the PHP delete script
        window.location.href = `read_departments.php?id=${departmentIdToDelete}`;
    });

    // Close confirmation modal on cancel
    document.getElementById('cancelDelete').addEventListener('click', function() {
        const modal = document.getElementById('confirmationModal');
        modal.classList.add('animate__fadeOut'); // Add fade-out animation

        // Create a handler function for animation end
        const handleAnimationEnd = function() {
            modal.classList.add('hidden'); // Hide modal after animation completes
            modal.classList.remove('animate__fadeOut'); // Remove animation class
            modal.removeEventListener('animationend', handleAnimationEnd); // Remove the listener
        };

        // Add the event listener for animation end
        modal.addEventListener('animationend', handleAnimationEnd);
    });

    // Show appropriate message modal based on message type
    const message = <?php echo json_encode($message); ?>;
    const messageType = <?php echo json_encode($messageType); ?>;

    if (message) {
        const messageText = messageType === 'success' ? document.getElementById('successMessageText') : document.getElementById('errorMessageText');
        const modalToShow = messageType === 'success' ? document.getElementById('successModal') : document.getElementById('errorModal');

        messageText.textContent = message;
        modalToShow.classList.remove('hidden');

        // If it's a success message, add fade-in animation
        if (messageType === 'success') {
            modalToShow.classList.add('animate__animated', 'animate__fadeIn'); // Add fade-in animation
        }

        // If it's an error message, add head shake animation immediately without fade-in
        if (messageType === 'error') {
            modalToShow.classList.add('animate__animated', 'animate__headShake'); // Add head shake animation
        }
    }

    // Close modals on button click
    document.getElementById('closeSuccessModal').addEventListener('click', function() {
        const modal = document.getElementById('successModal');
        modal.classList.add('animate__fadeOut'); // Add fade-out animation

        modal.addEventListener('animationend', function() {
            modal.classList.add('hidden'); // Hide modal after animation completes
            modal.classList.remove('animate__fadeOut'); // Remove animation class
        });
    });

    document.getElementById('closeErrorModal').addEventListener('click', function() {
        const modal = document.getElementById('errorModal');
        modal.classList.add('animate__fadeOut'); // Add fade-out animation

        modal.addEventListener('animationend', function() {
            modal.classList.add('hidden'); // Hide modal after animation completes
            modal.classList.remove('animate__fadeOut'); // Remove animation class
            modal.classList.remove('animate__headShake'); // Remove head shake class to reset state
        });
    });
});
</script>
