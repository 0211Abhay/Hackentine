<?php
session_start();
require_once '../../../../Hackentine/Modules/Includes/db_connect.php';

if (!isset($_SESSION['id']) || !isset($_POST['event_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['id'];
$event_id = $_POST['event_id'];

try {
    // Check if already registered
    $checkQuery = "SELECT * FROM event_registrations WHERE event_id = :event_id AND user_id = :user_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        header("Location: event.php?id=$event_id&msg=already_registered");
        exit();
    }

    // Register user for event
    $insertQuery = "INSERT INTO event_registrations (event_id, user_id) VALUES (:event_id, :user_id)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $insertStmt->execute();

    header("Location: event.php?id=$event_id&msg=registered");
    exit();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
