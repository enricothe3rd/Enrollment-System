<?php
// Include the database connection file
require '../../db/db_connection1.php';

// Set number of records per page
$records_per_page = 10;

// Helper function to generate query params for pagination links
function paginationUrl($params) {
    $query = http_build_query(array_merge($_GET, $params));
    return '?' . $query;
}

// Pagination and data fetching for Users
$page_users = isset($_GET['page_users']) ? (int)$_GET['page_users'] : 1;
$start_users = ($page_users - 1) * $records_per_page;

// Get total number of users
$stmt_users = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt_users->fetchColumn();
$total_pages_users = ceil($total_users / $records_per_page);

// Fetch users with pagination
$stmt_users = $pdo->prepare("
    SELECT id, email, password, role, status, created_at, updated_at, email_confirmed, failed_attempts, account_locked, lock_time 
    FROM users 
    LIMIT :start, :records_per_page
");
$stmt_users->bindValue(':start', $start_users, PDO::PARAM_INT);
$stmt_users->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

    <div class="container mx-auto">
        <!-- Display Users Table -->
        <h2 class="text-xl font-bold mb-4">User Management</h2>
        <form method="post" action="delete_users.php">
            <table class="min-w-full bg-white shadow-md rounded my-6">
                <thead>
                    <tr class="bg-gray-800 text-white text-left">
                        <th class="py-3 px-4">
                            <input type="checkbox" id="select_all_users">
                        </th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Password</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Role</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Status</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Created At</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Updated At</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Email Confirmed</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Failed Attempts</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Account Locked</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Lock Time</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="text-gray-700">
                            <td class="py-3 px-4">
                                <input type="checkbox" name="delete_ids[]" value="<?php echo htmlspecialchars($user['id']); ?>">
                            </td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['password']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['status']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['updated_at']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['email_confirmed']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['failed_attempts']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['account_locked']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($user['lock_time']); ?></td>
                            <td class="py-3 px-4">
                                <!-- Edit Button -->
                                <button type="button" class="text-blue-600" onclick="openEditModal(<?php echo $user['id']; ?>, '<?php echo $user['email']; ?>', '<?php echo $user['role']; ?>', '<?php echo $user['status']; ?>', <?php echo $user['failed_attempts']; ?>)">Edit</button>
                                
                                <!-- Delete Button -->
                                <button type="button" class="text-red-600" onclick="openDeleteModal(<?php echo $user['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 mt-4">Delete Selected</button>
        </form>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h2 class="text-xl font-bold mb-4">Edit User</h2>
                <form method="POST" action="edit_user.php">
                    <input type="hidden" name="id" id="editUserId">

                    <div class="mb-4">
                        <label for="editEmail" class="block text-sm font-semibold">Email</label>
                        <input type="email" name="email" id="editEmail" class="w-full px-4 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label for="editRole" class="block text-sm font-semibold">Role</label>
                        <select name="role" id="editRole" class="w-full px-4 py-2 border rounded">
                            <option value="Student">Student</option>
                            <option value="Cashier">Cashier</option>
                            <option value="College Department">College Department</option>
                            <option value="Registrar">Registrar</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="editStatus" class="block text-sm font-semibold">Status</label>
                        <select name="status" id="editStatus" class="w-full px-4 py-2 border rounded">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Lock/Unlock Account -->
                    <div class="mb-4">
                        <label for="editAccountLocked" class="block text-sm font-semibold">Account Locked</label>
                        <select name="account_locked" id="editAccountLocked" class="w-full px-4 py-2 border rounded">
                            <option value="0">Unlocked</option>
                            <option value="1">Locked</option>
                        </select>
                    </div>

                    <!-- Failed Attempts Dropdown -->
                    <div class="mb-4">
                        <label for="editFailedAttempts" class="block text-sm font-semibold">Failed Attempts</label>
                        <select name="failed_attempts" id="editFailedAttempts" class="w-full px-4 py-2 border rounded">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <!-- Password Change Section -->
                    <div class="mb-4">
                        <label for="newPassword" class="block text-sm font-semibold">New Password</label>
                        <input type="password" name="new_password" id="newPassword" class="w-full px-4 py-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <label for="confirmPassword" class="block text-sm font-semibold">Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirmPassword" class="w-full px-4 py-2 border rounded">
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2">Save</button>
                    <button type="button" onclick="closeEditModal()" class="bg-gray-600 text-white px-4 py-2">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Open and populate the Edit Modal
        function openEditModal(id, email, role, status, failed_attempts) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editStatus').value = status;
            document.getElementById('editAccountLocked').value = document.getElementById('editAccountLocked').options[0].value;
            
            // Set selected value for failed_attempts dropdown
            document.getElementById('editFailedAttempts').value = failed_attempts;

            document.getElementById('editModal').classList.remove('hidden');
        }

        // Close the Edit Modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Handle select all checkboxes
        document.getElementById('select_all_users').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>
</html>
