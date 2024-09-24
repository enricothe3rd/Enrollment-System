<?php
require '../../db/db_connection3.php'; // Ensure to include your database connection file

// Get the PDO instance from your Database class
$pdo = Database::connect();

// Initialize variables to hold input values
$units_price = '';
$miscellaneous_fee = '';
$months_of_payments = '';

// Handle form submission for insert/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $units_price = $_POST['units_price'];
    $miscellaneous_fee = $_POST['miscellaneous_fee'];
    $months_of_payments = $_POST['months_of_payments'] ?? null; // Optional

    // Check if a row already exists
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollment_payments");
        $stmt->execute();
        $rowCount = $stmt->fetchColumn();

        if ($rowCount > 0) {
            // Update the existing row
            $sql = "UPDATE enrollment_payments SET units_price = :units_price, miscellaneous_fee = :miscellaneous_fee, months_of_payments = :months_of_payments";
            $stmt = $pdo->prepare($sql);
        } else {
            // Insert new row
            $sql = "INSERT INTO enrollment_payments (units_price, miscellaneous_fee, months_of_payments) VALUES (:units_price, :miscellaneous_fee, :months_of_payments)";
            $stmt = $pdo->prepare($sql);
        }

        // Bind parameters and execute
        $stmt->bindParam(':units_price', $units_price);
        $stmt->bindParam(':miscellaneous_fee', $miscellaneous_fee);
        $stmt->bindParam(':months_of_payments', $months_of_payments);
        $stmt->execute();

        // Redirect to the same page (or another page) to prevent re-submission
        header('Location: enrollment_payments.php');
        exit(); // Ensure no further code is executed
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM enrollment_payments");
        $stmt->execute();
        header('Location: enrollment_payments.php');
        exit();
    } catch (PDOException $e) {
        echo "Error deleting data: " . $e->getMessage();
    }
}

// Fetch existing data to prepopulate the form (optional)
try {
    $stmt = $pdo->prepare("SELECT * FROM enrollment_payments LIMIT 1"); // Only fetch one row
    $stmt->execute();
    $existing_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existing_data) {
        $units_price = $existing_data['units_price'];
        $miscellaneous_fee = $existing_data['miscellaneous_fee'];
        $months_of_payments = $existing_data['months_of_payments'];
    }
} catch (PDOException $e) {
    echo "Error fetching existing data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Units Price Form</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <form action="enrollment_payments.php" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md space-y-4">
        <!-- Units Price -->
        <div>
            <label for="units_price" class="block text-sm font-medium text-gray-700">Units Price</label>
            <input type="number" step="0.01" name="units_price" id="units_price" required
                value="<?php echo htmlspecialchars($units_price); ?>"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Miscellaneous Fee -->
        <div>
            <label for="miscellaneous_fee" class="block text-sm font-medium text-gray-700">Miscellaneous Fee</label>
            <input type="number" step="0.01" name="miscellaneous_fee" id="miscellaneous_fee" required
                value="<?php echo htmlspecialchars($miscellaneous_fee); ?>"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <!-- Months of Payments -->
        <div>
            <label for="months_of_payments" class="block text-sm font-medium text-gray-700">Months of Payments</label>
            <select name="months_of_payments" id="months_of_payments" required
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Select Months --</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($i == $months_of_payments) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </div>
    </form>

    <!-- Table for existing enrollment payments -->
    <div class="max-w-lg mx-auto mt-6 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-medium text-gray-700">Existing Enrollment Payments</h2>
        <table class="min-w-full mt-4 border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Units Price</th>
                    <th class="border px-4 py-2">Miscellaneous Fee</th>
                    <th class="border px-4 py-2">Months of Payments</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($existing_data): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($existing_data['units_price']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($existing_data['miscellaneous_fee']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($existing_data['months_of_payments']); ?></td>
                        <td class="border px-4 py-2">
                            <a href="?delete=1" onclick="return confirm('Are you sure you want to delete this entry?');"
                               class="text-red-600 hover:text-red-800">Delete</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-center">No entries found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
