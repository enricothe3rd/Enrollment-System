<?php
session_start();
require 'session_timeout.php';
require 'db/db_connection1.php';

// Check if user is logged in and their role is either 'student' or 'admin'
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'student' && $_SESSION['user_role'] !== 'admin')) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user email and student_id
$sql = "SELECT u.email, u.id AS student_id 
        FROM users u 
        WHERE u.id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo "Error: User not found.";
    exit();
}

$email = htmlspecialchars($result['email']);
$student_id = $result['student_id'];

// Fetch student's existing enrollment data
$enrollment_sql = "SELECT * FROM enrollment WHERE student_id = :student_id";
$enrollment_stmt = $pdo->prepare($enrollment_sql);
$enrollment_stmt->bindParam(':student_id', $student_id);
$enrollment_stmt->execute();
$enrollment_data = $enrollment_stmt->fetch(PDO::FETCH_ASSOC);

// Fetch School Years
$school_years_sql = "SELECT year FROM school_years";
$school_years_stmt = $pdo->prepare($school_years_sql);
$school_years_stmt->execute();
$school_years = $school_years_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Semesters
$semesters_sql = "SELECT semester FROM semesters";
$semesters_stmt = $pdo->prepare($semesters_sql);
$semesters_stmt->execute();
$semesters = $semesters_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Status Options
$status_sql = "SELECT status_name FROM status_options";
$status_stmt = $pdo->prepare($status_sql);
$status_stmt->execute();
$status_options = $status_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Sex Options
$sex_sql = "SELECT sex_name FROM sex_options";
$sex_stmt = $pdo->prepare($sex_sql);
$sex_stmt->execute();
$sex_options = $sex_stmt->fetchAll(PDO::FETCH_ASSOC);


// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $suffix = $_POST['suffix'];
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester'];
    $sex = $_POST['sex'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $contact_no = $_POST['contact_no'];
    $status = $_POST['status'];

    if ($enrollment_data) {
        // Update the existing record
        $update_sql = "UPDATE enrollment 
                       SET lastname = :lastname, firstname = :firstname, middlename = :middlename, suffix = :suffix, 
                           school_year = :school_year, semester = :semester, sex = :sex, dob = :dob, 
                           address = :address, contact_no = :contact_no, status = :status 
                       WHERE student_id = :student_id";
        $stmt = $pdo->prepare($update_sql);
    } else {
        // Insert a new record if no existing enrollment found
        $insert_sql = "INSERT INTO enrollment (student_id, lastname, firstname, middlename, suffix, school_year, 
                        semester, sex, dob, address, contact_no, status) 
                       VALUES (:student_id, :lastname, :firstname, :middlename, :suffix, :school_year, :semester, :sex, :dob, :address, :contact_no, :status)";
        $stmt = $pdo->prepare($insert_sql);
    }

    // Bind parameters for both insert and update
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':middlename', $middlename);
    $stmt->bindParam(':suffix', $suffix);
    $stmt->bindParam(':school_year', $school_year);
    $stmt->bindParam(':semester', $semester);
    $stmt->bindParam(':sex', $sex);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':contact_no', $contact_no);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        echo "Data successfully saved.";
        header("Location: enrollment_form.php");  // Redirect to a success page after saving
        exit();
    } else {
        echo "Error saving data. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="bg-white p-6 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800 mt-10">Student Registration Form</h2>
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Last Name -->
                <div class="mb-4">
                    <label for="lastname" class="block text-gray-700 mb-2">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                    value="<?php echo isset($enrollment_data['lastname']) ? htmlspecialchars($enrollment_data['lastname']) : ''; ?>" required>
                </div>

                <!-- First Name -->
                <div class="mb-4">
                    <label for="firstname" class="block text-gray-700 mb-2">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                    value="<?php echo isset($enrollment_data['firstname']) ? htmlspecialchars($enrollment_data['firstname']) : ''; ?>" required>
                </div>

                <!-- Middle Name -->
                <div class="mb-4">
                    <label for="middlename" class="block text-gray-700 mb-2">Middle Name</label>
                    <input type="text" id="middlename" name="middlename" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                    value="<?php echo isset($enrollment_data['middlename']) ? htmlspecialchars($enrollment_data['middlename']) : ''; ?>">
                </div>

                <!-- Suffix -->
                <div class="mb-4">
                    <label for="suffix" class="block text-gray-700 mb-2">Suffix (Optional)</label>
                    <select id="suffix" name="suffix" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">--Select--</option>
                        <option value="Jr" <?php echo isset($enrollment_data['suffix']) && $enrollment_data['suffix'] == 'Jr' ? 'selected' : ''; ?>>Jr. (Junior)</option>
                        <option value="Sr" <?php echo isset($enrollment_data['suffix']) && $enrollment_data['suffix'] == 'Sr' ? 'selected' : ''; ?>>Sr. (Senior)</option>
                        <option value="II" <?php echo isset($enrollment_data['suffix']) && $enrollment_data['suffix'] == 'II' ? 'selected' : ''; ?>>II</option>
                        <option value="III" <?php echo isset($enrollment_data['suffix']) && $enrollment_data['suffix'] == 'III' ? 'selected' : ''; ?>>III</option>
                        <option value="IV" <?php echo isset($enrollment_data['suffix']) && $enrollment_data['suffix'] == 'IV' ? 'selected' : ''; ?>>IV</option>
                        <option value="Esq" <?php echo isset($enrollment_data['suffix']) && $enrollment_data['suffix'] == 'Esq' ? 'selected' : ''; ?>>Esq</option>
                    </select>
                </div>

<!-- School Year -->
<div class="mb-4">
    <label for="school_year" class="block text-gray-700 mb-2">School Year</label>
    <select id="school_year" name="school_year" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        <option value="">--Select School Year--</option>
        <?php foreach ($school_years as $year) : ?>
            <option value="<?php echo htmlspecialchars($year['year']); ?>" 
                <?php echo isset($enrollment_data['school_year']) && $enrollment_data['school_year'] == $year['year'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($year['year']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Semester -->
<div class="mb-4">
    <label for="semester" class="block text-gray-700 mb-2">Semester</label>
    <select id="semester" name="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        <option value="">--Select Semester--</option>
        <?php foreach ($semesters as $semester) : ?>
            <option value="<?php echo htmlspecialchars($semester['semester']); ?>" 
                <?php echo isset($enrollment_data['semester']) && $enrollment_data['semester'] == $semester['semester'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($semester['semester']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Sex -->
<div class="mb-4">
    <label for="sex" class="block text-gray-700 mb-2">Sex</label>
    <select id="sex" name="sex" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        <option value="">--Select Sex--</option>
        <?php foreach ($sex_options as $sex) : ?>
            <option value="<?php echo htmlspecialchars($sex['sex_name']); ?>" 
                <?php echo isset($enrollment_data['sex']) && $enrollment_data['sex'] == $sex['sex_name'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($sex['sex_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Status -->
<div class="mb-4">
    <label for="status" class="block text-gray-700 mb-2">Status</label>
    <select id="status" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
        <option value="">--Select Status--</option>
        <?php foreach ($status_options as $status) : ?>
            <option value="<?php echo htmlspecialchars($status['status_name']); ?>" 
                <?php echo isset($enrollment_data['status']) && $enrollment_data['status'] == $status['status_name'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($status['status_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


                <!-- Date of Birth -->
                <div class="mb-4">
                    <label for="dob" class="block text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" id="dob" name="dob" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                    value="<?php echo isset($enrollment_data['dob']) ? htmlspecialchars($enrollment_data['dob']) : ''; ?>" required>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="block text-gray-700 mb-2">Address</label>
                    <input type="text" id="address" name="address" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                    value="<?php echo isset($enrollment_data['address']) ? htmlspecialchars($enrollment_data['address']) : ''; ?>" required>
                </div>

                <!-- Contact Number -->
                <div class="mb-4">
                    <label for="contact_no" class="block text-gray-700 mb-2">Contact Number</label>
                    <input type="text" id="contact_no" name="contact_no" class="w-full border border-gray-300 rounded-lg px-3 py-2" 
                    value="<?php echo isset($enrollment_data['contact_no']) ? htmlspecialchars($enrollment_data['contact_no']) : ''; ?>" required>
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Save</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
