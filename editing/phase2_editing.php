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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
  <style>
    #upload-demo {
      display: none;
      width: 100%;
      height: auto;
      margin: 20px auto;
      overflow: hidden;
    }

    #uploaded-image {
      display: none;
      max-width: 100%;
    }

    #upload-result {
      display: none;
    }

    #tool-button {
      display: none;
    }

    #upload-demo img {
      max-width: 100%;
      max-height: 100%;
    }

    #upload {
      display: none;
    }
  </style>
</head>

<body>
  <div class="container mt-5 p-4 bg-white rounded shadow">
    <div class="cropping-instructions text-center mb-4">
      <h2>Cropping Tool</h2>
      <p>Removes unwanted areas from image edges.</p>
    </div>
    <div id="upload-container" class="mb-4 border border-2 border-dashed rounded p-4 text-center">
      <p class="m-0 text-muted">Drag & Drop your image here or click to upload</p>
      <input type="file" id="upload" accept="image/*">
      <button id="upload-button" class="btn btn-primary mt-3">Upload Image</button>
    </div>
    <div id="uploaded-image-container" class="mb-4">
      <img id="uploaded-image" src="#" alt="Uploaded Image" class="img-fluid">
    </div>
    <div id="upload-demo" class="mb-4">
      <img id="image" src="#" alt="Image for cropping" class="img-fluid">
    </div>
    <div class="d-flex justify-content-between">
      <button id="tool-button" class="btn btn-secondary">Start Cropping</button>
      <button id="upload-result" class="btn btn-success">Get Result</button>
      <button id="reset-tool" class="btn btn-warning" style="display: none;">Crop Another Image</button>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var cropper;
    var image = document.getElementById('image');
    var isTriggeringChange = false; // Add a flag to prevent recursive triggering

    // Trigger file input click when upload button is clicked
    $('#upload-button').on('click', function (e) {
      e.preventDefault();
      $('#upload').click();
    });

    // Handle drag over event for the upload container
    $('#upload-container').on('dragover', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass('border-secondary');
    });

    // Handle drag leave event for the upload container
    $('#upload-container').on('dragleave', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass('border-secondary');
    });

    // Handle drop event for the upload container
    $('#upload-container').on('drop', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass('border-secondary');
      var files = e.originalEvent.dataTransfer.files;
      $('#upload')[0].files = files;
      if (!isTriggeringChange) {
        isTriggeringChange = true;
        $('#upload').trigger('change');
        isTriggeringChange = false;
      }
    });

    // Handle file input change event
    $('#upload').on('change', function () {
      var file = this.files[0];
      var validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
      // Check file type
      if (!validTypes.includes(file.type)) {
        $('#errorModal .modal-body').text('The file type must be PNG, JPEG, JPG, or WEBP.');
        $('#errorModal').modal('show');
        $(this).val('');
        return;
      }
      // Check file size
      if (file.size > 1048576) {
        $('#errorModal .modal-body').text('The file size must be less than 1MB.');
        $('#errorModal').modal('show');
        $(this).val('');
        return;
      }
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#uploaded-image').attr('src', e.target.result).show();
        $('#upload').prop('disabled', true);
        $('#tool-button').show();
        $('#image').attr('src', e.target.result);
      }
      reader.readAsDataURL(file);
    });

    // Enable cropping tool when tool button is clicked
    $('#tool-button').on('click', function () {
      enableCroppingTool();
    });

    // Get cropped result when upload result button is clicked
    $('#upload-result').on('click', function () {
      $(this).prop('disabled', true);
      var canvas = cropper.getCroppedCanvas();
      var croppedImage = canvas.toDataURL('image/png');
      popupResult({
        src: croppedImage
      });
      $('#reset-tool').show();
    });

    // Reset tool when reset button is clicked
    $('#reset-tool').on('click', function () {
      resetTool();
    });

    // Function to enable cropping tool
    function enableCroppingTool() {
      $('#uploaded-image-container').hide();
      $('#uploaded-image').hide();
      $('#upload-demo').show();
      $('#tool-button').hide();
      $('#upload-result').show();
      if (cropper) {
        cropper.destroy();
      }
      cropper = new Cropper(image, {
        aspectRatio: NaN, // Allows freeform cropping
        viewMode: 1,
        autoCropArea: 0.8,
        movable: true,
        zoomable: true,
        rotatable: true,
        scalable: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false
      });
    }

    // Function to reset the tool
    function resetTool() {
      location.reload(); // Reload the page to reset the tool
    }

    // Function to display the cropped result
    function popupResult(result) {
      var html;
      if (result.src) {
        html = '<img src="' + result.src + '" style="max-width: 100%; max-height: 100%;" />';
      }
      $('#upload-demo').html(html);
    }
  </script>
</body>

</html>