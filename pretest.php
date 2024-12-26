<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$category = isset($_GET['category']) ? $_GET['category'] : 'editing';

// Fetch questions
$questions = $conn->query("SELECT * FROM Questions WHERE category='$category' LIMIT 15")->fetch_all(MYSQLI_ASSOC);

if (!isset($_SESSION['lives'])) {
    $_SESSION['lives'] = 3;
}
if (!isset($_SESSION['pretest_start'])) {
    $_SESSION['pretest_start'] = time();
}

// Fetch user's best performance
$stmt = $conn->prepare("SELECT score, time_taken FROM Leaderboards
                       WHERE user_id = ? AND category = ?
                       ORDER BY score DESC, time_taken ASC LIMIT 1");
$stmt->bind_param("is", $user_id, $category);
$stmt->execute();
$best_performance = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-test</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .question-modal {
            display: none;
        }
        .question-modal.active {
            display: block;
        }
        .form-check {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .form-check:hover {
            background-color: #f8f9fa;
        }
        .form-check-input {
            margin-top: 3px;
        }
        .form-check-label {
            width: 100%;
            cursor: pointer;
            margin-left: 10px;
        }
        .best-performance {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mt-5">Pre-test</h1>

    <?php if ($best_performance): ?>
    <div class="best-performance">
        <h4>Your Best Performance</h4>
        <p>Score: <?php echo $best_performance['score']; ?></p>
        <p>Time: <?php echo $best_performance['time_taken']; ?> seconds</p>
    </div>
    <?php endif; ?>

    <div class="alert alert-info">
        <p>Lives: <span id="lives"><?php echo $_SESSION['lives']; ?></span></p>
        <p>Time remaining: <span id="time">60</span> seconds</p>
        <p>Questions remaining: <span id="remainingQuestions"><?php echo count($questions); ?></span></p>
    </div>

    <div id="quiz-container">
        <?php foreach ($questions as $index => $question): ?>
            <div id="question-<?php echo $index; ?>" class="question-modal <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Question <?php echo $index + 1; ?> of <?php echo count($questions); ?></h5>
                        <p class="card-text"><?php echo $question['question_text']; ?></p>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="question<?php echo $index; ?>"
                                   id="choice_a_<?php echo $index; ?>"
                                   value="A">
                            <label class="form-check-label" for="choice_a_<?php echo $index; ?>">
                                A. <?php echo $question['choice_a']; ?>
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="question<?php echo $index; ?>"
                                   id="choice_b_<?php echo $index; ?>"
                                   value="B">
                            <label class="form-check-label" for="choice_b_<?php echo $index; ?>">
                                B. <?php echo $question['choice_b']; ?>
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="question<?php echo $index; ?>"
                                   id="choice_c_<?php echo $index; ?>"
                                   value="C">
                            <label class="form-check-label" for="choice_c_<?php echo $index; ?>">
                                C. <?php echo $question['choice_c']; ?>
                            </label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="question<?php echo $index; ?>"
                                   id="choice_d_<?php echo $index; ?>"
                                   value="D">
                            <label class="form-check-label" for="choice_d_<?php echo $index; ?>">
                                D. <?php echo $question['choice_d']; ?>
                            </label>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-primary next-btn"
                                    data-question="<?php echo $question['question_id']; ?>"
                                    data-index="<?php echo $index; ?>">
                                <?php echo $index < count($questions) - 1 ? 'Next' : 'Finish'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Game Over Modal -->
<div class="modal fade" id="gameOverModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quiz Complete!</h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3>Your Score: <span id="finalScore">0</span></h3>
                    <p>Lives Remaining: <span id="finalLives">0</span></p>
                    <p>Time Taken: <span id="timeTaken">0</span> seconds</p>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" onclick="submitResults()">Submit Results</button>
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
let currentQuestion = 0;
let timer;
let lives = <?php echo $_SESSION['lives']; ?>;
let answers = {};
let startTime = new Date();
let quizResults = [];
let totalQuestions = <?php echo count($questions); ?>;
let answeredQuestions = 0;

function resetTimer() {
    clearInterval(timer);
    let timeRemaining = 60;
    $('#time').text(timeRemaining);

    timer = setInterval(() => {
        timeRemaining--;
        $('#time').text(timeRemaining);

        if (timeRemaining <= 0) {
            clearInterval(timer);
            handleTimeUp();
        }
    }, 1000);
}

function handleTimeUp() {
    lives--;
    $('#lives').text(lives);

    if (lives <= 0) {
        showGameOver();
        return;
    }

    moveToNextQuestion();
}

function calculateTotalTime() {
    let baseTime = Math.floor((new Date() - startTime) / 1000);
    let remainingQuestions = totalQuestions - answeredQuestions;
    // Add 60 seconds for each unanswered question
    return baseTime + (remainingQuestions * 60);
}

function showGameOver() {
    clearInterval(timer);
    const totalTime = calculateTotalTime();
    const correctAnswers = quizResults.filter(result => result.is_correct).length;

    $('#finalScore').text(correctAnswers + ' / ' + totalQuestions);
    $('#finalLives').text(lives);
    $('#timeTaken').text(totalTime);

    $('#gameOverModal').modal('show');
}

function submitResults() {
    const totalTime = calculateTotalTime();
    const correctAnswers = quizResults.filter(result => result.is_correct).length;

    $.ajax({
        type: 'POST',
        url: 'submit_results.php',
        data: {
            score: correctAnswers,
            time_taken: totalTime,
            category: '<?php echo $category; ?>'
        },
        success: function(response) {
            window.location.href = 'leaderboard.php?category=<?php echo $category; ?>';
        },
        error: function() {
            alert('Error submitting results. Please try again.');
        }
    });
}

function moveToNextQuestion() {
    answeredQuestions++;
    $('#remainingQuestions').text(totalQuestions - answeredQuestions);

    $('.question-modal').removeClass('active');
    currentQuestion++;

    if (currentQuestion < totalQuestions) {
        $(`#question-${currentQuestion}`).addClass('active');
        resetTimer();
    } else {
        showGameOver();
    }
}

$(document).ready(function() {
    resetTimer();

    $('.form-check').click(function() {
        $(this).find('input[type="radio"]').prop('checked', true);
    });

    $('.next-btn').click(function() {
        const questionId = $(this).data('question');
        const index = $(this).data('index');
        const selectedAnswer = $(`input[name="question${index}"]:checked`).val();

        if (!selectedAnswer) {
            alert('Please select an answer before proceeding.');
            return;
        }

        answers[questionId] = selectedAnswer;

        $.ajax({
            type: 'POST',
            url: 'check_answer.php',
            data: {
                question_id: questionId,
                user_answer: selectedAnswer
            },
            success: function(response) {
                const result = JSON.parse(response);
                const currentQuestion = $(`#question-${index}`);
                quizResults.push({
                    question_text: currentQuestion.find('.card-text').text(),
                    user_answer: selectedAnswer,
                    user_answer_text: currentQuestion.find(`label[for="choice_${selectedAnswer.toLowerCase()}_${index}"]`).text(),
                    correct_answer: result.correct_answer,
                    correct_answer_text: currentQuestion.find(`label[for="choice_${result.correct_answer.toLowerCase()}_${index}"]`).text(),
                    is_correct: result.is_correct
                });

                if (!result.is_correct) {
                    lives--;
                    $('#lives').text(lives);

                    if (lives <= 0) {
                        showGameOver();
                        return;
                    }
                }
                moveToNextQuestion();
            },
            error: function() {
                alert('Error checking answer. Please try again.');
            }
        });
    });
});
</script>
</body>
</html>