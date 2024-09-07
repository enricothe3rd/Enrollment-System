<?php
session_start();
require '../../db/db_connection.php';

// Check if user is not logged in or if their role is not 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle form submission for editing a user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $role = strtolower($_POST['role']); // Convert role to lowercase
    $status = strtolower($_POST['status']); // Convert status to lowercase
    $account_locked = isset($_POST['account_locked']) ? 1 : 0;
    $lock_time = $account_locked ? date('Y-m-d H:i:s') : null;

    $updateQuery = "UPDATE users SET role = ?, status = ?, account_locked = ?, lock_time = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute([$role, $status, $account_locked, $lock_time, $id]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all users
$query = "SELECT id, email, role, status, email_confirmed, failed_attempts, account_locked, lock_time FROM users";
$stmt = $conn->query($query);
$users = $stmt->fetchAll();

// Fetch specific user if editing
$editingUser = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $editingUser = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            position: relative;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            background: #fff;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Manage Users</h1>

        <!-- User Table -->
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border p-3 text-left">Email</th>
                        <th class="border p-3 text-left">Role</th>
                        <th class="border p-3 text-left">Status</th>
                        <th class="border p-3 text-left">Email Confirmed</th>
                        <th class="border p-3 text-left">Failed Attempts</th>
                        <th class="border p-3 text-left">Account Locked</th>
                        <th class="border p-3 text-left">Lock Time</th>
                        <th class="border p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border p-3"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="border p-3"><?= htmlspecialchars(ucfirst($user['role'])) ?></td>
                            <td class="border p-3"><?= htmlspecialchars(ucfirst($user['status'])) ?></td>
                            <td class="border p-3"><?= $user['email_confirmed'] ? 'Yes' : 'No' ?></td>
                            <td class="border p-3"><?= htmlspecialchars($user['failed_attempts']) ?></td>
                            <td class="border p-3"><?= $user['account_locked'] ? 'Yes' : 'No' ?></td>
                            <td class="border p-3"><?= htmlspecialchars($user['lock_time']) ?></td>
                            <td class="border p-3">
                                <button onclick="openModal(<?= htmlspecialchars(json_encode($user)) ?>)" class="text-blue-600 hover:underline">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Edit Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span id="closeModal" class="absolute top-2 right-2 cursor-pointer text-gray-500">&times;</span>
                <h2 class="text-xl font-bold mb-4">Edit User</h2>
                <form id="editForm" method="POST">
                    <input type="hidden" name="id" id="userId">
                    
                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 font-semibold">Role</label>
                        <select id="role" name="role" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="student">Student</option>
                            <option value="cashier">Cashier</option>
                            <option value="college department">College Department</option>
                            <option value="registrar">Registrar</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 font-semibold">Status</label>
                        <select id="status" name="status" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="account_locked" class="block text-gray-700 font-semibold">Account Locked</label>
                        <input type="checkbox" id="account_locked" name="account_locked" class="mr-2">
                        <span>Yes</span>
                    </div>
                    
                    <div class="flex items-center">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Save Changes</button>
                        <button type="button" id="cancelButton" class="ml-4 text-red-500 hover:underline">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Open Modal
        function openModal(user) {
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('userId').value = user.id;
            document.getElementById('role').value = user.role.toLowerCase(); // Convert role to lowercase
            document.getElementById('status').value = user.status.toLowerCase(); // Convert status to lowercase
            document.getElementById('account_locked').checked = user.account_locked;
        }

        // Close Modal
        document.getElementById('closeModal').onclick = function() {
            document.getElementById('editModal').style.display = 'none';
        }

        document.getElementById('cancelButton').onclick = function() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>
