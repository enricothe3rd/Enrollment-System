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

// Pagination and data fetching for Password Resets
$page_password_resets = isset($_GET['page_password_resets']) ? (int)$_GET['page_password_resets'] : 1;
$start_password_resets = ($page_password_resets - 1) * $records_per_page;

$stmt_password_resets = $pdo->query("SELECT COUNT(*) FROM password_resets");
$total_password_resets = $stmt_password_resets->fetchColumn();
$total_pages_password_resets = ceil($total_password_resets / $records_per_page);

$stmt_password_resets = $pdo->prepare("SELECT id, email, token, created_at, expires_at FROM password_resets LIMIT :start, :records_per_page");
$stmt_password_resets->bindParam(':start', $start_password_resets, PDO::PARAM_INT);
$stmt_password_resets->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt_password_resets->execute();
$password_resets = $stmt_password_resets->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Management Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="container mx-auto">
        <!-- Display Password Resets Table -->
        <h2 class="text-xl font-bold mt-8 mb-4">Password Resets</h2>
        <form method="post" action="delete_password_resets.php">
            <table class="min-w-full bg-white shadow-md rounded my-6">
                <thead>
                    <tr class="bg-gray-800 text-white text-left">
                        <th class="py-3 px-4">
                            <input type="checkbox" id="select_all">
                        </th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">ID</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Email</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Token</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Created At</th>
                        <th class="py-3 px-4 uppercase font-semibold text-sm">Expires At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($password_resets as $reset): ?>
                        <tr class="text-gray-700">
                            <td class="py-3 px-4">
                                <input type="checkbox" name="delete_ids[]" value="<?php echo $reset['id']; ?>">
                            </td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($reset['id']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($reset['email']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($reset['token']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($reset['created_at']); ?></td>
                            <td class="py-3 px-4"><?php echo htmlspecialchars($reset['expires_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="bg-red-600 text-white px-4 py-2 mt-4">Delete Selected</button>
        </form>


        <!-- Pagination for Password Resets -->
        <div class="flex justify-center items-center mt-6 space-x-2">
            <?php if ($page_password_resets > 1): ?>
                <a href="<?php echo paginationUrl(['page_password_resets' => $page_password_resets - 1]); ?>" class="px-4 py-2 bg-gray-300 text-gray-600">Previous</a>
            <?php endif; ?>

            <div class="flex space-x-2">
                <?php for ($i = 1; $i <= $total_pages_password_resets; $i++): ?>
                    <a href="<?php echo paginationUrl(['page_password_resets' => $i]); ?>"
                       class="px-4 py-2 <?php echo $i == $page_password_resets ? 'bg-blue-500 text-white' : 'bg-gray-300 text-gray-600'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>

            <?php if ($page_password_resets < $total_pages_password_resets): ?>
                <a href="<?php echo paginationUrl(['page_password_resets' => $page_password_resets + 1]); ?>" class="px-4 py-2 bg-gray-300 text-gray-600">Next</a>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
<script>
    document.getElementById('select_all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
