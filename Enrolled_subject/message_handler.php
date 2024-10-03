<?php

// Initialize messages
$success_message = '';
$message1 = '';

// Check if there are any messages in the session
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear message after displaying
}

if (isset($_SESSION['error_message'])) {
    $message1 = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear message after displaying
}
?>

<!-- Success Message -->
<?php if (!empty($success_message)): ?>
    <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4">
        <?php echo htmlspecialchars($success_message); ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove the success message after 5 seconds
            setTimeout(function() {
                var successMessageDiv = document.getElementById('success-message');
                if (successMessageDiv) {
                    successMessageDiv.style.display = 'none';
                }
            }, 5000);
        });
    </script>
<?php endif; ?>

<!-- Error Message -->
<?php if (!empty($message1)): ?>
    <div id="message1" class="bg-red-100 text-red-700 border border-red-400 rounded px-4 py-3 mt-4">
        <?= htmlspecialchars($message1) ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Remove the error message after 5 seconds
            setTimeout(function() {
                var messageDiv = document.getElementById('message1');
                if (messageDiv) {
                    messageDiv.style.display = 'none';
                }
            }, 5000);
        });
    </script>
<?php endif; ?>
