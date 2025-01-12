<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz App</title>
  <meta name="description" content="Join CODEMORA to improve your coding and editing skills through interactive quizzes and hands-on practice.">
  <meta name="keywords" content="CODEMORA, coding, editing, quizzes, skills, practice">
  <meta name="author" content="CODEMORA Team">
  <link href="./css/global.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
  <div class="text-center text-light">
    <h1 class="main_title hidden">Welcome to the Quiz App</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
      <form action="pretest.php" method="get" class="mb-3 w-50 m-auto">
        <div class="d-flex justify-content-center mb-2 w-100 gap-2">
          <a href="pick_category.php" class="hidden btn btn-warning fs-5 fw-semibold text-warning-emphasis square_button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-question text-warning-emphasis">
              <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
              <path d="M12 17h.01" />
            </svg>Take Quiz</a>
          <a href="leaderboard.php" class="hidden btn btn-info fs-5 fw-semibold text-info-emphasis square_button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy text-info-emphasis">
              <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
              <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
              <path d="M4 22h16" />
              <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
              <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
              <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
            </svg>Leaderboard</a>
        </div>
        <a href="logout.php" class="hidden btn btn-danger fs-5 w-100">Logout</a>
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

    function animateElement(selector, delay = 0) {
      animate(selector,
        { opacity: [0, 1], y: [-20, 0], filter: ["blur(10px)", "blur(0px)"], scale: selector === ".main_title" ? [0.9, 1] : 1 },
        { type: "spring", damping: 12, stiffness: 100, duration: 0.5, delay: delay }
      );
    }

    document.addEventListener('DOMContentLoaded', () => {
      animateElement(".main_title");
      animateElement(".square_button:first-child", 0.25);
      animateElement(".square_button:last-child", 0.5);
      animateElement(".btn-danger", 0.75);
    });
  </script>
</body>

</html>