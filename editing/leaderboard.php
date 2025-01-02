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
    <link href="./css/global.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .best-score {
            /* outline: lime dashed 1px; */
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
        <h1 class="text-light fs-1">Leaderboard</h1>

        <ul class="nav nav-pills mb-4">
            <li class="nav-item dropdown me-2">
                <a class="nav-link dropdown-toggle <?php echo $test_type != 'time' && $test_type != 'score' ? 'active' : ''; ?>" id="testTypeDropdown"
                    data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo ucfirst($test_type); ?> Tests
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item <?php echo $test_type == 'all' ? 'active' : ''; ?>" href="?test_type=all&sort_by=<?php echo $sort_by; ?>">All
                        Tests</a>
                    <a class="dropdown-item <?php echo $test_type == 'pre' ? 'active' : ''; ?>"
                        href="?test_type=pre&sort_by=<?php echo $sort_by; ?>">Pre-Tests</a>
                    <a class="dropdown-item <?php echo $test_type == 'post' ? 'active' : ''; ?>"
                        href="?test_type=post&sort_by=<?php echo $sort_by; ?>">Post-Tests</a>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link <?php echo $sort_by == 'time' ? 'active bg-warning text-dark' : 'text-light'; ?>"
                    href="?sort_by=time&test_type=<?php echo $test_type; ?>">By
                    Time</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $sort_by == 'score' ? 'active bg-warning text-dark' : 'text-light'; ?>"
                    href="?sort_by=score&test_type=<?php echo $test_type; ?>">By
                    Score</a>
            </li>
        </ul>

        <?php if ($best_performance): ?>
            <div class="best-score">
                <h3>Leaderboard - <?php echo $sort_by == 'score' ? 'By Score' : 'By Time'; ?></h3>
                <h4>Your Best Performance</h4>
                <div class="row">
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="fs-6 d-flex justify-content-between">
                                    Test Type <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                                        <path d="M12 17h.01" />
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z" />
                                        <path d="M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3" />
                                    </svg>
                                </h4>
                                <p class="mb-0 fs-4 fw-semibold"><?php echo ucfirst($best_performance['test_type']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="fs-6 d-flex justify-content-between">
                                    Achieved On <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                                        <path d="M8 2v4" />
                                        <path d="M16 2v4" />
                                        <rect width="18" height="18" x="3" y="4" rx="2" />
                                        <path d="M3 10h18" />
                                    </svg>
                                </h4>
                                <p class="mb-0 fs-4 fw-semibold"><?php echo date('M j, Y, g:i a', strtotime($best_performance['created_at'])); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="fs-6 d-flex justify-content-between">
                                    Score <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                                        <path
                                            d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                    </svg>
                                </h4>
                                <p class="mb-0 fs-4 fw-semibold"><?php echo $best_performance['score']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="fs-6 d-flex justify-content-between">
                                    Time <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                                        <line x1="10" x2="14" y1="2" y2="2" />
                                        <line x1="12" x2="15" y1="14" y2="11" />
                                        <circle cx="12" cy="14" r="8" />
                                    </svg>
                                </h4>
                                <p class="mb-0 fs-4 fw-semibold"><?php echo $best_performance['time_taken']; ?> seconds</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                        <td><?php echo date('M j, Y, g:i a', strtotime($entry['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4 mb-5">
            <a href="index.php" class="btn btn-warning">Back to Home</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <script>
        const { animate } = Motion;

        animate(document.body, { opacity: [0, 1] }, { duration: 0.5 });
    </script>
    <script>
        $(document).ready(function () {
            // Initialize Bootstrap dropdowns
            $('.dropdown-toggle').dropdown();
        });
    </script>
</body>

</html>