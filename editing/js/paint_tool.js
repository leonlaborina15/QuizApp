// Wait for the DOM to fully load before executing the script
document.addEventListener("DOMContentLoaded", function () {
	// Initialize the current tool to "cropping"
	let currentTool = "cropping"

	// Get the next tool button element and disable it initially
	const nextToolButton = document.getElementById("next-tool")
	nextToolButton.disabled = true

	// Get the reset tool button element
	const resetToolButton = document.getElementById("reset-tool")

	// Get the upload input element
	const uploadInput = document.getElementById("upload")

	// Get the upload container element
	const uploadContainer = document.getElementById("upload-container")

	// Get the uploaded image element
	const uploadedImage = document.getElementById("uploaded-image")

	// Get the image element
	const image = document.getElementById("image")

	// Add an event listener to the next tool button to switch tools when clicked
	nextToolButton.addEventListener("click", switchTool)

	// Function to switch between tools
	function switchTool() {
		if (currentTool === "cropping") {
			// Hide the upload demo section
			document.getElementById("upload-demo").style.display = "none"

			// Show the paint container section
			document.getElementById("paint-container").style.display = "block"

			// Hide the tool button
			document.getElementById("tool-button").style.display = "none"

			// Hide the upload result section
			document.getElementById("upload-result").style.display = "none"

			// Hide the uploaded image container
			document.getElementById("uploaded-image-container").style.display = "none"

			// Update the tool title to "Paint Tool"
			document.getElementById("tool-title").textContent = "Paint Tool"

			// Update the tool description
			document.getElementById("tool-description").textContent =
				"Use brush or eraser to draw on the canvas."

			// Hide the Crop Another Image button
			resetToolButton.style.display = "none"

			// Initialize the paint tool
			initPaintTool()

			// Change the next button text to "Previous"
			nextToolButton.textContent = "Previous"

			// Enable the next button
			nextToolButton.disabled = false

			// Set the current tool to "painting"
			currentTool = "painting"
		} else {
			// Reload the page to reset the tool
			location.reload()
		}
	}

	// Function to initialize the paint tool
	function initPaintTool() {
		// Create a script element to load the Konva library
		const script = document.createElement("script")
		script.src = "https://unpkg.com/konva@9.3.18/konva.min.js"

		// Once the script is loaded, initialize the Konva stage and layer
		script.onload = () => {
			// Get the container element for the canvas
			const container = document.getElementById("container")

			// Get the width and height of the container
			const width = container.offsetWidth
			const height = container.offsetHeight

			// Create a new Konva stage with the container dimensions
			const stage = new Konva.Stage({
				container: "container",
				width: width,
				height: height,
			})

			// Create a new Konva layer and add it to the stage
			const layer = new Konva.Layer()
			stage.add(layer)

			// Initialize painting state and mode
			let isPaint = false
			let mode = "brush"
			let brushSize = 5
			let lastLine

			// Event listener for starting to paint
			stage.on("mousedown touchstart", function (e) {
				isPaint = true
				const pos = stage.getPointerPosition()
				lastLine = new Konva.Line({
					stroke: "#df4b26",
					strokeWidth: brushSize,
					globalCompositeOperation: mode === "brush" ? "source-over" : "destination-out",
					lineCap: "round",
					lineJoin: "round",
					points: [pos.x, pos.y, pos.x, pos.y],
				})
				layer.add(lastLine)
			})

			// Event listener for stopping painting
			stage.on("mouseup touchend", function () {
				isPaint = false
			})

			// Event listener for painting
			stage.on("mousemove touchmove", function (e) {
				if (!isPaint) return
				e.evt.preventDefault()
				const pos = stage.getPointerPosition()
				const newPoints = lastLine.points().concat([pos.x, pos.y])
				lastLine.points(newPoints)
			})

			// Event listener for changing the tool mode (brush or eraser)
			document.getElementById("tool").addEventListener("change", function () {
				mode = this.value
			})

			// Event listener for changing the brush size
			document.getElementById("size").addEventListener("input", function () {
				brushSize = this.value
			})
		}
		// Append the script to the document head
		document.head.appendChild(script)
	}
})
