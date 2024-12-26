<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="text-center">
    <h1 class="mb-4">Welcome to the Quiz App</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
      <div>
        <form action="pretest.php" method="get" class="mb-3">
            <button type="submit" name="category" value="editing" class="btn btn-primary mb-2">Start Editing Pre-test</button>
            <button type="submit" name="category" value="coding" class="btn btn-secondary mb-2">Start Coding Pre-test</button>
        </form>
        <a href="leaderboard.php" class="btn btn-info mb-2">View Leaderboard</a>
        <a href="logout.php" class="btn btn-danger mb-2">Logout</a>
      </div>
    <?php else: ?>
        <a href="signup.php" class="btn btn-primary mb-2">Signup</a>
        <a href="login.php" class="btn btn-secondary mb-2">Login</a>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
