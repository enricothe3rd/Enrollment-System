<?php
session_start();
$user_email = $_SESSION['user_email'] ?? '';
$student_number = $_SESSION['student_number'] ?? null;

if (empty($user_email) || empty($student_number)) {
    echo "User email or student number is not set in the session.";
    exit;
}

// Database connection
require_once '../db/db_connection3.php';

try {
    $db = Database::connect();

    // Get the number of subjects for the student
    $stmt = $db->prepare("SELECT COUNT(*) AS subject_count FROM subject_enrollments WHERE student_number = :student_number");
    $stmt->bindParam(':student_number', $student_number);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $subject_count = $result['subject_count'] ?? 0;

    // Prepare and execute the query to get subject IDs for the student
    $stmt = $db->prepare("SELECT subject_id FROM subject_enrollments WHERE student_number = :student_number");
    $stmt->bindParam(':student_number', $student_number);
    $stmt->execute();

    // Fetch all subject IDs
    $subject_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $total_units = 0;

    // If there are subjects, fetch their unit counts
    if (!empty($subject_ids)) {
        // Prepare to get the number of units for each subject
        $placeholders = implode(',', array_fill(0, count($subject_ids), '?'));
        $unitStmt = $db->prepare("SELECT SUM(units) AS total_units FROM subjects WHERE id IN ($placeholders)");
        $unitStmt->execute($subject_ids);

        // Fetch the total units
        $unitResult = $unitStmt->fetch(PDO::FETCH_ASSOC);
        $total_units = $unitResult['total_units'] ?? 0;
    }

    // Output the results
    // echo "Number of subjects for student number $student_number: $subject_count<br>";
    // echo "Total units for student number $student_number: $total_units";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



// CREATE TABLE enrollment_payment_summaries (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     student_number VARCHAR(255) NOT NULL,
//     subject_count INT NOT NULL,
//     total_units INT NOT NULL,
//     enrollment_fee DECIMAL(10, 2) NOT NULL,
//     miscellaneous_fee DECIMAL(10, 2) NOT NULL,
//     research_fee DECIMAL(10, 2) NOT NULL,
//     overload_fee DECIMAL(10, 2) NOT NULL,
//     total_amount DECIMAL(10, 2) NOT NULL,
//     payment_method ENUM('cash', 'installment') NOT NULL,
//     interest_rate DECIMAL(5, 2) DEFAULT 0,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
//     FOREIGN KEY (student_number) REFERENCES users(student_number) ON DELETE CASCADE
// );


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function toggleInstallmentFields() {
            const paymentMethod = document.querySelector('select[name="payment_method"]').value;
            const installmentFields = document.getElementById('installmentFields');
            const installmentSuggestion = document.getElementById('installmentSuggestion');
            const interestRateField = document.getElementById('interestRateField');

            if (paymentMethod === 'installment') {
                installmentFields.style.display = 'block';
                installmentSuggestion.style.display = 'block';
                interestRateField.style.display = 'block'; // Show interest rate input
                calculateTotal(); 
            } else {
                installmentFields.style.display = 'none';
                installmentSuggestion.style.display = 'none';
                interestRateField.style.display = 'none'; // Hide interest rate input
                calculateTotal(); 
            }
        }

        function calculateTotal() {
            const enrollmentFee = parseFloat(document.querySelector('input[name="enrollment_fee"]').value) || 0;
            const miscellaneousFee = parseFloat(document.querySelector('input[name="miscellaneous_fee"]').value) || 0;
            const researchFee = parseFloat(document.querySelector('input[name="research_fee"]').value) || 0;
            const overloadFee = parseFloat(document.querySelector('input[name="overload_fee"]').value) || 0;
            const numSubjects = parseInt(document.querySelector('input[name="number_of_subjects"]').value) || 0;
            const numUnits = parseInt(document.querySelector('input[name="number_of_units"]').value) || 0;
            const amountPerUnit = parseFloat(document.querySelector('input[name="amount_per_unit"]').value) || 0;
            const interestRate = parseFloat(document.querySelector('input[name="interest_rate"]').value) || 0;

            const totalFees = enrollmentFee + miscellaneousFee + researchFee + overloadFee;
            const totalUnitsFee = numSubjects * numUnits * amountPerUnit;
            const totalAmount = totalFees + totalUnitsFee;
            const totalAmountWithInterest = totalAmount * (1 + interestRate / 100); // Ensure interest rate is applied correctly

            document.getElementById('totalAmount').innerText = `Total Amount (Cash): ₱${totalAmount.toFixed(2)}`;
            document.getElementById('totalAmountWithInterest').innerText = `Total Amount (with Interest): ₱${totalAmountWithInterest.toFixed(2)}`;

            if (document.querySelector('select[name="payment_method"]').value === 'installment') {
                calculateInstallmentPayment(totalAmountWithInterest);
            }
        }

        function calculateInstallmentPayment(totalAmount) {
            const frequency = document.querySelector('select[name="installment_frequency"]').value;
            let installmentCount = 1;
            let breakdown = '';

            if (frequency === 'monthly') {
                installmentCount = 12;
            } else if (frequency === 'quarterly') {
                installmentCount = 4;
            } else if (frequency === 'yearly') {
                installmentCount = 1;
            }

            const paymentPerInstallment = (totalAmount / installmentCount).toFixed(2);
            document.getElementById('suggestionText').innerText = `Suggested ${frequency} payment: ₱${paymentPerInstallment}`;

            // Generate breakdown table
            for (let i = 1; i <= installmentCount; i++) {
                breakdown += `<tr>
                    <td>${i}${frequency === 'monthly' ? ' Month' : frequency === 'quarterly' ? ' Quarter' : ''}</td>
                    <td>₱${paymentPerInstallment}</td>
                    <td>₱${(paymentPerInstallment * i).toFixed(2)}</td>
                </tr>`;
            }
            document.getElementById('breakdownTableBody').innerHTML = breakdown;
            document.getElementById('breakdownTable').style.display = 'table'; // Show the breakdown table
        }
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-4xl w-full bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4 text-center">Payment Form</h2>
        
        <form method="POST" action="process_payment.php">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID:</label>
                    <input type="text" name="student_id" value="<?php echo htmlspecialchars($student_number); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" readonly>
                </div>

                <div>
                    <label for="number_of_subjects" class="block text-sm font-medium text-gray-700">Number of Subjects:</label>
                    <input type="number" name="number_of_subjects" value="<?php echo htmlspecialchars($subject_count); ?>" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" readonly>
                </div>


                <div>
                    <label for="number_of_units" class="block text-sm font-medium text-gray-700">Number of Units per Subject:</label>
                    <input type="number" name="number_of_units" value="<?php echo htmlspecialchars($total_units); ?>" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="amount_per_unit" class="block text-sm font-medium text-gray-700">Amount per Unit:</label>
                    <input type="number" name="amount_per_unit"  required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="enrollment_fee" class="block text-sm font-medium text-gray-700">Enrollment Fee:</label>
                    <input type="number" name="enrollment_fee" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="miscellaneous_fee" class="block text-sm font-medium text-gray-700">Miscellaneous Fee:</label>
                    <input type="number" name="miscellaneous_fee" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="research_fee" class="block text-sm font-medium text-gray-700">Research Fee:</label>
                    <input type="number" name="research_fee" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="overload_fee" class="block text-sm font-medium text-gray-700">Overload Fee:</label>
                    <input type="number" name="overload_fee" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method:</label>
                    <select name="payment_method" required onchange="toggleInstallmentFields()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                        <option value="cash">Cash</option>
                        <option value="installment">Installment</option>
                    </select>
                </div>
            </div>

            <div id="interestRateField" style="display: none;">
                <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%):</label>
                <input type="number" name="interest_rate" oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" step="0.01">
            </div>

            <div id="installmentFields" class="mt-4 hidden">
                <label for="installment_frequency" class="block text-sm font-medium text-gray-700">Installment Frequency:</label>
                <select name="installment_frequency" onchange="calculateInstallmentPayment(parseFloat(document.getElementById('totalAmountWithInterest').innerText.split('₱')[1]))" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>

            <div id="installmentSuggestion" style="display: none;">
                <p id="suggestionText" class="text-lg font-bold mt-4"></p>
            </div>

            <div class="mt-4">
                <p id="totalAmount" class="text-lg font-bold"></p>
                <p id="totalAmountWithInterest" class="text-lg font-bold"></p>
            </div>

            <table id="breakdownTable" style="display: none;" class="mt-4 w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Installment</th>
                        <th class="border border-gray-300 px-4 py-2">Amount</th>
                        <th class="border border-gray-300 px-4 py-2">Cumulative</th>
                    </tr>
                </thead>
                <tbody id="breakdownTableBody"></tbody>
            </table>

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Submit Payment</button>
            </div>
        </form>
    </div>
</body>
</html>
