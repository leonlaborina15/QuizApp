<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz App</title>
  <link href="./css/global.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100" id="page-body">
  <div class="text-center text-light">
    <h1 class="main_title">Welcome to the Quiz App</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
      <form action="pretest.php" method="get" class="mb-3 row w-50 m-auto">
        <a href="guide.php" class="btn btn-warning fs-5 mb-4">Take Quiz</a>
        <a href="leaderboard.php" class="btn btn-info fs-5 mb-4">View Leaderboard</a>
        <a href="logout.php" class="btn btn-danger fs-5">Logout</a>
      </form>
    <?php else: ?>
      <a href="signup.php" class="btn btn-primary mb-2">Signup</a>
      <a href="login.php" class="btn btn-secondary mb-2">Login</a>
    <?php endif; ?>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
  <script>
    const { animate } = Motion;

    animate(document.body, { opacity: [0, 1] }, { duration: 0.5 });
    animate(".main_title", { opacity: [0, 1], y: ["10%", "0"] }, { duration: 0.5 });
  </script>
</body>

</html>