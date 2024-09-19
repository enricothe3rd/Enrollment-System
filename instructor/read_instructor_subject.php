<?php
require 'Instructor_subject.php';

$instructorSubject = new InstructorSubject();

// Fetch all instructor-subject assignments
$assignments = $instructorSubject->read();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Instructor Subjects</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
async function deleteAssignment(id) {
    if (confirm("Are you sure you want to delete this assignment?")) {
        try {
            const response = await fetch('delete_instructor_subject.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ id })
            });

            const result = await response.json();
            if (result.success) {
                alert("Assignment deleted successfully!");
                window.location.reload();
            } else {
                alert(`Failed to delete assignment. ${result.message || ''}`);
            }
        } catch (error) {
            console.error('Error deleting assignment:', error);
        }
    }
}
    </script>
</head>
<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto mt-10 bg-white p-8 shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-700">Instructor Subjects</h2>

        <div class="mb-4">
            <a href="create_instructor_subject.php" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Add New Assignment</a>
        </div>

        <table class="min-w-full bg-white border rounded-lg">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Instructor</th>
                    <th class="py-3 px-6 text-left">Subject</th>
                    <th class="py-3 px-6 text-left">Semester</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-6"><?php echo htmlspecialchars($assignment['instructor_name']); ?></td>
                    <td class="py-3 px-6"><?php echo htmlspecialchars($assignment['subject_name']); ?></td>
                    <td class="py-3 px-6"><?php echo htmlspecialchars($assignment['semester_name']); ?></td>
                    <td class="py-3 px-6 text-center">
                        <!-- Edit Button -->
                        <a href="edit_instructor_subject.php?id=<?php echo htmlspecialchars($assignment['id']); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Edit</a>
                        <!-- Delete Button -->
                        <button onclick="deleteAssignment(<?php echo htmlspecialchars($assignment['id']); ?>)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
