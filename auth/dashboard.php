<?php

session_start();

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($username) ?> 🎉</h2>

<p>You are logged in.</p>

<a href="/auth-system/auth/logout.php">Logout</a>

</body>
</html>
