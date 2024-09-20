<?php
require 'Enrollment.php'; // Adjust the path as needed
$enrollment = new Enrollment();
$school_years = $enrollment->getSchoolYears();
$status_options = $enrollment->getStatusOptions(); // Fetch status options
$sex_options = $enrollment->getSexOptions(); // Fetch sex option
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Enrollment Form</title>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-3xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Enrollment Form</h2>
        <form action="enrollment_handler.php" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="lastname" id="lastname" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="firstname" id="firstname" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="middlename" class="block text-sm font-medium text-gray-700">Middle Name</label>
                <input type="text" name="middlename" id="middlename" class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200" placeholder="example@example.com">
            </div>

            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="dob" id="dob" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address" id="address" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact No</label>
                <input type="tel" name="contact_no" id="contact_no" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                <select name="sex" id="sex" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    <option value="" disabled selected>Select your sex</option>
                    <?php foreach ($sex_options as $sex): ?>
                        <option value="<?= $sex['sex_name'] ?>"><?= $sex['sex_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix</label>
                <input type="text" name="suffix" id="suffix" class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="school_year" class="block text-sm font-medium text-gray-700">School Year</label>
                <select name="school_year" id="school_year" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    <option value="" disabled selected>Select school year</option>
                    <?php foreach ($school_years as $year): ?>
                        <option value="<?= $year['year'] ?>"><?= $year['year'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                    <option value="" disabled selected>Select status</option>
                    <?php foreach ($status_options as $status): ?>
                        <option value="<?= $status['status_name'] ?>"><?= $status['status_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-span-2">
                <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-md shadow hover:bg-indigo-700 transition duration-200">Enroll</button>
            </div>
        </form>
    </div>
</body>
</html>
