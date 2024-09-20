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

            if (paymentMethod === 'installment') {
                installmentFields.style.display = 'block';
                installmentSuggestion.style.display = 'block';
                calculateTotal(); 
            } else {
                installmentFields.style.display = 'none';
                installmentSuggestion.style.display = 'none';
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
            const interestRate = parseFloat(document.querySelector('input[name="interest_rate"]').value) / 100 || 0;

            const totalFees = enrollmentFee + miscellaneousFee + researchFee + overloadFee;
            const totalUnitsFee = numSubjects * numUnits * amountPerUnit;
            const totalAmount = totalFees + totalUnitsFee;
            const totalAmountWithInterest = totalAmount * (1 + interestRate);

            document.getElementById('totalAmount').innerText = `Total Amount (Cash): ₱${totalAmount.toFixed(2)}`;
            document.getElementById('totalAmountWithInterest').innerText = `Total Amount (with Interest): ₱${totalAmountWithInterest.toFixed(2)}`;

            if (document.querySelector('select[name="payment_method"]').value === 'installment') {
                calculateInstallmentPayment(totalAmountWithInterest);
            }
        }

        function calculateInstallmentPayment(totalAmount) {
            const frequency = document.querySelector('select[name="installment_frequency"]').value;

            let installmentCount = 1;
            if (frequency === 'monthly') {
                installmentCount = 12;
            } else if (frequency === 'quarterly') {
                installmentCount = 4;
            } else if (frequency === 'yearly') {
                installmentCount = 1;
            }

            const paymentPerInstallment = (totalAmount / installmentCount).toFixed(2);
            document.getElementById('suggestionText').innerText = `Suggested ${frequency} payment: ₱${paymentPerInstallment}`;
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
                    <input type="text" name="student_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="number_of_subjects" class="block text-sm font-medium text-gray-700">Number of Subjects:</label>
                    <input type="number" name="number_of_subjects" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="number_of_units" class="block text-sm font-medium text-gray-700">Number of Units per Subject:</label>
                    <input type="number" name="number_of_units" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                </div>

                <div>
                    <label for="amount_per_unit" class="block text-sm font-medium text-gray-700">Amount per Unit:</label>
                    <input type="number" name="amount_per_unit" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
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
                    <label for="interest_rate" class="block text-sm font-medium text-gray-700">Interest Rate (%):</label>
                    <input type="number" name="interest_rate" required oninput="calculateTotal()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" step="0.01">
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method:</label>
                    <select name="payment_method" required onchange="toggleInstallmentFields()" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                        <option value="cash">Cash</option>
                        <option value="installment">Installment</option>
                    </select>
                </div>
            </div>

            <div id="installmentFields" class="mt-4 hidden">
                <label for="installment_frequency" class="block text-sm font-medium text-gray-700 mt-4">Installment Frequency:</label>
                <select name="installment_frequency" class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500" onchange="calculateInstallmentPayment()">
                    <option value="monthly">Monthly</option>
                    <option value="quarterly">Quarterly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>

            <div id="installmentSuggestion" class="mt-4 hidden">
                <p id="totalAmount" class="text-lg font-bold">Total Amount (Cash): ₱0.00</p>
                <p id="totalAmountWithInterest" class="text-lg font-bold">Total Amount (with Interest): ₱0.00</p>
                <p id="suggestionText" class="text-lg text-green-500"></p>
            </div>

            <div class="mt-6 text-center">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-600">Submit Payment</button>
            </div>
        </form>
    </div>
</body>
</html>
