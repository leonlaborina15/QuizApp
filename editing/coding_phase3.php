<?php
session_start();

$questions = [
    1 => [
        'question' => 'Add body tags to the code:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '',
            '</html>'
        ],
        'expected_elements' => ['body'],
        'lines' => 4
    ],
    2 => [
        'question' => 'Add head and title tags to the code:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '',
            '</html>'
        ],
        'expected_elements' => ['head', 'title'],
        'lines' => 4
    ],
    3 => [
        'question' => 'Add a paragraph inside the body:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['p'],
        'lines' => 6
    ],
    4 => [
        'question' => 'Add a list with one item:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['ul', 'li'],
        'lines' => 6
    ],
    5 => [
        'question' => 'Add a link to google.com:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['a'],
        'lines' => 6
    ],
    6 => [
        'question' => 'Add an image with a source and alt text:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['img'],
        'lines' => 6
    ],
    7 => [
        'question' => 'Add a table with one row and one cell:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['table', 'tr', 'td'],
        'lines' => 6
    ],
    8 => [
        'question' => 'Add a div with a class named "container":',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['div'],
        'lines' => 6
    ],
    9 => [
        'question' => 'Add a form with one input and a button:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['form', 'input', 'button'],
        'lines' => 6
    ],
    10 => [
        'question' => 'Add a heading and a paragraph:',
        'initial_code' => [
            '<!DOCTYPE html>',
            '<html>',
            '<body>',
            '',
            '</body>',
            '</html>'
        ],
        'expected_elements' => ['h1', 'p'],
        'lines' => 6
    ]
];

if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 1;
}

$current_question = $_SESSION['current_question'];

if (isset($_POST['next']) && $current_question < count($questions)) {
    $_SESSION['current_question']++;
    $current_question = $_SESSION['current_question'];
} elseif (isset($_POST['prev']) && $current_question > 1) {
    $_SESSION['current_question']--;
    $current_question = $_SESSION['current_question'];
}

if (!isset($questions[$current_question])) {
    $current_question = 1;
    $_SESSION['current_question'] = $current_question;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HTML Code Practice</title>
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

        #results {
            background-color: var(--background-color);
            padding: 15px;
            border-radius: 4px;
        }

        .code-textarea {
            width: 97%;
            height: 200px;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #A6CDC6;
            margin-bottom: 20px;
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
            <form method="post" id="codeForm">
                <textarea name="code" class="code-textarea"><?php
                    echo isset($_POST['code'])
                        ? htmlspecialchars($_POST['code'])
                        : htmlspecialchars(implode("\n", $questions[$current_question]['initial_code']));
                ?></textarea>

                <div class="button-group">
                    <div class="button-group-left">
                        <button type="button" id="runCode" class="button run-btn">
                            <i class="fas fa-play"></i> Run Code
                        </button>
                    </div>
                   <div class="button-group-right">
    <button type="submit" name="prev" class="button nav-btn" <?php if ($current_question <= 1) echo 'disabled'; ?>>
        Previous
    </button>
    <?php if ($current_question < count($questions)): ?>
        <button type="submit" name="next" class="button nav-btn">Next</button>
    <?php else: ?>
        <a href="http://localhost/quizApp/editing/index.php" class="button nav-btn">Home</a>
    <?php endif; ?>
</div>

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

        document.getElementById('runCode').addEventListener('click', function() {
            const form = document.getElementById('codeForm');
            const formData = new FormData(form);
            formData.append('check', 'true');

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const result = doc.querySelector('.result');
                const output = doc.querySelector('.output-frame');

                let resultContent = '';
                if (result) resultContent += result.outerHTML;
                if (output) resultContent += output.outerHTML;

                document.getElementById('resultContent').innerHTML = resultContent;
                document.querySelector('[data-tab="results"]').click();
            });
        });
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check'])) {
        $code = $_POST['code'];
        $dom = new DOMDocument();
        $hasError = false;
        $errorMessage = '';

        libxml_use_internal_errors(true);
        $dom->loadHTML($code, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        $backgroundColor = '';
        if (!empty($errors)) {
            $hasError = true;
            $errorMessage = "HTML Syntax Error: Please check your code structure.";
        } else {
            foreach ($questions[$current_question]['expected_elements'] as $element) {
                if ($dom->getElementsByTagName($element)->length === 0) {
                    $hasError = true;
                    $errorMessage = "Missing required <{$element}> tag!";
                    break;
                }
            }
            // Check for body background color
            $styles = $dom->getElementsByTagName('style');
            foreach ($styles as $style) {
                if (preg_match('/body\s*{\s*background-color\s*:\s*([^;]+);/i', $style->nodeValue, $matches)) {
                    $backgroundColor = $matches[1];
                }
            }
        }

        if ($hasError) {
            echo "<div class='result error'>❌ " . htmlspecialchars($errorMessage) . "</div>";
        } else {
            echo "<div class='result success'>✅ Correct! Your code contains all required elements.</div>";
            echo "<h3>Output:</h3><div class='output-frame' style='background-color: " . htmlspecialchars($backgroundColor) . ";'>" . $code . "</div>";
        }
    }
    ?>
</body>
</html>