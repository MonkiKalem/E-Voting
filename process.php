<?php
require 'db.php'; // Make sure db.php includes the connection code correctly

function log_message($message) {
    file_put_contents('debug.log', $message . "\n", FILE_APPEND);
}

function redirect_with_message($message, $location) {
    echo "<script>alert('$message'); window.location.href='$location';</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'];

    if (isset($_FILES['id_card_image']) && isset($_FILES['face_image'])) {
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // File paths for the uploaded images
        $id_card_image = $target_dir . uniqid() . "_" . basename($_FILES["id_card_image"]["name"]);
        $face_image = $target_dir . uniqid() . "_" . basename($_FILES["face_image"]["name"]);

        // Sanitize file paths
        $id_card_image = preg_replace('/[^A-Za-z0-9_\.-]/', '', $id_card_image);
        $face_image = preg_replace('/[^A-Za-z0-9_\.-]/', '', $face_image);

        // Move uploaded images to the uploads folder
        if (!move_uploaded_file($_FILES["id_card_image"]["tmp_name"], $id_card_image)) {
            die("Failed to move ID card image to uploads directory.");
        }

        if (!move_uploaded_file($_FILES["face_image"]["tmp_name"], $face_image)) {
            die("Failed to move face image to uploads directory.");
        }

        log_message("ID Card Image Path: " . $id_card_image);
        log_message("Face Image Path: " . $face_image);

        // Check if the user has already voted
        $stmt = $conn->prepare("SELECT id FROM users WHERE nik = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("s", $nik);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->close();
            redirect_with_message("You have already voted.", 'index.php');
            exit();
        }
        $stmt->close();

        // Path to Python script for face recognition
        $python_path = "C:\\Users\\syuja\\AppData\\Local\\Programs\\Python\\Python312\\python.exe";
        $python_script_path = __DIR__ . DIRECTORY_SEPARATOR . 'evote_face_recognition.py';
        
        // Command to execute Python script
        $command = escapeshellcmd($python_path) . ' ' . escapeshellarg($python_script_path) . ' ' . escapeshellarg($id_card_image) . ' ' . escapeshellarg($face_image) . ' 2>&1';
        log_message("Running command: " . $command);
        
        // Execute the command and capture output
        $output = shell_exec($command);
        log_message("Output: " . $output);

        if ($output === null) {
            die("Error: Unable to execute the command.");
        }

        // Decode JSON output from Python script
        $result = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Error: Invalid JSON response from face recognition script. JSON error: " . json_last_error_msg());
        }

        if (!$result || !isset($result['status'])) {
            log_message("Error: Invalid response from face recognition script.");
            die("Error: Invalid response from face recognition script.");
        }

        if ($result['status'] == 'verified') {
            // Insert user data into database if face verification successful
            $stmt = $conn->prepare("INSERT INTO users (nik, id_card_image, face_image) VALUES (?, ?, ?)");
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }
            
            $stmt->bind_param("sss", $nik, $id_card_image, $face_image);

            if ($stmt->execute()) {
                session_start();
                $_SESSION['user_id'] = $stmt->insert_id;
                redirect_with_message("Face verification successful.", 'vote.php');
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Redirect with message if face verification failed
            redirect_with_message("Face verification failed: " . htmlspecialchars($result['message']), 'index.php');
        }
    } else {
        echo "Error: Please upload both ID card image and face image.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
