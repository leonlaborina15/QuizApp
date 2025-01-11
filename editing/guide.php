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
    <title>Photoshop Tools Guide</title>
    <link href="./css/global.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-container {
            max-width: 60rem;
            margin: 0 auto;
            padding: 2rem;
        }

        .main-title {
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .guide-section {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .tool-item {
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }

        .tool-category {
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

        .btn-proceed {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h1 class="main_title text-center">Adobe Photoshop Tools Guide</h1>

        <div class="guide-section">
            <h2 class="h3 mb-4">Introduction</h2>
            <p>
                Before starting the quiz, familiarize yourself with these essential Photoshop tools and their functions. Understanding these tools
                is crucial for effective image editing.
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
            <h3 class="h4 mb-4">Photoshop Tools Overview</h3>

            <h4 class="tool-category h5">Selection Tools</h4>
            <div class="tool-item">
                <h5 class="h6">Marquee Tools</h5>
                <ul class="list-unstyled">
                    <li><strong>Rectangular Marquee Tool:</strong> Creates rectangular or square selections</li>
                    <li><strong>Elliptical Marquee Tool:</strong> Creates circular or oval selections</li>
                </ul>
            </div>

            <div class="tool-item">
                <h5 class="h6">Lasso Tools</h5>
                <ul class="list-unstyled">
                    <li><strong>Lasso Tool:</strong> Creates freehand selections with irregular boundaries</li>
                    <li><strong>Polygonal Lasso Tool:</strong> Creates straight-edged selections by clicking points</li>
                </ul>
            </div>

            <h4 class="tool-category h5">Correction Tools</h4>
            <div class="tool-item">
                <h5 class="h6">Healing Tools</h5>
                <ul class="list-unstyled">
                    <li><strong>Healing Brush Tool:</strong> Blends imperfections with surrounding pixels</li>
                    <li><strong>Spot Healing Brush Tool:</strong> Automatically removes blemishes and small imperfections</li>
                    <li><strong>Clone Stamp Tool:</strong> Copies pixels from one area to another</li>
                </ul>
            </div>

            <h4 class="tool-category h5">Manipulation Tools</h4>
            <div class="tool-item">
                <h5 class="h6">Transform Tools</h5>
                <ul class="list-unstyled">
                    <li><strong>Move Tool:</strong> Moves layers, selections, or guides</li>
                    <li><strong>Crop Tool:</strong> Removes unwanted areas from image edges</li>
                    <li><strong>Perspective Crop Tool:</strong> Corrects perspective distortion while cropping</li>
                    <li><strong>Rotate View Tool:</strong> Temporarily rotates the canvas view without affecting the image</li>
                </ul>
            </div>

            <h4 class="tool-category h5">Creation Tools</h4>
            <div class="tool-item">
                <h5 class="h6">Shape and Path Tools</h5>
                <ul class="list-unstyled">
                    <li><strong>Pen Tool:</strong> Creates and edits precise paths</li>
                    <li><strong>Rectangle Tool:</strong> Creates rectangular or square shapes</li>
                </ul>
            </div>

            <div class="tool-item">
                <h5 class="h6">Utility Tools</h5>
                <ul class="list-unstyled">
                    <li><strong>Zoom Tool:</strong> Changes the view magnification of the image</li>
                    <li><strong>Eraser Tool:</strong> Removes or erases pixels from layers</li>
                </ul>
            </div>
        </div>

        <div class="guide-section">
            <h3 class="h4 mb-3">Tips for Success</h3>
            <ul class="rules-list">
                <li>Focus on understanding each tool's primary function</li>
                <li>Pay attention to the differences between similar tools</li>
                <li>Remember that some tools have multiple uses</li>
                <li>Consider the context of when each tool is most effective</li>
            </ul>
        </div>

        <div class="text-center mb-5">
            <a href="index.php" class="btn btn-secondary btn-lg btn-proceed me-3">Back to Home</a>
            <a href="pretest.php?category=editing" class="btn btn-warning btn-lg btn-proceed">Start Quiz</a>
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