
<?php
require 'Department.php';

$department = new Department();
$departments = $department->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class=" font-sans leading-normal tracking-normal">
    <div class=" mt-10 ">
        <h1 class="text-2xl font-semibold text-red-800 mb-4">Departments</h1>
        <a href="create_department.php" class="inline-block mb-4 px-4 py-4 bg-red-700 text-white rounded hover:bg-red-800">Add New Department</a>
        <table class="w-full border-collapse  shadow-md rounded-lg">
            <thead class="bg-red-800">
                <tr>
                    <!-- <th class="px-4 py-4 border-b text-left text-white">ID</th> -->
                    <th class="px-4 py-4 border-b text-left text-white">Name</th>
                    <th class="px-4 py-4 border-b text-left text-white">Established</th>
                    <th class="px-4 py-4 border-b text-left text-white">Dean</th>
                    <th class="px-4 py-4 border-b text-left text-white">Email</th>
                    <th class="px-4 py-4 border-b text-left text-white">Phone</th>
                    <th class="px-4 py-4 border-b text-left text-white">Location</th>
                    <th class="px-4 py-4 border-b text-left text-white">Faculty Count</th>
                    <th class="px-4 py-4 border-b text-left text-white">Student Count</th>
                    <th class="px-4 py-4 border-b text-left text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $dept): ?>
                <tr class="border-b bg-red-50 hover:bg-red-200">
                    <!-- <td class="px-4 py-4"><?php echo htmlspecialchars($dept['id']); ?></td> -->
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['name']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['established']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['dean']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['email']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['phone']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['location']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['faculty_count']); ?></td>
                    <td class="px-4 py-4"><?php echo htmlspecialchars($dept['student_count']); ?></td>
                    <td class="px-4 py-4">
                        <a href="update_department.php?id=<?php echo $dept['id']; ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Edit</a>
                        <a href="delete_department.php?id=<?php echo $dept['id']; ?>" onclick="return confirm('Are you sure?');" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1 px-2 rounded transition duration-150">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
