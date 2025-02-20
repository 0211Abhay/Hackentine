<?php
// Database Connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "event_management";

$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Get JSON Data
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id']) || !isset($data['status'])) {
    die(json_encode(["success" => false, "message" => "Invalid request"]));
}

$eventId = intval($data['id']);
$newStatus = $conn->real_escape_string($data['status']);

// Update Query
$sql = "UPDATE events SET status = '$newStatus' WHERE id = $eventId";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update status"]);
}

$conn->close();
?>
