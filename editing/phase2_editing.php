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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
  <style>
    #post-test,
    #upload-demo,
    #uploaded-image,
    #upload-result,
    #tool-button,
    #upload,
    #paint-container {
      display: none;
    }

    #upload-demo img {
      max-width: 100%;
      max-height: 100%;
    }

    #paint-container {
      width: 100%;
      height: 400px;
      background-color: #f0f0f0;
      overflow: hidden;
    }

    #container {
      width: 100%;
      height: 100%;
    }

    #tool-instructions {
      width: 30%;
    }

    #upload-container {
      width: 70%;
      height: 400px;
      display: grid;
      place-items: center;
    }

    #uploaded-image-container,
    #upload-demo {
      max-height: 50vh;

      #uploaded-image,
      img {
        height: 100%;
        margin: 0 auto;
      }
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <h1 class="main_title">Phase 2</h1>
    <div class="container-md mt-5 p-4 bg-white rounded shadow">
      <div class="d-flex gap-3 w-100">
        <div id="tool-instructions" class="mb-4">
          <h2 id="tool-title">Crop and Rotate Tool</h2>
          <p id="tool-description">Removes unwanted areas from image edges and allows rotation.</p>
        </div>

        <!-- Dropzone for image -->
        <div id="upload-container" class="mb-4 border-secondary rounded p-4 text-center" style="border: 3px dashed;">
          <div>
            <p class="m-0 text-muted">Drag & Drop your image here or click to upload</p>
            <input type="file" id="upload" accept="image/*">
            <button id="upload-button" class="btn btn-primary mt-3">Upload Image</button>
          </div>
        </div>
        <!-- Uploaded image preview -->
        <div id="uploaded-image-container" class="mb-4">
          <img id="uploaded-image" src="#" alt="Uploaded Image" class="img-fluid">
        </div>

        <!-- Cropping tool -->
        <div id="upload-demo" class="mb-4">
          <img id="image" src="#" alt="Image for cropping" class="img-fluid">
        </div>

        <!-- Paint tool -->
        <div id="paint-container" class="mb-4">
          <select id="tool">
            <option value="brush">Brush</option>
            <option value="eraser">Eraser</option>
          </select>
          <input type="range" id="size" min="1" max="50" value="5">
          <div id="container"></div>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <button id="tool-button" class="btn btn-secondary fs-6 fw-semibold me-4">Start Cropping</button>
        <button id="upload-result" class="btn btn-success fs-6 fw-semibold me-4">Get Result</button>
        <button id="reset-tool" class="btn btn-warning fs-6 fw-semibold me-4" style="display: none;">Crop Another Image</button>
        <div id="rotate-container" class="input-group w-25">
          <button id="rotate-button" class="btn btn-secondary fs-6 fw-semibold" style="display: none;">Rotate</button>
          <input type="number" id="rotate-degree" class="form-control me-4" placeholder="Enter degree" style="display: none; width: 100px !important;">
        </div>
        <button id="next-tool" class="btn btn-info fs-6 fw-semibold" disabled>Next</button>
        <a id="post-test" href="posttest.php?category=editing" class="btn btn-warning fs-6 fw-semibold">Proceed to Post test</a>
      </div>
    </div>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="errorModalLabel">Error</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            The file size must be less than 1MB.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script src="./js/cropping_tool.js"></script>
  <script src="./js/paint_tool.js"></script>
</body>

</html>