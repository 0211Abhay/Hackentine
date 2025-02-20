<?php
session_start();
require_once '../../../../Hackentine/Modules/Includes/db_connect.php';

if (!isset($_SESSION['first_name'])) {
    header("Location: ../Authentication_&_Authorization/View/Login/login.php");
    exit();
}

// Ensure an event ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Event ID");
}

$event_id = $_GET['id'];

try {
    // Fetch event details
    $query = "SELECT * FROM events WHERE id = :event_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?></title>
    <link rel="stylesheet" href="event.css">
</head>

<body>
    <div class="container">
        <div class="event-poster">


            <img src="../../Event Creation Page/uploads/<?php echo htmlspecialchars($event['poster']); ?>"
                 onerror="this.onerror=null; this.src='../../resources/event_posters/default.jpg';"
                 alt="Event Poster">
        </div>
        <div class="content">
            <div class="announcement">
                <h3>Announcement</h3>
                <p>&rarr; <?php echo htmlspecialchars($event['description']); ?></p>
                <p>&rarr; <b>Timelines</b>: <?php echo htmlspecialchars(date('d M Y', strtotime($event['start_date']))); ?> - <?php echo htmlspecialchars(date('d M Y', strtotime($event['end_date']))); ?></p>
                <p>&rarr; <b>Eligibility</b>: Every Under Graduate student will eligible</p>
                <p>&rarr; <b>Rules</b>: <?php echo htmlspecialchars($event['rules']); ?></p>
                <p>&rarr; <b>Rewards</b>: <?php echo htmlspecialchars($event['rewards']); ?></p>
            </div>
            <div class="register-box">
                <button>Register</button>
                <p>Deadline: <?php echo htmlspecialchars(date('d M Y', strtotime($event['end_date']))); ?></p>
            </div>
        </div>
    </div>
</body>

</html>
