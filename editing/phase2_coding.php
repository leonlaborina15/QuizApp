<?php
session_start();

$questions = [
    1 => [
        'question' => 'Add body tags to the code:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '',
            '',
            '</html>'
        ],
        'expected_elements' => ['body', '/body'],
        'content' => ['This is a paragraph inside the body.']
    ],
    2 => [
        'question' => 'Add a head with a title:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '',
            '',
            '',
            '',
            '<body>',
            '    <p>This is some body content.</p>',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['head', 'title', '/title', '/head'],
        'content' => ['My Dynamic Page']
    ],
    3 => [
        'question' => 'Add a list inside the body:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '',
            '',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['ul', 'li', '/li', '/ul'],
        'content' => ['Item 1', 'Item 2', 'Item 3']
    ],
];

if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 1;
}

$current_question = $_SESSION['current_question'];

if (isset($_POST['next']) && $current_question < count($questions)) {
    $_SESSION['current_question']++;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} elseif (isset($_POST['prev']) && $current_question > 1) {
    $_SESSION['current_question']--;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($questions[$current_question])) {
    $current_question = 1;
    $_SESSION['current_question'] = $current_question;
}

function renderInitialCode($initial_code, $expected_elements)
{
    $drop_zone_count = 0;
    foreach ($initial_code as $line) {
        if (trim($line) === '') {
            echo "<div class='drop-zone' data-expected='" . $expected_elements[$drop_zone_count] . "'></div>\n";
            $drop_zone_count++;
        } else {
            echo htmlspecialchars($line) . "\n";
        }
    }
}

function renderChoices($expected_elements)
{
    foreach ($expected_elements as $element) {
        echo "<div class='draggable' draggable='true' data-element='$element'>&lt;$element&gt;</div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>HTML Drag-and-Drop Quiz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0C356A;
            --secondary-color: #0174BE;
            --highlight-color: #FFC436;
            --background-light: #FFF0CE;
            --error-color: #f44336;
            --success-color: #4CAF50;
            --background-color: #f5f5f5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: var(--background-color);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .question-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .question-header h2 {
            margin: 0;
        }

        .nav-tabs {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            margin-top: 20px;
            background-color: var(--secondary-color);
            border-radius: 8px;
            overflow: hidden;
        }

        .nav-tabs li {
            flex: 1;
            text-align: center;
            padding: 10px 15px;
            cursor: pointer;
            color: white;
            background-color: var(--secondary-color);
            transition: background-color 0.3s;
        }

        .nav-tabs li.active {
            background-color: var(--highlight-color);
            color: black;
        }

        .tab-content {
            display: none;
            margin-top: 20px;
        }

        .tab-content.active {
            display: block;
        }

        .drop-zone {
            display: inline-block;
            min-width: 150px;
            padding: 10px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            background-color: var(--background-light);
            margin: 5px;
        }

        .drop-zone.valid {
            background-color: #e8f5e9;
            border-color: var(--success-color);
        }

        .drop-zone.invalid {
            background-color: #ffebee;
            border-color: var(--error-color);
        }

        .draggable {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 4px;
            cursor: grab;
        }

        .draggable:active {
            cursor: grabbing;
        }

        .code-preview {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            background-color: #A6CDC6;
            padding: 10px;
            border-radius: 5px;
            white-space: pre-wrap;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 20px 0;
        }

        .button-group-left {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
        }

        .button-group-right {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .run-btn {
            background-color: var(--success-color);
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn {
            background-color: var(--secondary-color);
            color: white;
        }

        .button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .result.success {
            background-color: #e8f5e9;
            border: 1px solid var(--success-color);
            color: var(--success-color);
        }

        .result.error {
            background-color: #ffebee;
            border: 1px solid var(--error-color);
            color: var(--error-color);
        }

        .output-frame {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="question-header">
            <h2>Question <?php echo $current_question; ?></h2>
            <p><?php echo $questions[$current_question]['question']; ?></p>
        </div>

        <ul class="nav-tabs">
            <li class="active" data-tab="code">Code Editor</li>
            <li data-tab="results">Results</li>
        </ul>

        <div id="code" class="tab-content active">
            <div class="code-preview">
                <?php renderInitialCode($questions[$current_question]['initial_code'], $questions[$current_question]['expected_elements']); ?>
            </div>

            <div class="choices-container">
                <h4>Drag the correct tags below:</h4>
                <div class="drag-container">
                    <?php renderChoices($questions[$current_question]['expected_elements']); ?>
                </div>
            </div>

            <form method="post" class="button-group">
                <div class="button-group-left">
                    <button type="submit" name="prev" class="button nav-btn" <?php if ($current_question <= 1)
                        echo 'disabled'; ?>>Previous</button>
                </div>
                <div class="button-group-right">
                    <button type="submit" name="next" class="button nav-btn" <?php if ($current_question >= count($questions))
                        echo 'disabled'; ?>>Next</button>
                </div>
            </form>
        </div>

        <div id="results" class="tab-content">
            <h3>Results</h3>
            <div id="resultContent"></div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.nav-tabs li').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.nav-tabs li').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });

        document.querySelectorAll('.draggable').forEach(draggable => {
            draggable.addEventListener('dragstart', function (e) {
                e.dataTransfer.setData('text', this.getAttribute('data-element'));
            });
        });

        document.querySelectorAll('.drop-zone').forEach(zone => {
            zone.addEventListener('dragover', function (e) {
                e.preventDefault();
            });

            zone.addEventListener('drop', function (e) {
                e.preventDefault();
                const element = e.dataTransfer.getData('text');
                const expected = this.getAttribute('data-expected');

                if (element === expected) {
                    this.innerHTML = `&lt;${element}&gt;`;
                    this.classList.add('valid');
                    this.classList.remove('invalid');
                } else {
                    this.innerHTML = `&lt;${element}&gt;`;
                    this.classList.add('invalid');
                    this.classList.remove('valid');
                }

                checkResults();
                updateOutput();
            });
        });

        function checkResults() {
            const dropZones = document.querySelectorAll('.drop-zone');
            let allValid = true;

            dropZones.forEach(zone => {
                if (!zone.classList.contains('valid')) {
                    allValid = false;
                }
            });

            const resultContainer = document.getElementById('resultContent');
            if (allValid) {
                resultContainer.innerHTML = "<div class='result success'>✅ Correct! All tags are in the right place and properly nested.</div>";
            } else {
                resultContainer.innerHTML = "<div class='result error'>❌ Incorrect! Please check your tags and their nesting.</div>";
            }
        }

        function updateOutput() {
            const dropZones = document.querySelectorAll('.drop-zone');
            let htmlCode = `<!DOCTYPE html>\n<html>\n`;

            dropZones.forEach(zone => {
                htmlCode += zone.innerHTML + '\n';
            });

            htmlCode += `</html>`;

            const outputContainer = document.getElementById('resultContent');
            outputContainer.innerHTML += `<h3>Output:</h3><div class='output-frame'>${htmlCode}</div>`;
        }
    </script>
</body>

</html>