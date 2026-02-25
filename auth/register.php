<?php
// register.php
require_once __DIR__ . '/../config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Basic validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, insert into DB
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            header("Location: login.php");
            exit;

        } catch (PDOException $e) {
            // Handle duplicate email
            if ($e->getCode() == 23000) {
                $errors[] = "Email already exists.";
            } else {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 40px auto; }
        .error { background: #ffdddd; padding: 10px; margin-bottom: 15px; border-left: 4px solid #d00; }
        .toggle { cursor: pointer; font-size: 0.9em; color: #0077cc; }
    </style>
</head>
<body>

<h2>Create an Account</h2>

<?php if (!empty($errors)): ?>
    <div class="error">
        <?php foreach ($errors as $err): ?>
            <p><?= htmlspecialchars($err) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="" method="POST">

    <label>Username</label><br>
    <input type="text" name="username" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" id="password" name="password" required>
    <span class="toggle" onclick="togglePassword()">Show</span><br><br>

    <label>Confirm Password</label><br>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <span class="toggle" onclick="toggleConfirm()">Show</span><br><br>

    <button type="submit">Register</button>
</form>

<script>
    function togglePassword() {
        const field = document.getElementById('password');
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    function toggleConfirm() {
        const field = document.getElementById('confirm_password');
        field.type = field.type === 'password' ? 'text' : 'password';
    }
</script>

</body>
</html>