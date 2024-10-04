<?php
session_start(); // Start the session

require 'Instructor.php';

// Create an instance of the Instructor
$instructor = new Instructor(Database::connect());

// Initialize variables to retain the form data
$firstName = isset($_SESSION['last_first_name']) ? htmlspecialchars($_SESSION['last_first_name']) : '';
$middleName = isset($_SESSION['last_middle_name']) ? htmlspecialchars($_SESSION['last_middle_name']) : '';
$lastName = isset($_SESSION['last_last_name']) ? htmlspecialchars($_SESSION['last_last_name']) : '';
$suffix = isset($_SESSION['last_suffix']) ? htmlspecialchars($_SESSION['last_suffix']) : '';
$email = isset($_SESSION['last_email']) ? htmlspecialchars($_SESSION['last_email']) : '';

// Clear session variables after retrieving their values
unset($_SESSION['last_first_name']);
unset($_SESSION['last_middle_name']);
unset($_SESSION['last_last_name']);
unset($_SESSION['last_suffix']);
unset($_SESSION['last_email']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted form data
    $firstName = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
    $middleName = isset($_POST['middle_name']) ? htmlspecialchars($_POST['middle_name']) : '';
    $lastName = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
    $suffix = isset($_POST['suffix']) ? htmlspecialchars($_POST['suffix']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';


    // Store inputs in session for retrieval after redirect
    $_SESSION['last_first_name'] = $firstName;
    $_SESSION['last_middle_name'] = $middleName;
    $_SESSION['last_last_name'] = $lastName;
    $_SESSION['last_suffix'] = $suffix;
    $_SESSION['last_email'] = $email;
  
    
    // Handle section creation
    $instructor->handleCreateInstructorRequest();
}
// Fetch departments, courses, and sections for the dropdowns
$departments = $instructor->getDepartments();
$courses = $instructor->getCourses();
$sections = $instructor->getSections();
$emails = $instructor->getAllEmails(); // Fetch emails

// Check for the message in the query string or session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); // Clear the message after displaying it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Instructor</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Function to update courses based on department selection
            function updateCourses() {
                const departmentId = document.getElementById('department_id').value;
                const courseSelect = document.getElementById('course_id');
                courseSelect.innerHTML = '<option value="" disabled selected>Select a Course</option>'; // Clear previous options
                const sectionSelect = document.getElementById('section_id');
                sectionSelect.innerHTML = '<option value="" disabled selected>Select a Section</option>'; // Clear previous options

                if (departmentId) {
                    fetch(`fetch_courses.php?department_id=${departmentId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            data.forEach(course => {
                                const option = document.createElement('option');
                                option.value = course.id;
                                option.textContent = course.course_name;
                                courseSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Fetch error:', error));
                }
            }

            // Function to update sections based on course selection
            function updateSections() {
                const courseId = document.getElementById('course_id').value;
                const sectionSelect = document.getElementById('section_id');
                sectionSelect.innerHTML = '<option value="" disabled selected>Select a Section</option>'; // Clear previous options

                if (courseId) {
                    fetch(`fetch_sections.php?course_id=${courseId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            data.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                sectionSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Fetch error:', error));
                }
            }

            // Add event listeners for the dropdowns
            document.getElementById('department_id').addEventListener('change', updateCourses);
            document.getElementById('course_id').addEventListener('change', updateSections);
        });
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md bg-white shadow-md rounded-lg p-8 font-sans leading-normal tracking-normal">
<button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        
    <h1 class="text-2xl font-semibold text-red-800 mb-4">Create Instructor</h1>
    <?php if (isset($_GET['message'])): ?>
 
    
    <?php if ($message == 'invalid_name'): ?>
        <div id="error-message" class="mt-4 bg-red-200 text-red-700 p-4 rounded">
            <h2 class="text-lg font-semibold">Error</h2>
            <p>The instructor's name is invalid. Please use only alphabets and spaces.</p>
        </div>

    <?php elseif ($message == 'invalid_email'): ?>
        <div id="error-message" class="mt-4 bg-red-200 text-red-700 p-4 rounded">
            <h2 class="text-lg font-semibold">Error</h2>
            <p>The email format is invalid. Please provide a valid email address.</p>
        </div>

    <?php elseif ($message == 'exists'): ?>
        <div id="error-message" class="mt-4 bg-red-200 text-red-700 p-4 rounded">
            <h2 class="text-lg font-semibold">Error</h2>
            <p>An instructor with this email already exists.</p>
        </div>

    <?php elseif ($message == 'success'): ?>
        <div id="success-message" class="mt-4 bg-green-200 text-green-700 p-4 rounded">
            <h2 class="text-lg font-semibold">Success</h2>
            <p>The instructor has been created successfully.</p>
        </div>

    <?php elseif ($message == 'failure'): ?>
        <div id="error-message" class="mt-4 bg-red-200 text-red-700 p-4 rounded">
            <h2 class="text-lg font-semibold">Error</h2>
            <p>There was an issue creating the instructor. Please try again.</p>
        </div>
    <?php endif; ?>

    <script>
        // Set a timeout to hide the messages after 3 seconds
        setTimeout(function() {
            var errorMessage = document.getElementById('error-message');
            var successMessage = document.getElementById('success-message');
            if (errorMessage) {
                errorMessage.style.display = 'none'; // Hide the error message
            }
            if (successMessage) {
                successMessage.style.display = 'none'; // Hide the success message
            }
        }, 3000); // Hide after 3000 milliseconds (3 seconds)
    </script>
<?php endif; ?>


  <form method="POST" class="space-y-4">
    <div>
    <label for="first_name" class="block text-red-700 font-medium">First Name</label>
    <!-- First Name -->
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-user  text-red-500 px-3"></i>
    
            
            <input type="text" id="first_name" name="first_name"  value="<?php echo $firstName; ?>" placeholder="Enter First Name" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
        
    </div>
    </div>
    <!-- Middle Name -->
     <div>
     <label for="middle_name" class="block text-red-700 font-medium">Middle Name</label>
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-user  text-red-500 px-3"></i>
    
           
            <input type="text" id="middle_name" name="middle_name"  value="<?php echo $middleName; ?>" placeholder="Enter Middle Name" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
        
    </div>
    </div>
    <!-- Last Name -->
     <div>
     <label for="last_name" class="block text-red-700 font-medium">Last Name</label>
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-user  text-red-500 px-3"></i>
    
    
            <input type="text" id="last_name" name="last_name"  value="<?php echo $lastName; ?>" placeholder="Enter Last Name" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
 
    </div>
    </div>
    <!-- Suffix -->
     <div>
     <label for="suffix" class="block text-red-700 font-medium">Suffix (optional)</label>
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-user  text-red-500 px-3"></i>
    
           
            <input type="text" id="suffix" name="suffix"  value="<?php echo $suffix; ?>" placeholder="Enter Suffix Name" class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
   
    </div>
    </div>

 <!-- Email Dropdown -->
<div>
    <label for="email" class="block text-red-700 font-medium">Email</label>
    <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-envelope text-red-500 px-3"></i>
        <select id="email" name="email" required class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
            <option value="" disabled selected>Select an Email</option>
            <?php foreach ($emails as $user): ?>
                <option value="<?= htmlspecialchars($user['email']) ?>"><?= htmlspecialchars($user['email']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

    <!-- Department -->
     <div>
     <label for="department_id" class="block text-red-700 font-medium">Department</label>
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-building  text-red-500 px-3"></i>
    
          
            <select id="department_id" name="department_id" required class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="" disabled selected>Select a Department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= htmlspecialchars($department['id']) ?>"><?= htmlspecialchars($department['name']) ?></option>
                <?php endforeach; ?>
            </select>
   
    </div>
    </div>
    <!-- Course -->
     <div>
    <label for="course_id" class="block text-red-700 font-medium">Course</label>
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-book  text-red-500 px-3"></i>
    
            
            <select id="course_id" name="course_id" required class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="" disabled selected>Select a Course</option>
                <!-- Courses will be populated based on department selection -->
            </select>

    </div>
    </div>

    <!-- Section -->
     <div>
     <label for="section_id" class="block text-red-700 font-medium">Section</label>
      <div class="flex items-center border border-red-300 rounded-md shadow-sm">
        <i class="fas fa-chalkboard-teacher  text-red-500 px-3"></i>
    
            <select id="section_id" name="section_id" required class="bg-red-50 block w-full px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="" disabled selected>Select a Section</option>
                <!-- Sections will be populated based on course selection -->
            </select>

    </div>
    </div>

    <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-md">
        <i class="fas fa-save mr-2"></i>Create
    </button>
</form>

</div>

<script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }
    </script>
</body>
</html>
