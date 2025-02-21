<?php
session_start();
require_once '../../../../Hackentine/Modules/Includes/db_connect.php';

// Ensure the user is logged in as a coordinator
if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "coordinator") {
    header("Location: ../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
    exit();
}

$university_id = $_SESSION['university_id'];

try {
    // Fetch Upcoming Events
    $queryUpcoming = "SELECT id, title, start_date, poster 
                      FROM events 
                      WHERE university_id = :university_id 
                      AND start_date >= CURDATE() 
                      ORDER BY start_date ASC";
    
    $stmtUpcoming = $conn->prepare($queryUpcoming);
    $stmtUpcoming->bindParam(':university_id', $university_id, PDO::PARAM_INT);
    $stmtUpcoming->execute();
    $upcomingEvents = $stmtUpcoming->fetchAll(PDO::FETCH_ASSOC);

    // Fetch Previous Events
    $queryPrevious = "SELECT id, title, end_date, poster 
                      FROM events 
                      WHERE university_id = :university_id 
                      AND end_date < CURDATE() 
                      ORDER BY end_date DESC";

    $stmtPrevious = $conn->prepare($queryPrevious);
    $stmtPrevious->bindParam(':university_id', $university_id, PDO::PARAM_INT);
    $stmtPrevious->execute();
    $previousEvents = $stmtPrevious->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Page</title>
    <link rel="stylesheet" href="../../../../Hackentine/Modules/Club Coordinator/View/core.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <a href="../../Modules/Event Creation Page/event.php">
            <button class="create-event">Create an Event</button>
        </a>
        <div class="user-info">
            <?php echo htmlspecialchars($_SESSION['first_name']); ?>
            <button class="logout-btn" type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'">Logout</button>
        </div>
    </header>

    <section class="mentors">
        <div class="mentor-card">
            <!-- <div class="mentor-img"> -->
            <img src="../../../../Hackentine/resources/Pooja Rathore.jpeg"/>
            <!-- </div> -->
            <div>
            <strong><p class="mentor-name">10x Mentor</p></strong>
            <p>Pooja Rathod</p>
            </div>
        </div>
    </section>

    <section class="challenges">
    <h2>Upcoming Challenges</h2>
    <div class="challenge-list">
    <?php if (!empty($upcomingEvents)): ?>
        <?php foreach ($upcomingEvents as $event): ?>
            <a href="../../../Hackentine/Modules/Event Page/View/event.php?id=<?php echo $event['id']; ?>" class="challenge-link">
                <div class="challenge">
                    <div class="poster">
                        <img src="../../resources/event_posters/<?php echo htmlspecialchars($event['poster']); ?>"
                             onerror="this.onerror=null; this.src='../../resources/event_posters/default.jpg';"
                             alt="Event Poster">
                    </div>
                    <div class="details">
                        <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                        <p><?php echo htmlspecialchars(date('d M Y', strtotime($event['start_date']))); ?></p>
                        <p>Participants: TBD</p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No upcoming events found.</p>
    <?php endif; ?>
</div>
</section>

<section class="challenges">
    <h2>Previous Challenges</h2>
    <div class="challenge-list">
        <?php if (!empty($previousEvents)): ?>
            <?php foreach ($previousEvents as $event): ?>
                <a href="../../../Hackentine/Modules/Event Page/View/event.php?id=<?php echo $event['id']; ?>" class="challenge-link">
                    <div class="challenge">
                        <div class="poster">
                            <img src="../../resources/event_posters/<?php echo htmlspecialchars($event['poster']); ?>"
                                 onerror="this.onerror=null; this.src='../../resources/event_posters/default.jpg';"
                                 alt="Event Poster">
                        </div>
                        <div class="details">
                            <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                            <p><?php echo htmlspecialchars(date('d M Y', strtotime($event['end_date']))); ?></p>
                            <p>Participants: TBD</p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No previous events found.</p>
        <?php endif; ?>
    </div>
</section>

    <script src="../../../../Hackentine/Modules/Club Coordinator/View/core.js"></script>
</body>

</html>
