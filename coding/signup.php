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
    <link href="./css/auth.css" rel="stylesheet" />
    <link href="./css/global.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="form_wrapper">
        <p class="mb-2 fs-4 fw-semibold text-center">Create an account</p>
        <p class="mb-5 text-center">Sign up to get started.</p>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="auth.php" method="post">
            <div class="form-group mb-4">
                <label class="fw-semibold mb-2" for="username">Username</label>
                <input type="text" class="form-control shadow-sm" id="username" name="username" minlength="6" required
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="e.g. Noobmaster69" />
            </div>
            <div class="form-group mb-4">
                <label class="fw-semibold mb-2" for="email">Email</label>
                <input type="email" class="form-control shadow-sm" id="email" name="email" required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="e.g. user@example.com" />
            </div>
            <div class="form-group mb-4">
                <label class="fw-semibold mb-2" for="password">Password</label>
                <input type="password" class="form-control shadow-sm" id="password" name="password" required />
            </div>
            <div class="form-group mb-4">
                <label class="fw-semibold mb-2" for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control shadow-sm" name="confirm_password" id="confirm_password" required />
            </div>
            <button type="submit" name="register" class="btn btn-warning" style="width: 100%;">Sign Up</button>
            <span class="mb-0 mt-3" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                Already have an account?
                <a href="login.php" class="btn btn-link d-block text-center p-0">Log In</a>
            </span>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <script>
        const { animate } = Motion;

        animate(document.body, { opacity: [0, 1] }, { duration: 0.25 });
        animate(".form_wrapper", { opacity: [0, 1], y: ["1.5rem", "0"] }, { duration: 0.25 });

        function validatePassword(password) {
            const length = password.length >= 8;
            const complexity = /[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password);
            return length && complexity;
        }

        $(document).ready(function () {
            $('#password').on('input', function () {
                const password = $(this).val();
                if (validatePassword(password)) {
                    $('#password').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#password').removeClass('is-valid').addClass('is-invalid');
                }
            });
        });
    </script>
</body>

</html>