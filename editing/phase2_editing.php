<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CODEMORA - Enhance Your Coding and Editing Skills</title>
  <meta name="description" content="Join CODEMORA to improve your coding and editing skills through interactive quizzes and hands-on practice.">
  <meta name="keywords" content="CODEMORA, coding, editing, quizzes, skills, practice">
  <meta name="author" content="CODEMORA Team">
  <link href="./css/global.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" rel="stylesheet">
  <style>
    #upload-demo {
      width: 300px;
      height: 300px;
      margin: 20px auto;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="question-header">
      <h2>Tool </h2>
    </div>
    <div id="upload-demo"></div>
    <button id="upload-result">Get Result</button>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
  <script>
    var $uploadCrop;

    $uploadCrop = $('#upload-demo').croppie({
      enableExif: true,
      viewport: {
        width: 200,
        height: 200,
        type: 'square'
      },
      boundary: {
        width: 320,
        height: 320
      }
    });

    $uploadCrop.croppie('bind', {
      url: './assets/sample.png'
    }).then(function () {
      console.log('jQuery bind complete');
    });

    $('#upload-result').on('click', function (ev) {
      $(this).prop('disabled', true);
      $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
      }).then(function (resp) {
        popupResult({
          src: resp
        });
      });
    });

    function popupResult(result) {
      var html;
      if (result.html) {
        html = result.html;
      }
      if (result.src) {
        html = '<img src="' + result.src + '" />';
      }
      $('#upload-demo').html(html);
    }
  </script>
</body>

</html>