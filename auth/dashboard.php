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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custom.css">

</head>

<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">

        <a class="navbar-brand" href="#">Auth System</a>

        <div class="ms-auto">
            <span class="text-white me-3">
                Welcome, <?= htmlspecialchars($username) ?>
            </span>

            <a href="/auth-system/auth/logout.php" class="btn btn-light btn-sm">
                Logout
            </a>
        </div>

    </div>
</nav>


<!-- Main Content -->
<div class="container mt-5">

    <div class="row">
        <div class="col-md-8">

            <div class="card shadow border-0">
                <div class="card-body">

                    <h4 class="mb-3">Dashboard</h4>

                    <p>You are logged in successfully</p>

                    <p>
                        This is your protected page. Only authenticated users can see this.
                    </p>

                </div>
            </div>

        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>