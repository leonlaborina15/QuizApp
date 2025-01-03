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
    <title>CODEMORA - Login</title>
    <link href="./css/global.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow border border-warning border-3" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="auth.php" method="post">
            <div class="form-group mb-3">
                <label class="fw-semibold" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group mb-3">
                <label class="fw-semibold" for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Login</button>
            <span class="mb-0 mt-3" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                Don't have an account?
                <a href="signup.php" class="btn btn-link d-block text-center p-0">Signup here</a>
            </span>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>