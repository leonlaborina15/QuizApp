<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

if (!isset($_POST['question_id']) || !isset($_POST['user_answer'])) {
    echo json_encode(['error' => 'Missing parameters']);
    exit();
}

$question_id = $_POST['question_id'];
$user_answer = $_POST['user_answer'];

// Fetch the correct answer
$stmt = $conn->prepare("SELECT question_text, correct_answer FROM Questions WHERE question_id = ?");
$stmt->bind_param("i", $question_id);
$stmt->execute();
$stmt->bind_result($question_text, $correct_answer);
$stmt->fetch();
$stmt->close();

$is_correct = ($user_answer === $correct_answer);

// Store the answer in session
if (!isset($_SESSION['quiz_answers'])) {
    $_SESSION['quiz_answers'] = [];
}
$_SESSION['quiz_answers'][$question_id] = [
    'question_text' => $question_text,
    'user_answer' => $user_answer,
    'correct_answer' => $correct_answer,
    'is_correct' => $is_correct
];

echo json_encode([
    'is_correct' => $is_correct,
    'correct_answer' => $correct_answer,
    'question_text' => $question_text
]);
?>