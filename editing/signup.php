<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']); // Clear error after displaying
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
    <h3 class="text-center mb-4">Signup</h3>
    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <form action="auth.php" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input
                type="text"
                class="form-control"
                id="username"
                name="username"
                placeholder="Enter username"
                required
                value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter email"
                required
                value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Enter password"
                required
                value="<?= isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input
                type="password"
                class="form-control"
                id="confirm_password"
                name="confirm_password"
                placeholder="Re-enter password"
                required
                value="<?= isset($_POST['confirm_password']) ? htmlspecialchars($_POST['confirm_password']) : ''; ?>">
        </div>
        <button type="submit" name="register" class="btn btn-primary btn-block">Signup</button>
        <a href="login.php" class="btn btn-link d-block text-center mt-2">Already have an account? Login</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
