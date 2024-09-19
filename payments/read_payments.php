<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> <!-- Updated CDN URL -->

    <script>
        function toggleFields() {
            const paymentType = document.querySelector('select[name="payment_type"]').value;
            document.getElementById('installment_fields').style.display = paymentType === 'installment' ? 'block' : 'none';
            document.getElementById('bank_number').style.display = paymentType === 'installment' ? 'block' : 'none';
        }

        function toggleInstallmentType() {
            const installmentType = document.querySelector('select[name="installment_type"]').value;
            document.getElementById('monthly_fields').style.display = installmentType === 'monthly' ? 'block' : 'none';
            document.getElementById('quarterly_fields').style.display = installmentType === 'quarterly' ? 'block' : 'none';
            document.getElementById('flexible_fields').style.display = installmentType === 'flexible' ? 'block' : 'none';
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Create Payment</h2>
        <form action="process_payment.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-2">Student ID:</label>
                <input type="text" name="student_id" required class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Subject ID:</label>
                <input type="text" name="subject_id" required class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Subject Price:</label>
                <input type="text" name="subject_price" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Payment Type:</label>
                <select name="payment_type" onchange="toggleFields()" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="cash">Cash</option>
                    <option value="installment">Installment</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Amount:</label>
                <input type="text" name="amount_paid" required class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Miscellaneous Fee:</label>
                <input type="text" name="miscellaneous_fee" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Research Fee:</label>
                <input type="text" name="research_fee" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Transfer Fee:</label>
                <input type="text" name="transfer_fee" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Overload Subjects Fee:</label>
                <input type="text" name="overload_fee" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div id="installment_fields" style="display: none;">
                <div>
                    <label class="block text-gray-700 mb-2">Installment Type:</label>
                    <select name="installment_type" onchange="toggleInstallmentType()" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="flexible">Flexible</option>
                    </select>
                </div>

                <div id="monthly_fields" style="display: none;">
                    <label class="block text-gray-700 mb-2">Monthly Installment Amount:</label>
                    <input type="text" name="monthly_amount" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div id="quarterly_fields" style="display: none;">
                    <label class="block text-gray-700 mb-2">Quarterly Installment Amount:</label>
                    <input type="text" name="quarterly_amount" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div id="flexible_fields" style="display: none;">
                    <label class="block text-gray-700 mb-2">Flexible Payment Details:</label>
                    <input type="text" name="flexible_details" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div id="bank_number" style="display: none;">
                <label class="block text-gray-700 mb-2">Bank Number:</label>
                <input type="text" name="bank_number" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Payment Status:</label>
                <select name="payment_status" class="border border-gray-300 p-3 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="paid">Paid</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <div>
                <input type="submit" value="Submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            </div>
        </form>
    </div>
</body>
</html>
