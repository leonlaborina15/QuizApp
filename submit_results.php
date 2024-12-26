<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

if (!isset($_POST['score']) || !isset($_POST['time_taken'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required data']);
    exit();
}

$user_id = $_SESSION['user_id'];
$score = intval($_POST['score']);
$total_questions = intval($_POST['total_questions']);
$time_taken = intval($_POST['time_taken']);
$lives_remaining = intval($_POST['lives_remaining']);

try {
    $stmt = $conn->prepare("
        INSERT INTO Results (user_id, score, time_taken, lives_remaining)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("iiii",
        $user_id,
        $score,
        $time_taken,
        $lives_remaining
    );

    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Results saved successfully'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error saving results: ' . $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>