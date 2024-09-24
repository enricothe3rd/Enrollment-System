<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Email is required.";
    } else {
        // Check if the email exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Normally you would send an email with a reset link here
            $success = "A password reset link has been sent to your email.";
        } else {
            $error = "No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2>Forgot Password</h2>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p>$success</p>"; ?>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <button type="submit">Send Reset Link</button>
    </form>
    <a href="login.php">Back to Login</a>
</body>
</html>
