<?php
// login.php
session_start();
require_once __DIR__ . '/../config/db.php';

$errors = [];
$email = '';

// If already logged in → redirect
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // VALIDATION
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // AUTHENTICATION
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit;

        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custom.css">

</head>

<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">

            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">Login</h3>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $err): ?>
                                <p class="mb-0"><?= htmlspecialchars($err) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($email) ?>"
                                    required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required>
                        </div>

                        <button class="btn btn-primary w-100">Login</button>

                    </form>

                    <p class="text-center mt-3">
                        Don’t have an account?
                        <a href="register.php">Register</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>