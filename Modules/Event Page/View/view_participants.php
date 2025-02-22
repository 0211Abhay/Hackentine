<?php
session_start();
require_once '../../../../Hackentine/Modules/Includes/db_connect.php';

if (!isset($_SESSION['id']) || ($_SESSION['role'] != 'coordinator' && $_SESSION['role'] != 'mentor')) {
    die("Unauthorized access.");
}

// Ensure an event ID is provided
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Invalid Event ID");
}

$event_id = $_GET['event_id'];

try {
    // Fetch event details
    $eventQuery = "SELECT title FROM events WHERE id = :event_id";
    $eventStmt = $conn->prepare($eventQuery);
    $eventStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $eventStmt->execute();
    $event = $eventStmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }

    // Fetch registered participants
    $participantQuery = "
        SELECT users.id, users.first_name, users.last_name, users.email, users.mobile_no
        FROM event_registrations
        INNER JOIN users ON event_registrations.user_id = users.id
        WHERE event_registrations.event_id = :event_id
    ";
    $participantStmt = $conn->prepare($participantQuery);
    $participantStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $participantStmt->execute();
    $participants = $participantStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle Excel Download
if (isset($_GET['download']) && $_GET['download'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=participants_event_" . $event_id . ".xls");

    echo "Full Name\tEmail\tMobile\n";
    foreach ($participants as $participant) {
        echo htmlspecialchars($participant['first_name'] . ' ' . $participant['last_name']) . "\t" . 
             htmlspecialchars($participant['email']) . "\t" . 
             htmlspecialchars($participant['mobile_no']) . "\n";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participants - <?php echo htmlspecialchars($event['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Participants for <?php echo htmlspecialchars($event['title']); ?></h2>
        
        <?php if (count($participants) > 0): ?>
            <a href="?event_id=<?php echo $event_id; ?>&download=excel" class="download-btn">Download as Excel</a>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $index => $participant): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($participant['first_name'] . ' ' . $participant['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($participant['email']); ?></td>
                            <td><?php echo htmlspecialchars($participant['mobile_no']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No participants registered yet.</p>
        <?php endif; ?>

        <a href="event.php?id=<?php echo $event_id; ?>">Back to Event</a>
    </div>
</body>
</html>
