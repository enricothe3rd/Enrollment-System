<?php
session_start();
require '../../db_connection.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../../index.php");
    exit();
}

// Initialize message variable
$msg = '';

// Handle unlock request
if (isset($_GET['unlock']) && !empty($_GET['unlock'])) {
    $emailToUnlock = trim($_GET['unlock']);

    // Update account to unlock
    $sql = "UPDATE users SET account_locked = 0, failed_attempts = 0, lock_time = NULL WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $emailToUnlock);

    if ($stmt->execute()) {
        $msg = "Account unlocked successfully.";
    } else {
        $msg = "Error unlocking account: " . $conn->errorInfo()[2];
    }
}

// Fetch locked accounts
$sql = "SELECT email, lock_time FROM users WHERE account_locked = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$lockedAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locked Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800">Manage Locked Accounts</h1>
        
        <?php if (!empty($msg)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md max-w-md mx-auto text-center">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto bg-white rounded-lg shadow-lg p-6 border border-gray-200 max-w-4xl mx-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lock Time</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($lockedAccounts)): ?>
                        <?php foreach ($lockedAccounts as $account): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($account['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($account['lock_time']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="admin_unlock.php?unlock=<?php echo urlencode($account['email']); ?>" class="text-blue-600 hover:text-blue-800 font-semibold" onclick="return confirm('Are you sure you want to unlock this account?');">
                                        Unlock
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No locked accounts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
