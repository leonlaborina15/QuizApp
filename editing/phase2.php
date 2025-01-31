<?php
session_start();
include 'db.php'; // Database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase 2</title>
    <link href="./css/global.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-container d-flex justify-content-center align-items-center flex-column">
        <h1 class="main_title mb-0 text-center">Phase 2</h1>
        <h1 class="main_title text-center">Hands-on Experience</h1>
        <p class="text-white text-center w-50">This is a placeholder for Phase 2. You can provide tools and resources based on the incorrect answers in the
            pre-test.</p>
        <a href="phase2_editing.php" class="btn btn-warning fs-5 fw-semibold text-warning-emphasis">Proceed to Post-test</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>