<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['pretest_results'])) {
    header("Location: index.php");
    exit();
}

$results = $_SESSION['pretest_results'];
$score = $_SESSION['pretest_score'];
$total_questions = count($results);
$lives = $_SESSION['lives'];
$time_taken = $_SESSION['time_taken'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-test Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Pre-test Results</h1>
    <?php if ($lives > 0): ?>
        <p>Score: <?php echo $score; ?> out of <?php echo $total_questions; ?></p>
        <p>Time taken: <?php echo $time_taken; ?> seconds</p>
        <h2>Results</h2>
        <ul class="list-group">
            <?php foreach ($results as $result): ?>
                <li class="list-group-item">
                    <p><?php echo $result['question_text']; ?></p>
                    <p class="<?php echo $result['is_correct'] ? 'text-success' : 'text-danger'; ?>">Your answer: <?php echo $result['user_answer']; ?></p>
                    <?php if (!$result['is_correct']): ?>
                        <p class="text-success">Correct answer: <?php echo $result['correct_answer']; ?></p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="phase2.php" class="btn btn-primary mt-3">Proceed to Phase 2</a>
    <?php else: ?>
        <p class="text-danger">Game Over! You have lost all your lives.</p>
        <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>