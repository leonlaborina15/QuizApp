<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category = $_POST['category'] ?? 'editing';
$score = $_POST['score'] ?? 0;
$time_taken = $_POST['time_taken'] ?? 0;
$remaining_questions = $_POST['remaining_questions'] ?? 0;

// Add penalty time for remaining questions (60 seconds per question)
$total_time = $time_taken + ($remaining_questions * 60);

try {
    // Insert into Leaderboards
    $stmt = $conn->prepare("INSERT INTO Leaderboards (user_id, category, score, time_taken) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $user_id, $category, $score, $total_time);
    $stmt->execute();
    $stmt->close();

    // Get user's best score for this category
    $stmt = $conn->prepare("SELECT score, time_taken FROM Leaderboards
                           WHERE user_id = ? AND category = ?
                           ORDER BY score DESC, time_taken ASC LIMIT 1");
    $stmt->bind_param("is", $user_id, $category);
    $stmt->execute();
    $best_result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $response = [
        'success' => true,
        'score' => $score,
        'time_taken' => $total_time,
        'best_score' => $best_result['score'] ?? 0,
        'best_time' => $best_result['time_taken'] ?? 0
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);