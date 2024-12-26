<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'time';

$stmt = $conn->prepare("SELECT username FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$current_username = $user_data['username'];
$stmt->close();

$stmt = $conn->prepare("
    SELECT score, time_taken, created_at
    FROM Results
    WHERE user_id = ?
    ORDER BY score DESC, time_taken ASC
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$best_performance = $result->fetch_assoc();
$stmt->close();

if ($sort_by == 'score') {
    $query = "
        SELECT u.username, r.score, r.time_taken, r.created_at,
               IF(u.user_id = ?, ' You', '') as is_current_user
        FROM Results r
        INNER JOIN Users u ON r.user_id = u.user_id
        INNER JOIN (
            SELECT user_id, MAX(score) as max_score
            FROM Results
            GROUP BY user_id
        ) maxScores ON r.user_id = maxScores.user_id AND r.score = maxScores.max_score
        ORDER BY r.score DESC, r.time_taken ASC
        LIMIT 100
    ";
} else {
    $query = "
        SELECT u.username, r.score, r.time_taken, r.created_at,
               IF(u.user_id = ?, ' You', '') as is_current_user
        FROM Results r
        INNER JOIN Users u ON r.user_id = u.user_id
        INNER JOIN (
            SELECT user_id, MIN(time_taken) as min_time
            FROM Results
            GROUP BY user_id
        ) minTimes ON r.user_id = minTimes.user_id AND r.time_taken = minTimes.min_time
        ORDER BY r.time_taken ASC
        LIMIT 100
    ";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$leaderboard = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .best-score {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .current-user {
            background-color: #e3f2fd;
        }
        .nav-pills .nav-link.active {
            background-color: #007bff;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Leaderboard</h1>

    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link <?php echo $sort_by == 'time' ? 'active' : ''; ?>"
               href="?sort_by=time">By Time</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $sort_by == 'score' ? 'active' : ''; ?>"
               href="?sort_by=score">By Score</a>
        </li>
    </ul>

    <?php if ($best_performance): ?>
    <div class="best-score">
        <h3>Leaderboard - <?php echo $sort_by == 'score' ? 'By Score' : 'By Time'; ?></h3>
        <h4>Your Best Performance</h4>
        <p>Score: <?php echo $best_performance['score']; ?></p>
        <p>Time: <?php echo $best_performance['time_taken']; ?> seconds</p>
        <p>Achieved On: <?php echo date('Y-m-d H:i', strtotime($best_performance['created_at'])); ?></p>
    </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Username</th>
                <th>Score</th>
                <th>Time</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leaderboard as $index => $entry): ?>
            <tr class="<?php echo strpos($entry['username'], $current_username) !== false ? 'current-user' : ''; ?>">
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars($entry['username'] . $entry['is_current_user']); ?></td>
                <td><?php echo $entry['score']; ?></td>
                <td><?php echo $entry['time_taken']; ?> seconds</td>
                <td><?php echo date('Y-m-d H:i', strtotime($entry['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-4 mb-5">
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>