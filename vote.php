<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate = $_POST['candidate'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already voted
    $stmt = $conn->prepare("SELECT id FROM votes WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        echo "<script>alert('You have already voted.'); window.location.href='index.php';</script>";
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO votes (user_id, candidate) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $candidate);

    if ($stmt->execute()) {
        echo "<script>alert('Your vote for " . htmlspecialchars($candidate) . " has been recorded. Thank you for voting!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
} else {
    // Redirect to the voting form
    header('Location: vote_form.html');
    exit();
}

$conn->close();
?>
