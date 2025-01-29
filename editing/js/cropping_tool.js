// Wait for the DOM to fully load before executing the script
document.addEventListener("DOMContentLoaded", function () {
	// Initialize the cropper variable
	let cropper

	// Elements
	const image = document.getElementById("image") // Image element for cropping
	const uploadButton = document.getElementById("upload-button") // Button to trigger file upload
	const uploadContainer = document.getElementById("upload-container") // Container for drag-and-drop upload
	const uploadInput = document.getElementById("upload") // File input element
	const uploadedImageContainer = document.getElementById("uploaded-image-container") // Container for the uploaded image
	const uploadedImage = document.getElementById("uploaded-image") // Uploaded image element
	const backToHome = document.getElementById("back-to-home") // Button to enable cropping tool
	const toolButton = document.getElementById("tool-button") // Button to enable cropping tool
	const uploadResultButton = document.getElementById("upload-result") // Button to get cropped result
	const resetToolButton = document.getElementById("reset-tool") // Button to reset the tool
	const nextToolButton = document.getElementById("next-tool") // Button to proceed to the next step
	const croppedImageResult = document.getElementById("upload-demo") // Container for displaying cropped image result

	// Modals
	const errorModal = new bootstrap.Modal(document.getElementById("errorModal")) // Error modal for displaying messages

	// Event listeners
	uploadButton.addEventListener("click", () => uploadInput.click()) // Trigger file input click on button click
	uploadContainer.addEventListener("dragover", handleDragOver) // Handle drag over event
	uploadContainer.addEventListener("dragleave", handleDragLeave) // Handle drag leave event
	uploadContainer.addEventListener("drop", handleDrop) // Handle drop event
	uploadInput.addEventListener("change", handleFileChange) // Handle file input change event
	toolButton.addEventListener("click", enableCroppingTool) // Enable cropping tool on button click
	uploadResultButton.addEventListener("click", getCroppedResult) // Get cropped result on button click
	resetToolButton.addEventListener("click", resetTool) // Reset the tool on button click

	// Handle drag over event
	function handleDragOver(e) {
		e.preventDefault() // Prevent default behavior
		uploadContainer.classList.add("border-secondary") // Add border class to indicate drag over
	}

	// Handle drag leave event
	function handleDragLeave(e) {
		e.preventDefault() // Prevent default behavior
		uploadContainer.classList.remove("border-secondary") // Remove border class
	}

	// Handle drop event
	function handleDrop(e) {
		e.preventDefault() // Prevent default behavior
		uploadContainer.classList.remove("border-secondary") // Remove border class
		uploadInput.files = e.dataTransfer.files // Assign dropped files to file input
		handleFileChange() // Handle file change
	}

	// Handle file change event
	function handleFileChange() {
		const file = uploadInput.files[0] // Get the first file
		const validTypes = ["image/png", "image/jpeg", "image/jpg", "image/webp"] // Valid file types
		if (!validTypes.includes(file.type)) {
			// Check for valid file type
			errorModal._element.querySelector(".modal-body").textContent =
				"The file type must be PNG, JPEG, JPG, or WEBP." // Set error message
			errorModal.show() // Show error modal
			uploadInput.value = "" // Reset file input
			return
		}
		if (file.size > 1048576) {
			// Check for file size limit
			errorModal._element.querySelector(".modal-body").textContent =
				"The file size must be less than 1MB." // Set error message
			errorModal.show() // Show error modal
			uploadInput.value = "" // Reset file input
			return
		}
		const reader = new FileReader() // Create FileReader instance
		reader.onload = (e) => {
			uploadInput.disabled = true // Disable file input

			uploadedImageContainer.style.width = "70%" // Set width of uploaded image container

			uploadedImage.src = e.target.result // Set uploaded image source
			uploadedImage.style.display = "block" // Display uploaded image

			uploadContainer.style.display = "none" // Hide upload container
			uploadContainer.style.width = 0 // Set width of upload container to 0

			toolButton.style.display = "block" // Display tool button
			backToHome.style.display = "none" // Display back to home button
			image.src = e.target.result // Set image source for cropping
		}
		reader.readAsDataURL(file) // Read file as data URL
	}

	// Enable the cropping tool
	function enableCroppingTool() {
		uploadedImageContainer.style.display = "none" // Hide uploaded image container
		uploadedImage.style.display = "none" // Hide uploaded image

		croppedImageResult.style.width = "70%" // Set width of cropped image result container
		croppedImageResult.style.display = "block" // Display cropped image result container

		toolButton.style.display = "none" // Hide tool button
		uploadResultButton.style.display = "block" // Display upload result button

		if (cropper) cropper.destroy() // Destroy existing cropper instance if any
		cropper = new Cropper(image, {
			// Initialize new cropper instance
			aspectRatio: NaN, // No fixed aspect ratio
			viewMode: 1, // View mode
			autoCropArea: 0.8, // Auto crop area
			movable: true, // Allow moving the image
			zoomable: true, // Allow zooming the image
			rotatable: true, // Allow rotating the image
			scalable: true, // Allow scaling the image
			cropBoxMovable: true, // Allow moving the crop box
			cropBoxResizable: true, // Allow resizing the crop box
			toggleDragModeOnDblclick: false, // Disable toggle drag mode on double click
		})
	}

	// Get the cropped result
	function getCroppedResult() {
		uploadResultButton.style.display = "none" // Hide upload result button
		const canvas = cropper.getCroppedCanvas() // Get cropped canvas
		const croppedImage = canvas.toDataURL("image/png") // Convert canvas to data URL
		document.getElementById(
			"upload-demo"
		).innerHTML = `<img src="${croppedImage}" style="max-width: 100%; max-height: 100%; margin: 0 auto; display: block;" />` // Display cropped image
		resetToolButton.style.display = "block" // Display reset tool button
		nextToolButton.style.display = "block" // Display next tool button
		nextToolButton.disabled = false // Enable next tool button
	}

	// Reset the tool
	function resetTool() {
		location.reload() // Reload the page to reset the tool
		nextToolButton.style.display = "none" // Hide next tool button
	}
})
