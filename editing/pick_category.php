<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CODEMORA - Enhance Your Coding and Editing Skills</title>
    <meta name="description" content="Join CODEMORA to improve your coding and editing skills through interactive quizzes and hands-on practice.">
    <meta name="keywords" content="CODEMORA, coding, editing, quizzes, skills, practice">
    <meta name="author" content="CODEMORA Team">
    <link href="./css/home.css" rel="stylesheet">
    <link href="./css/global.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="main_wrapper text-center">
        <h1 class="main_title">PICK CATEGORY</h1>
        <div class="action_wrapper">
            <a href="guide.php" class="btn btn-warning btn-border-radius-4 w-full">Editing</a>
            <a href="pretest.php?category=coding" class="btn btn-warning btn-border-radius-4">Coding</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <script>
        const { animate } = Motion;

        animate(document.body, { opacity: [0, 1] }, { duration: 0.25 });
    </script>
</body>

</html>