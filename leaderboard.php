<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category = isset($_GET['category']) ? $_GET['category'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

// Get current user's username
$stmt = $conn->prepare("SELECT username FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current_user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch user's best score and time
if ($category) {
    $stmt = $conn->prepare("SELECT score, time_taken, date_achieved
                           FROM Leaderboards
                           WHERE user_id = ? AND category = ?
                           ORDER BY score DESC, time_taken ASC
                           LIMIT 1");
    $stmt->bind_param("is", $user_id, $category);
    $stmt->execute();
    $user_best = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Fetch leaderboard data
if ($category && $type) {
    $order_by = ($type == 'score') ? 'score DESC, time_taken ASC' : 'time_taken ASC, score DESC';
    $query = "SELECT Users.username, Leaderboards.score, Leaderboards.time_taken,
              Leaderboards.user_id, Leaderboards.date_achieved
              FROM Leaderboards
              JOIN Users ON Leaderboards.user_id = Users.user_id
              WHERE category = ?
              ORDER BY $order_by";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $leaderboard = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .current-user {
            background-color: #fff3cd;
        }
        .user-stats {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .badge-user {
            background-color: #17a2b8;
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.8em;
            margin-left: 5px;
        }
        .time-badge {
            color: #6c757d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mt-5">Leaderboard</h1>

    <?php if (!$category): ?>
        <h2>Select Category</h2>
        <div class="btn-group mt-3">
            <a href="leaderboard.php?category=editing" class="btn btn-primary">Editing</a>
            <a href="leaderboard.php?category=coding" class="btn btn-secondary">Coding</a>
        </div>

    <?php elseif (!$type): ?>
        <h2>Select Leaderboard Type - <?php echo ucfirst($category); ?></h2>
        <div class="btn-group mt-3">
            <a href="leaderboard.php?category=<?php echo $category; ?>&type=score" class="btn btn-primary">By Score</a>
            <a href="leaderboard.php?category=<?php echo $category; ?>&type=time" class="btn btn-secondary">By Time</a>
        </div>

        <?php if ($user_best): ?>
        <div class="user-stats mt-4">
            <h3>Your Best Performance</h3>
            <div class="row">
                <div class="col-md-4">
                    <h5>Score</h5>
                    <p class="h3"><?php echo $user_best['score']; ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Time</h5>
                    <p class="h3"><?php echo $user_best['time_taken']; ?> seconds</p>
                </div>
                <div class="col-md-4">
                    <h5>Achieved On</h5>
                    <p class="time-badge"><?php echo date('Y-m-d H:i', strtotime($user_best['date_achieved'])); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <h2><?php echo ucfirst($category); ?> Leaderboard - By <?php echo ucfirst($type); ?></h2>

        <?php if ($user_best): ?>
        <div class="user-stats">
            <h3>Your Best Performance</h3>
            <div class="row">
                <div class="col-md-4">
                    <h5>Score</h5>
                    <p class="h3"><?php echo $user_best['score']; ?></p>
                </div>
                <div class="col-md-4">
                    <h5>Time</h5>
                    <p class="h3"><?php echo $user_best['time_taken']; ?> seconds</p>
                </div>
                <div class="col-md-4">
                    <h5>Achieved On</h5>
                    <p class="time-badge"><?php echo date('Y-m-d H:i', strtotime($user_best['date_achieved'])); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <table class="table table-bordered mt-3">
            <thead class="thead-light">
                <tr>
                    <th width="10%">Rank</th>
                    <th width="30%">Username</th>
                    <th width="20%">Score</th>
                    <th width="20%">Time</th>
                    <th width="20%">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                while ($row = $leaderboard->fetch_assoc()):
                    $is_current_user = ($row['user_id'] == $user_id);
                ?>
                    <tr class="<?php echo $is_current_user ? 'current-user' : ''; ?>">
                        <td><?php echo $rank++; ?></td>
                        <td>
                            <?php echo htmlspecialchars($row['username']); ?>
                            <?php if ($is_current_user): ?>
                                <span class="badge-user">You</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['score']; ?></td>
                        <td><?php echo $row['time_taken']; ?> seconds</td>
                        <td class="time-badge">
                            <?php echo date('Y-m-d H:i', strtotime($row['date_achieved'])); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="mt-3 mb-5">
        <a href="index.php" class="btn btn-info">Back to Home</a>
        <?php if ($category): ?>
            <a href="leaderboard.php" class="btn btn-secondary">Change Category</a>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>