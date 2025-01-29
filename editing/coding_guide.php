<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML & Coding Quiz Guide</title>
    <link href="./css/global.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .guide-section {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .concept-item {
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }

        .concept-category {
            color: #0056b3;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .rules-list {
            padding-left: 1.5rem;
        }

        .rules-list li::marker {
            color: #007bff;
            font-size: 1.2em;
        }
    </style>
</head>

<body class="hidden">
    <div class="main-container">
        <h1 class="main_title text-center">HTML & Coding Quiz Guide</h1>

        <div class="guide-section">
            <h2 class="h3 mb-4">Introduction</h2>
            <p>
                Before starting the quiz, familiarize yourself with these essential HTML and coding concepts. Understanding these basics
                will help you answer the quiz questions effectively.
            </p>
        </div>

        <div class="guide-section">
            <h3 class="h4 mb-3">Quiz Format</h3>
            <ul class="rules-list">
                <li>You will have <strong>60 seconds</strong> to answer each question</li>
                <li>You start with <strong>3 lives</strong></li>
                <li>Wrong answers will cost you one life</li>
                <li>The quiz ends when you either complete all questions or run out of lives</li>
                <li>Your score and completion time will be recorded on the leaderboard</li>
            </ul>
        </div>

        <div class="guide-section">
            <h3 class="h4 mb-4">Fundamental Coding Concepts</h3>

            <h4 class="concept-category h5">HTML Basics</h4>
            <div class="concept-item">
                <h5 class="h6">HTML Structure</h5>
                <ul class="list-unstyled">
                    <li>Understanding the meaning and role of HTML</li>
                    <li>Recognizing the correct sequence of HTML tags</li>
                </ul>
            </div>

            <div class="concept-item">
                <h5 class="h6">HTML Tags</h5>
                <ul class="list-unstyled">
                    <li>Common HTML tags and their purposes (e.g., headings, paragraphs, buttons, links, and images)</li>
                    <li>Recognizing opening and closing tag formats</li>
                </ul>
            </div>

            <h4 class="concept-category h5">Text Formatting & Styling</h4>
            <div class="concept-item">
                <h5 class="h6">Emphasis and Text Modifiers</h5>
                <ul class="list-unstyled">
                    <li>Using tags for bold and italicized text</li>
                    <li>Understanding how to emphasize text correctly</li>
                </ul>
            </div>

            <h4 class="concept-category h5">Navigation & Links</h4>
            <div class="concept-item">
                <h5 class="h6">Hyperlinks</h5>
                <ul class="list-unstyled">
                    <li>Understanding what the anchor tag (<code>&lt;a&gt;</code>) is used for</li>
                    <li>Using the <code>href</code> attribute to link to external pages</li>
                </ul>
            </div>

            <h4 class="concept-category h5">Multimedia Elements</h4>
            <div class="concept-item">
                <h5 class="h6">Images & Media</h5>
                <ul class="list-unstyled">
                    <li>Using the correct tag to insert images</li>
                    <li>Understanding the required attributes to display images properly</li>
                </ul>
            </div>
        </div>

        <div class="guide-section">
            <h3 class="h4 mb-3">Tips for Success</h3>
            <ul class="rules-list">
                <li>Focus on understanding the core structure of HTML</li>
                <li>Pay attention to the proper use of tags and attributes</li>
                <li>Differentiate between similar-looking tags</li>
                <li>Think about how elements interact on a webpage</li>
            </ul>
        </div>

        <div class="text-center mb-5">
            <a href="index.php" class="btn btn-secondary fs-5 fw-semibold me-4">Back to Home</a>
            <a href="pretest.php?category=coding" class="btn btn-warning fs-5 fw-semibold text-warning-emphasis">Start Quiz</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <script>
        const { animate } = Motion;
        animate(document.body, { opacity: [0, 1] }, { duration: 0.25 });
    </script>
</body>

</html>
