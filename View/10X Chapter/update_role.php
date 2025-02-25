<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$role = isset($_POST['role']) ? $_POST['role'] : '';

if ($user_id > 0 && in_array($role, ['member', 'coordinator', 'mentor'])) {
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $user_id);

    if ($stmt->execute()) {
        echo "User role updated successfully!";
    } else {
        echo "Failed to update role.";
    }

    $stmt->close();
} else {
    echo "Invalid input.";
}

$conn->close();
?>
