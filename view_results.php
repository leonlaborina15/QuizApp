<?php
session_start();
include 'db.php';

if (!isset($_SESSION['quiz_results'])) {
    header("Location: index.php");
    exit();
}

$results = $_SESSION['quiz_results'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .correct-answer {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .wrong-answer {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .question-container {
            border: 1px solid #dee2e6;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .score-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="score-summary">
            <h2>Quiz Results</h2>
            <p>Total Score: <?php echo $_SESSION['final_score']; ?></p>
            <p>Time Taken: <?php echo $_SESSION['time_taken']; ?> seconds</p>
            <p>Lives Remaining: <?php echo $_SESSION['lives_remaining']; ?></p>
        </div>

        <?php foreach ($results as $index => $result): ?>
            <div class="question-container">
                <h4>Question <?php echo $index + 1; ?></h4>
                <p class="font-weight-bold"><?php echo $result['question_text']; ?></p>

                <div class="<?php echo $result['is_correct'] ? 'correct-answer' : 'wrong-answer'; ?>">
                    <p>Your Answer: <?php echo $result['user_answer_text']; ?></p>
                    <?php if (!$result['is_correct']): ?>
                        <p>Correct Answer: <?php echo $result['correct_answer_text']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center mt-4">
            <a href="leaderboards.php" class="btn btn-info mr-2">View Leaderboards</a>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>