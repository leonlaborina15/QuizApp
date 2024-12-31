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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .guide-section {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .tool-item {
            margin-bottom: 20px;
            padding: 15px;
            border-left: 4px solid #007bff;
            background-color: white;
        }
        .rules-list {
            list-style-type: none;
            padding-left: 0;
        }
        .rules-list li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        .rules-list li:before {
            content: "•";
            color: #007bff;
            font-size: 20px;
            position: absolute;
            left: 0;
        }
        .btn-proceed {
            margin-top: 20px;
            padding: 10px 30px;
        }
        .tool-category {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Adobe Photoshop Tools Guide</h1>

        <!-- Introduction Section -->
        <div class="guide-section">
            <h2>Introduction</h2>
            <p>Before starting the quiz, familiarize yourself with these essential Photoshop tools and their functions. Understanding these tools is crucial for effective image editing.</p>
        </div>

        <!-- Quiz Format Section -->
        <div class="guide-section">
            <h3>Quiz Format</h3>
            <ul class="rules-list">
                <li>You will have <strong>60 seconds</strong> to answer each question</li>
                <li>You start with <strong>3 lives</strong></li>
                <li>Wrong answers will cost you one life</li>
                <li>The quiz ends when you either complete all questions or run out of lives</li>
                <li>Your score and completion time will be recorded on the leaderboard</li>
            </ul>
        </div>

        <!-- Tools Overview Section -->
        <div class="guide-section">
            <h3>Photoshop Tools Overview</h3>

            <h4 class="tool-category">Selection Tools</h4>
            <div class="tool-item">
                <h5>Marquee Tools</h5>
                <ul>
                    <li><strong>Rectangular Marquee Tool:</strong> Creates rectangular or square selections</li>
                    <li><strong>Elliptical Marquee Tool:</strong> Creates circular or oval selections</li>
                </ul>
            </div>

            <div class="tool-item">
                <h5>Lasso Tools</h5>
                <ul>
                    <li><strong>Lasso Tool:</strong> Creates freehand selections with irregular boundaries</li>
                    <li><strong>Polygonal Lasso Tool:</strong> Creates straight-edged selections by clicking points</li>
                </ul>
            </div>

            <h4 class="tool-category">Correction Tools</h4>
            <div class="tool-item">
                <h5>Healing Tools</h5>
                <ul>
                    <li><strong>Healing Brush Tool:</strong> Blends imperfections with surrounding pixels</li>
                    <li><strong>Spot Healing Brush Tool:</strong> Automatically removes blemishes and small imperfections</li>
                    <li><strong>Clone Stamp Tool:</strong> Copies pixels from one area to another</li>
                </ul>
            </div>

            <h4 class="tool-category">Manipulation Tools</h4>
            <div class="tool-item">
                <h5>Transform Tools</h5>
                <ul>
                    <li><strong>Move Tool:</strong> Moves layers, selections, or guides</li>
                    <li><strong>Crop Tool:</strong> Removes unwanted areas from image edges</li>
                    <li><strong>Perspective Crop Tool:</strong> Corrects perspective distortion while cropping</li>
                    <li><strong>Rotate View Tool:</strong> Temporarily rotates the canvas view without affecting the image</li>
                </ul>
            </div>

            <h4 class="tool-category">Creation Tools</h4>
            <div class="tool-item">
                <h5>Shape and Path Tools</h5>
                <ul>
                    <li><strong>Pen Tool:</strong> Creates and edits precise paths</li>
                    <li><strong>Rectangle Tool:</strong> Creates rectangular or square shapes</li>
                </ul>
            </div>

            <div class="tool-item">
                <h5>Utility Tools</h5>
                <ul>
                    <li><strong>Zoom Tool:</strong> Changes the view magnification of the image</li>
                    <li><strong>Eraser Tool:</strong> Removes or erases pixels from layers</li>
                </ul>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="guide-section">
            <h3>Tips for Success</h3>
            <ul class="rules-list">
                <li>Focus on understanding each tool's primary function</li>
                <li>Pay attention to the differences between similar tools</li>
                <li>Remember that some tools have multiple uses</li>
                <li>Consider the context of when each tool is most effective</li>
            </ul>
        </div>

        <!-- Navigation Buttons -->
        <div class="text-center mb-5">
            <a href="pretest.php" class="btn btn-primary btn-lg btn-proceed">Start Quiz</a>
            <a href="index.php" class="btn btn-secondary btn-lg btn-proceed ml-3">Back to Home</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>