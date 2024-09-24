<?php
require '../../db/db_connection3.php'; // Ensure you have your DB connection

// Get the PDO instance from your Database class
$pdo = Database::connect();

// Create the subject_ojt_fees table if it doesn't exist
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS subject_ojt_fees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject_id INT NOT NULL,
        ojt_fee DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (subject_id) REFERENCES subjects(id)
    )");
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}

// Fetch subjects and their corresponding section names from the database
$subjects = [];
try {
    $stmt = $pdo->query("
        SELECT s.id AS subject_id, s.title AS subject_title, sec.name AS section_name 
        FROM subjects s
        JOIN sections sec ON s.section_id = sec.id
    ");
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching subjects: " . $e->getMessage();
}

// Handle form submission for adding/updating OJT fee
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject_id'];
    $ojt_fee = $_POST['ojt_fee'];

    // Check if updating an existing fee
    if (!empty($_POST['ojt_fee_id'])) {
        $ojt_fee_id = $_POST['ojt_fee_id'];
        try {
            $stmt = $pdo->prepare("UPDATE subject_ojt_fees SET subject_id = :subject_id, ojt_fee = :ojt_fee WHERE id = :id");
            $stmt->execute(['subject_id' => $subject_id, 'ojt_fee' => $ojt_fee, 'id' => $ojt_fee_id]);
            // Redirect after successful update
            header("Location: ojt_fees.php");
            exit;
        } catch (PDOException $e) {
            echo "<p class='text-red-500'>Error updating OJT fee: " . $e->getMessage() . "</p>";
        }
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO subject_ojt_fees (subject_id, ojt_fee) VALUES (:subject_id, :ojt_fee)");
            $stmt->execute(['subject_id' => $subject_id, 'ojt_fee' => $ojt_fee]);
            // Redirect after successful insertion
            header("Location: ojt_fees.php");
            exit;
        } catch (PDOException $e) {
            echo "<p class='text-red-500'>Error inserting OJT fee: " . $e->getMessage() . "</p>";
        }
    }
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM subject_ojt_fees WHERE id = :id");
        $stmt->execute(['id' => $delete_id]);
        // Redirect after successful deletion
        header("Location: ojt_fees.php");
        exit;
    } catch (PDOException $e) {
        echo "<p class='text-red-500'>Error deleting OJT fee: " . $e->getMessage() . "</p>";
    }
}

// Fetch existing OJT fees with subject titles
$ojt_fees = [];
try {
    $stmt = $pdo->query("
        SELECT sof.id AS fee_id, s.title AS subject_title, sof.ojt_fee 
        FROM subject_ojt_fees sof
        JOIN subjects s ON sof.subject_id = s.id
    ");
    $ojt_fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching OJT fees: " . $e->getMessage();
}

// Populate data for editing
$selected_fee = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM subject_ojt_fees WHERE id = :id");
        $stmt->execute(['id' => $edit_id]);
        $selected_fee = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching OJT fee for edit: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OJT Fee Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-4 rounded shadow">
        <h1 class="text-2xl font-bold mb-4"><?= $selected_fee ? 'Edit OJT Fee' : 'Enter OJT Fee' ?></h1>
        <form method="POST" action="">
            <input type="hidden" name="ojt_fee_id" value="<?= $selected_fee ? $selected_fee['id'] : '' ?>">
            <label for="subject" class="block mb-2">Select Subject:</label>
            <select id="subject" name="subject_id" class="block w-full mb-4 border rounded p-2" required>
                <option value="">--Select a Subject--</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= htmlspecialchars($subject['subject_id']); ?>" <?= $selected_fee && $selected_fee['subject_id'] == $subject['subject_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($subject['subject_title']) . ' (Section: ' . htmlspecialchars($subject['section_name']) . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="ojt_fee" class="block mb-2">OJT Fee:</label>
            <input type="number" id="ojt_fee" name="ojt_fee" step="0.01" value="<?= $selected_fee ? htmlspecialchars($selected_fee['ojt_fee']) : '' ?>" class="block w-full mb-4 border rounded p-2" required>

            <button type="submit" class="bg-blue-500 text-white rounded p-2"><?= $selected_fee ? 'Update' : 'Submit' ?></button>
        </form>

        <h2 class="text-xl font-bold mt-6 mb-4">Existing OJT Fees</h2>
        <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Subject Title</th>
                    <th class="border px-4 py-2">OJT Fee</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ojt_fees as $fee): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($fee['fee_id']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($fee['subject_title']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($fee['ojt_fee']) ?></td>
                        <td class="border px-4 py-2">
                            <a href="?edit_id=<?= htmlspecialchars($fee['fee_id']) ?>" class="text-blue-500 hover:underline">Edit</a>
                            <a href="?delete_id=<?= htmlspecialchars($fee['fee_id']) ?>" class="text-red-500 hover:underline ml-4" onclick="return confirm('Are you sure you want to delete this OJT fee?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
