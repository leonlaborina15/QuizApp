<?php
session_start();
include 'db.php'; // Database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category = isset($_GET['category']) ? $_GET['category'] : 'editing'; // Default to 'editing' if not set

// Fetch 15 questions for post-test (randomized from pre-test)
$questions = $conn->query("SELECT * FROM Questions WHERE category='$category' ORDER BY RAND() LIMIT 15");

// Handle quiz submission
if (isset($_POST['submit_posttest'])) {
    $score = 0;
    $time_taken = $_POST['time_taken']; // Time taken to complete the quiz

    foreach ($_POST['answers'] as $question_id => $user_answer) {
        $stmt = $conn->prepare("SELECT correct_answer FROM Questions WHERE question_id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $stmt->bind_result($correct_answer);
        $stmt->fetch();
        $stmt->close();

        $is_correct = ($user_answer == $correct_answer) ? 1 : 0;
        $score += $is_correct;

        $stmt = $conn->prepare("INSERT INTO UserAnswers (user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $user_id, $question_id, $user_answer, $is_correct);
        $stmt->execute();
        $stmt->close();
    }

    // Update leaderboard
    $stmt = $conn->prepare("INSERT INTO Leaderboards (user_id, score, category, time_taken) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $user_id, $score, $category, $time_taken);
    $stmt->execute();
    $stmt->close();

    header("Location: leaderboard.php?category=<?php echo $category; ?>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post-test</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Post-test</h1>
    <form method="post" action="posttest.php?category=<?php echo $category; ?>" class="mt-3">
        <?php while ($question = $questions->fetch_assoc()): ?>
            <div class="mb-3">
                <p><?php echo $question['question_text']; ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="A" required>
                    <label class="form-check-label">A</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="B" required>
                    <label class="form-check-label">B</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="C" required>
                    <label class="form-check-label">C</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="D" required>
                    <label class="form-check-label">D</label>
                </div>
            </div>
        <?php endwhile; ?>
        <input type="hidden" name="time_taken" value="/* JavaScript to calculate time here */">
        <button type="submit" name="submit_posttest" class="btn btn-success">Submit Post-test</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>