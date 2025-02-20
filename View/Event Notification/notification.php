<?php
// Database Connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "event_management";

$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Pending Events
$sql = "SELECT id, title, event_type, university_id, created_by FROM events WHERE status = 'pending'";
$result = $conn->query($sql);

$pendingEvents = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pendingEvents[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="../../View/Event Notification/notification.css">
</head>

<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <a href="../Event Creation Page/event.html">
            <button class="create-event">Create an Event</button>
        </a>
        <div class="user-info">Username</div>
    </header>

    <div class="container">
        <!-- Event List -->
        <main>
            <?php if (!empty($pendingEvents)) : ?>
                <?php foreach ($pendingEvents as $event) : ?>
                    <div class="event-card">
                        <div class="event-info">
                            <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                            <p>University: <?php echo htmlspecialchars($event['university_id']); ?></p>
                            <p>Created By: <?php echo htmlspecialchars($event['created_by']); ?></p>
                            <p>Event Type: <?php echo htmlspecialchars($event['event_type']); ?></p>
                        </div>
                        <div class="event-actions">
                            <a href="edit_event.php?id=<?php echo $event['id']; ?>"><button class="edit-btn">Edit</button></a>
                            <button class="approve-btn" onclick="updateStatus(<?php echo $event['id']; ?>, 'approved')">Approve</button>
                            <button class="reject-btn" onclick="updateStatus(<?php echo $event['id']; ?>, 'rejected')">Reject</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No pending events found.</p>
            <?php endif; ?>
        </main>
    </div>

    <script>
        function updateStatus(eventId, newStatus) {
            fetch('update_event_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: eventId, status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh page on success
                } else {
                    alert("Failed to update status.");
                }
            });
        }
    </script>

</body>

</html>
