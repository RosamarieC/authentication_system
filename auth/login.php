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
    /* ======================
       VALIDATION
    ====================== */

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    /* ======================
       AUTHENTICATION
    ====================== */

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            // Store session data
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
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 40px auto; }
        .error { background: #ffdddd; padding: 10px; margin-bottom: 15px; border-left: 4px solid #d00; }
    </style>
</head>
<body>

<h2>Login</h2>

<?php if (!empty($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $err): ?>
            <p><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="" method="POST">

    <label>Email</label><br>
    <input type="email" name="email"
           value="<?= htmlspecialchars($email) ?>" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>

