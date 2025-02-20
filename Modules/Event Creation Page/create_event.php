<?php
include("../Includes/db_connect.php");

session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])  || !isset($_SESSION['role'])) {
    die("User not logged in, or role not found.");
}

$created_by = $_SESSION['id'];
$university_id = $_SESSION['university_id'];
$user_role = $_SESSION['role']; // Either 'mentor' or 'coordinator'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_type = $_POST['event_type'];
    $timeline_description = $_POST['timeline_description'] ?? null;
    $rules = $_POST['rules'] ?? null;
    $rewards = $_POST['rewards'] ?? null;
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    if($_POST["access_type"]== "open-for-all" && $user_role == "mentor"){
        $university_id = NULL;
    }

    if($_POST["access_type"]== "university-specific" && $user_role == "mentor"){
        $university_id = $_POST["university_id"];
    }
    // Handle File Upload
    $poster = null;
    if (!empty($_FILES['poster']['name'])) {
        $target_dir = "uploads/";
        $poster = $target_dir . basename($_FILES["poster"]["name"]);
        move_uploaded_file($_FILES["poster"]["tmp_name"], $poster);
    }

    try {
        $stmt = $conn->prepare("INSERT INTO events 
            (title, description, event_type, university_id, created_by, status, start_date, end_date, timeline_description, rules, rewards, poster)
            VALUES (:title, :description, :event_type, :university_id, :created_by, 'pending', :start_date, :end_date, :timeline_description, :rules, :rewards, :poster)");
        
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':event_type' => $event_type,
            ':university_id' => $university_id,
            ':created_by' => $created_by,
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':timeline_description' => $timeline_description,
            ':rules' => $rules,
            ':rewards' => $rewards,
            ':poster' => $poster
        ]);

        echo "Event created successfully!";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
