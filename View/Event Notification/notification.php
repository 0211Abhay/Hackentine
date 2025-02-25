<?php
// Database Connection
$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "event_management";

$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Pending Events
$sql = "SELECT events.id, events.title, events.event_type, events.created_by, 
               universities.name AS university_name 
        FROM events 
        LEFT JOIN universities ON events.university_id = universities.id
        WHERE events.status = 'pending'";
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
            <div class="user-info">
                <img src="../../../Hackentine/Modules/Assets/Images/Admin.jpg" alt="User Image" class="user-image">
                <?php
                session_start();
                    if (isset($_SESSION['first_name']) && !empty($_SESSION['first_name'])) {
                        echo htmlspecialchars($_SESSION['first_name']); 
                    }

                    else {
                        echo "Guest";
                    }
                ?>
                <button type="button" onclick="window.location.href='../../../Hackentine/Modules/Authentication_&_Authorization/View/Logout/Logout.php'" class="logout-btn">Logout</button>
            </div>
        </header>

    <div class="container">
        <!-- Event List -->
        <main>
            <?php if (!empty($pendingEvents)) : ?>
                <?php foreach ($pendingEvents as $event) : ?>
                    <div class="event-card">
                        <div class="event-info">
                            <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                            <p>University: <?php echo htmlspecialchars($event['university_name'] ?? 'N/A'); ?></p>
                            <p>Created By: <?php echo htmlspecialchars($event['created_by']); ?></p>
                            <p>Event Type: <?php echo htmlspecialchars($event['event_type']); ?></p>
                        </div>
                        <div class="event-actions">
                            <a href="../../../Hackentine/Modules/Event Page/View/event.php?id=<?php echo $event['id']; ?>">
                                <button class="view-btn">View</button>
                            </a>
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