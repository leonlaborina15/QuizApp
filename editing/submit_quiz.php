<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['quiz_answers'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$answers = $_SESSION['quiz_answers'];
$score = 0;

foreach ($answers as $question_id => $answer) {
    if ($answer['is_correct']) {
        $score++;
    }

    // Store in database
    $stmt = $conn->prepare("INSERT INTO UserAnswers (user_id, question_id, user_answer, is_correct) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $user_id, $question_id, $answer['user_answer'], $answer['is_correct']);
    $stmt->execute();
    $stmt->close();
}

$_SESSION['quiz_score'] = $score;
echo json_encode(['success' => true, 'score' => $score]);
?>