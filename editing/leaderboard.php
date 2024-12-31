<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'time';
$test_type = isset($_GET['test_type']) ? $_GET['test_type'] : 'all';

// Get current user's username
$stmt = $conn->prepare("SELECT username FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$current_username = $user_data['username'];
$stmt->close();

// Get user's best performance
$test_type_condition = $test_type != 'all' ? "AND test_type = ?" : "";
$stmt = $conn->prepare("
    SELECT score, time_taken, created_at, test_type
    FROM Results
    WHERE user_id = ? $test_type_condition
    ORDER BY score DESC, time_taken ASC
    LIMIT 1
");

if ($test_type != 'all') {
    $stmt->bind_param("is", $user_id, $test_type);
} else {
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$result = $stmt->get_result();
$best_performance = $result->fetch_assoc();
$stmt->close();

// Modify the queries to include test_type filter
if ($sort_by == 'score') {
    $query = "
        SELECT u.username, r.score, r.time_taken, r.created_at, r.test_type,
               IF(u.user_id = ?, ' You', '') as is_current_user
        FROM Results r
        INNER JOIN Users u ON r.user_id = u.user_id
        INNER JOIN (
            SELECT user_id, MAX(score) as max_score
            FROM Results
            " . ($test_type != 'all' ? "WHERE test_type = ?" : "") . "
            GROUP BY user_id
        ) maxScores ON r.user_id = maxScores.user_id AND r.score = maxScores.max_score
        " . ($test_type != 'all' ? "WHERE r.test_type = ?" : "") . "
        ORDER BY r.score DESC, r.time_taken ASC
        LIMIT 100
    ";
} else {
    $query = "
        SELECT u.username, r.score, r.time_taken, r.created_at, r.test_type,
               IF(u.user_id = ?, ' You', '') as is_current_user
        FROM Results r
        INNER JOIN Users u ON r.user_id = u.user_id
        INNER JOIN (
            SELECT user_id, MIN(time_taken) as min_time
            FROM Results
            " . ($test_type != 'all' ? "WHERE test_type = ?" : "") . "
            GROUP BY user_id
        ) minTimes ON r.user_id = minTimes.user_id AND r.time_taken = minTimes.min_time
        " . ($test_type != 'all' ? "WHERE r.test_type = ?" : "") . "
        ORDER BY r.time_taken ASC
        LIMIT 100
    ";
}

// Update the bind_param call
if ($test_type != 'all') {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $test_type, $test_type);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
}

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
        .dropdown-item.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Leaderboard</h1>

    <ul class="nav nav-pills mb-4">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle <?php echo $test_type != 'time' && $test_type != 'score' ? 'active' : ''; ?>"
           id="testTypeDropdown"
           data-toggle="dropdown"
           href="#"
           role="button"
           aria-haspopup="true"
           aria-expanded="false">
            <?php echo ucfirst($test_type); ?> Tests
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item <?php echo $test_type == 'all' ? 'active' : ''; ?>"
               href="?test_type=all&sort_by=<?php echo $sort_by; ?>">All Tests</a>
            <a class="dropdown-item <?php echo $test_type == 'pre' ? 'active' : ''; ?>"
               href="?test_type=pre&sort_by=<?php echo $sort_by; ?>">Pre-Tests</a>
            <a class="dropdown-item <?php echo $test_type == 'post' ? 'active' : ''; ?>"
               href="?test_type=post&sort_by=<?php echo $sort_by; ?>">Post-Tests</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $sort_by == 'time' ? 'active' : ''; ?>"
           href="?sort_by=time&test_type=<?php echo $test_type; ?>">By Time</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $sort_by == 'score' ? 'active' : ''; ?>"
           href="?sort_by=score&test_type=<?php echo $test_type; ?>">By Score</a>
    </li>
</ul>

    <?php if ($best_performance): ?>
    <div class="best-score">
        <h3>Leaderboard - <?php echo $sort_by == 'score' ? 'By Score' : 'By Time'; ?></h3>
        <h4>Your Best Performance</h4>
        <p>Score: <?php echo $best_performance['score']; ?></p>
        <p>Time: <?php echo $best_performance['time_taken']; ?> seconds</p>
        <p>Test Type: <?php echo ucfirst($best_performance['test_type']); ?></p>
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
                <th>Test Type</th>
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
                <td><?php echo ucfirst($entry['test_type']); ?></td>
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
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Bootstrap dropdowns
    $('.dropdown-toggle').dropdown();
});
</script>
</body>
</html>