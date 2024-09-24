<?php
// db_research_fee.php

require '../../db/db_connection3.php'; // Ensure you have your DB connection

// Get the PDO instance from your Database class
$pdo = Database::connect();

// Create the subject_research_fees table if it doesn't exist
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS subject_research_fees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subject_id INT NOT NULL,
        research_fee DECIMAL(10, 2) NOT NULL,
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

// Handle form submissions for creating, updating, and deleting
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject_id'];
    $research_fee = $_POST['research_fee'];
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO subject_research_fees (subject_id, research_fee) VALUES (:subject_id, :research_fee)");
            $stmt->execute(['subject_id' => $subject_id, 'research_fee' => $research_fee]);
            header("Location: research_fees.php?success=create");
            exit();
        } elseif ($action === 'update') {
            // Update existing record
            $fee_id = $_POST['fee_id'];
            $stmt = $pdo->prepare("UPDATE subject_research_fees SET subject_id = :subject_id, research_fee = :research_fee WHERE id = :fee_id");
            $stmt->execute(['subject_id' => $subject_id, 'research_fee' => $research_fee, 'fee_id' => $fee_id]);
            header("Location: research_fees.php?success=update");
            exit();
        } elseif ($action === 'delete') {
            // Delete record
            $fee_id = $_POST['fee_id'];
            $stmt = $pdo->prepare("DELETE FROM subject_research_fees WHERE id = :fee_id");
            $stmt->execute(['fee_id' => $fee_id]);
            header("Location: research_fees.php?success=delete");
            exit();
        }
    } catch (PDOException $e) {
        echo "<p class='text-red-500'>Error: " . $e->getMessage() . "</p>";
    }
}

// Fetch existing research fees for displaying in a table
$research_fees = [];
try {
    $stmt = $pdo->query("
        SELECT rf.id, rf.research_fee, rf.subject_id, s.title AS subject_title, sec.name AS section_name 
        FROM subject_research_fees rf
        JOIN subjects s ON rf.subject_id = s.id
        JOIN sections sec ON s.section_id = sec.id
    ");
    $research_fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching research fees: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Fee Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-4 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Manage Research Fees</h1>
        
        <!-- Form for creating/updating research fee -->
        <form method="POST" action="">
            <input type="hidden" name="fee_id" id="fee_id" value="">
            <input type="hidden" name="action" id="form_action" value="create">

            <label for="subject" class="block mb-2">Select Subject:</label>
            <select id="subject" name="subject_id" class="block w-full mb-4 border rounded p-2" required>
                <option value="">--Select a Subject--</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= htmlspecialchars($subject['subject_id']); ?>">
                        <?= htmlspecialchars($subject['subject_title']) . ' (Section: ' . htmlspecialchars($subject['section_name']) . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="research_fee" class="block mb-2">Research Fee:</label>
            <input type="number" id="research_fee" name="research_fee" step="0.01" class="block w-full mb-4 border rounded p-2" required>

            <button type="submit" class="bg-blue-500 text-white rounded p-2">Submit</button>
        </form>

        <!-- Table of existing research fees -->
        <h2 class="text-xl font-bold mt-6 mb-4">Existing Research Fees</h2>
        <table class="min-w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Subject</th>
                    <th class="border px-4 py-2">Section</th>
                    <th class="border px-4 py-2">Research Fee</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($research_fees as $fee): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($fee['subject_title']); ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($fee['section_name']); ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($fee['research_fee']); ?></td>
                        <td class="border px-4 py-2">
                            <form method="POST" action="" class="inline">
                                <input type="hidden" name="fee_id" value="<?= htmlspecialchars($fee['id']); ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="text-red-500">Delete</button>
                            </form>
                            <button type="button" class="text-blue-500" onclick="editFee(<?= htmlspecialchars($fee['id']); ?>, <?= htmlspecialchars($fee['subject_id']); ?>, '<?= htmlspecialchars($fee['research_fee']); ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editFee(id, subjectId, fee) {
            document.getElementById('fee_id').value = id;
            document.getElementById('subject').value = subjectId; // Use subjectId for the select value
            document.getElementById('research_fee').value = fee;
            document.getElementById('form_action').value = 'update';
        }
    </script>
</body>
</html>
