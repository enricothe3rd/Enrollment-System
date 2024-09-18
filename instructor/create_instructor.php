<?php
require '../db/db_connection3.php'; // Include your Database class file

// Initialize selected values
$selected_section = $_POST['section'] ?? '';

// Get the PDO instance
$pdo = Database::connect();

try {
    // Fetch subjects if a section is selected
    $subjects = [];
    if ($selected_section) {
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE section_id = ?");
        $stmt->execute([$selected_section]);
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debugging: Output the subjects array
        echo '<pre>';
        print_r($subjects);
        echo '</pre>';
    }
} catch (PDOException $e) {
    echo 'SQL Error: ' . htmlspecialchars($e->getMessage());
}
?>
<form method="post">
    <label for="section">Section:</label>
    <select id="section" name="section" onchange="this.form.submit()">
        <option value="">Select Section</option>
        <?php foreach ($sections as $section): ?>
            <option value="<?php echo htmlspecialchars($section['id']); ?>" <?php echo $selected_section == $section['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($section['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
