<?php
session_start();

if (isset($_POST['results'])) {
    $_SESSION['quiz_results'] = json_decode($_POST['results'], true);
    $_SESSION['final_score'] = $_POST['score'];
    $_SESSION['time_taken'] = $_POST['time'];
    $_SESSION['lives_remaining'] = $_POST['lives'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No results data']);
}
?>