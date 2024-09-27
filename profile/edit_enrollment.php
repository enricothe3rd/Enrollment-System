<?php
// Start session
session_start();

require '../db/db_connection3.php'; // Ensure this is the correct path to your Database class
$pdo = Database::connect();

if (isset($_GET['student_number'])) {
    $student_number = $_GET['student_number'];

    try {
        // Prepare the SQL statement to fetch enrollment details for the specific student
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   c.course_name, 
                   s.name AS section_name, 
                   d.name AS department_name
            FROM enrollments e
            LEFT JOIN courses c ON e.course_id = c.id
            LEFT JOIN sections s ON e.section_id = s.id
            LEFT JOIN departments d ON c.department_id = d.id
            WHERE e.student_number = :student_number
        ");
        $stmt->bindParam(':student_number', $student_number);
        $stmt->execute();

        // Fetch the enrollment data
        $enrollmentData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle any SQL errors
        echo "Error: " . $e->getMessage();
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize input data
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $suffix = $_POST['suffix'];
        $email = $_POST['email'];
        $contact_no = $_POST['contact_no'];
        $course_id = $_POST['course_id'];
        $section_id = $_POST['section_id'];
        $sex = $_POST['sex'];
        $dob = $_POST['dob'];

        try {
            // Prepare the SQL statement to update the enrollment data
            $updateStmt = $pdo->prepare("
                UPDATE enrollments
                SET firstname = :firstname,
                    middlename = :middlename,
                    lastname = :lastname,
                    suffix = :suffix,
                    email = :email,
                    contact_no = :contact_no,
                    course_id = :course_id,
                    section_id = :section_id,
                    sex = :sex,
                    dob = :dob
                WHERE student_number = :student_number
            ");

            // Bind parameters
            $updateStmt->bindParam(':firstname', $firstname);
            $updateStmt->bindParam(':middlename', $middlename);
            $updateStmt->bindParam(':lastname', $lastname);
            $updateStmt->bindParam(':suffix', $suffix);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':contact_no', $contact_no);
            $updateStmt->bindParam(':course_id', $course_id);
            $updateStmt->bindParam(':section_id', $section_id);
            $updateStmt->bindParam(':sex', $sex);
            $updateStmt->bindParam(':dob', $dob);
            $updateStmt->bindParam(':student_number', $student_number);

            // Execute the update statement
            if ($updateStmt->execute()) {
                // Redirect to success page
                header("Location: display_all_student.php"); // Replace with your success page
                exit;
            } else {
                echo "Error updating record: " . $updateStmt->errorInfo()[2];
            }
        } catch (PDOException $e) {
            // Handle any SQL errors
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    echo "No student number provided.";
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Update Enrollment</title>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-lg">
        <button 
            onclick="goBack()" 
            class="mb-4 px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 transition duration-200 flex items-center"
        >
            <i class="fas fa-arrow-left mr-2"></i> <!-- Arrow icon -->
            Back
        </button>
        <h1 class="text-3xl font-bold text-red-800 mb-6">Update Enrollment for <?= htmlspecialchars($enrollmentData['firstname'] . ' ' . $enrollmentData['lastname']) ?></h1>
        
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="student_number" value="<?= htmlspecialchars($enrollmentData['student_number']) ?>">

            <div>
                <label for="firstname" class="block text-sm font-medium text-red-700">First Name</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-user text-red-500 px-3"></i>
                    <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($enrollmentData['firstname']) ?>" required 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter First Name">
                </div>
            </div>
            
            <div>
                <label for="lastname" class="block text-sm font-medium text-red-700">Last Name</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-user text-red-500 px-3"></i>
                    <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($enrollmentData['lastname']) ?>" required 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter Last Name">
                </div>
            </div>

            <div>
                <label for="middlename" class="block text-sm font-medium text-red-700">Middle Name</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-user-circle text-red-500 px-3"></i>
                    <input type="text" name="middlename" id="middlename" value="<?= htmlspecialchars($enrollmentData['middlename']) ?>" 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter Middle Name">
                </div>
            </div>

            <div>
                <label for="suffix" class="block text-sm font-medium text-red-700">Suffix</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-tag text-red-500 px-3"></i>
                    <input type="text" name="suffix" id="suffix" value="<?= htmlspecialchars($enrollmentData['suffix']) ?>" 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter Suffix">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-red-700">Email</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-envelope text-red-500 px-3"></i>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($enrollmentData['email']) ?>" required 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter Email">
                </div>
            </div>

            <div>
                <label for="contact_no" class="block text-sm font-medium text-red-700">Contact Number</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-phone text-red-500 px-3"></i>
                    <input type="text" name="contact_no" id="contact_no" value="<?= htmlspecialchars($enrollmentData['contact_no']) ?>" required 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter Contact Number">
                </div>
            </div>

            <div>
                <label for="course_id" class="block text-sm font-medium text-red-700">Course</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-book text-red-500 px-3"></i>
                    <select name="course_id" id="course_id" required 
                            class="w-full h-12 bg-red-50 text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md transition duration-200">
                        <option value="" disabled selected>Select a Course</option>
                        <?php
                        // Fetch all courses to populate the select options
                        $coursesStmt = $pdo->query("SELECT * FROM courses");
                        while ($course = $coursesStmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = $course['id'] == $enrollmentData['course_id'] ? 'selected' : '';
                            echo "<option value=\"{$course['id']}\" $selected>" . htmlspecialchars($course['course_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="section_id" class="block text-sm font-medium text-red-700">Section</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-list text-red-500 px-3"></i>
                    <select name="section_id" id="section_id" required 
                            class="w-full h-12 bg-red-50 text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md transition duration-200">
                        <option value="" disabled selected>Select a Section</option>
                        <?php
                        // Fetch all sections to populate the select options
                        $sectionsStmt = $pdo->query("SELECT * FROM sections");
                        while ($section = $sectionsStmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = $section['id'] == $enrollmentData['section_id'] ? 'selected' : '';
                            echo "<option value=\"{$section['id']}\" $selected>" . htmlspecialchars($section['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="sex" class="block text-sm font-medium text-red-700">Sex</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-venus-mars text-red-500 px-3"></i>
                    <select name="sex" id="sex" required 
                            class="w-full h-12 bg-red-50 text-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md transition duration-200">
                        <option value="Male" <?= $enrollmentData['sex'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $enrollmentData['sex'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $enrollmentData['sex'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="dob" class="block text-sm font-medium text-red-700">Date of Birth</label>
                <div class="flex items-center border border-red-300 rounded-md shadow-sm">
                    <i class="fas fa-calendar-alt text-red-500 px-3"></i>
                    <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($enrollmentData['dob']) ?>" required 
                           class="w-full h-10 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            <button type="submit" class="col-span-1 md:col-span-2 bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded transition duration-200">
                Update Enrollment
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
