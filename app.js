async function setupCamera() {
    const video = document.getElementById('video');
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    video.srcObject = stream;
}

async function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('captured-image');
    const context = canvas.getContext('2d');

    // Draw the current video frame to the canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert the canvas image to a Blob or base64 to be sent to the server for verification
    const dataUrl = canvas.toDataURL('image/jpeg');

    // Create a blob from the dataURL and set it to the file input
    const blob = await (await fetch(dataUrl)).blob();
    const file = new File([blob], "face_image.jpg", { type: "image/jpeg" });

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    document.getElementById('face_image').files = dataTransfer.files;
}

document.getElementById('capture-btn').addEventListener('click', capturePhoto);

// Start the camera
setupCamera();
