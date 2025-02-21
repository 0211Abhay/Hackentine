<?php
session_start();

// Redirect if user is not logged in or not a member
if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "member") {
    header("Location: ../../../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
    exit();
}

include "../../Modules/Includes/db_connect.php";

$university_id = $_SESSION['university_id'] ?? null;

// Fetch Approved Past Events (Previous Challenges)
$sql_previous = "SELECT e.id, e.title, e.start_date, e.end_date, e.poster, 
                        u.name AS university_name 
                 FROM events e
                 LEFT JOIN universities u ON e.university_id = u.id
                 WHERE e.status = 'approved' 
                 AND (e.university_id = :university_id OR e.event_type = 'open for all')
                 AND e.end_date < CURDATE()
                 ORDER BY e.start_date DESC";

$stmt_previous = $conn->prepare($sql_previous);
$stmt_previous->bindParam(':university_id', $university_id, PDO::PARAM_INT);
$stmt_previous->execute();
$previous_events = $stmt_previous->fetchAll(PDO::FETCH_ASSOC);

// Fetch Approved Future Events (Upcoming Events)
$sql_upcoming = "SELECT e.id, e.title, e.start_date, e.end_date, e.poster, 
                        u.name AS university_name 
                 FROM events e
                 LEFT JOIN universities u ON e.university_id = u.id
                 WHERE e.status = 'approved' 
                 AND (e.university_id = :university_id OR e.event_type = 'open for all')
                 AND e.start_date >= CURDATE()
                 ORDER BY e.start_date ASC";

$stmt_upcoming = $conn->prepare($sql_upcoming);
$stmt_upcoming->bindParam(':university_id', $university_id, PDO::PARAM_INT);
$stmt_upcoming->execute();
$upcoming_events = $stmt_upcoming->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard Page</title>
    <link rel="stylesheet" href="../../View/Student Dashboard/student.css">
</head>

<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <div class="user-info">
            <img src="../../Modules/Assets/Images/Admin.jpg" alt="User Image" class="user-image">
            <?php
                echo htmlspecialchars($_SESSION['first_name'] ?? "Guest");

                if (isset($_SESSION['university_id']) && !empty($_SESSION['university_id'])) {
                    $uni_id = $_SESSION['university_id'];
                    
                    // Fetch university name
                    $stmt = $conn->prepare("SELECT name FROM universities WHERE id = :uni_id");
                    $stmt->bindValue(':uni_id', $uni_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $uni_name = $stmt->fetchColumn();
                    
                    echo "<p>" . htmlspecialchars($uni_name ?: "University Not Found") . "</p>";
                } else {
                    echo "<p>No University Found</p>";
                }
            ?>
            <button type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'" class="logout-btn">Logout</button>
        </div>
    </header>

    <main>
        <!-- Event Carousel Section -->
        <div class="carousel-container">
            <div class="carousel">
                <!-- You can add dynamic sliders here later -->
            </div>
        </div>

        <!-- Upcoming Events Section -->
<section class="upcoming-events">
    <h2>UPCOMING EVENTS</h2>
    <div class="challenges-container">
        <?php if (!empty($upcoming_events)) : ?>
            <?php foreach ($upcoming_events as $event) : ?>
                <a href="../../../Hackentine/Modules/Event Page/View/event.php?id=<?php echo $event['id']; ?>" class="event-link">
                    <div class="challenge">
                        <div class="poster">
                            <img src="<?php echo htmlspecialchars($event['poster'] ?: '../../resources/img/default_poster.jpg'); ?>" alt="Event Poster">
                        </div>
                        <div class="details">
                            <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                            <p><?php echo htmlspecialchars($event['start_date']) . " - " . htmlspecialchars($event['end_date']); ?></p>
                            <p><em>University: <?php echo htmlspecialchars($event['university_name'] ?? 'Unknown'); ?></em></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No upcoming events found.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Previous Challenges Section -->
<section class="previous-challenges">
    <h2>PREVIOUS CHALLENGES</h2>
    <div class="challenges-container">
        <?php if (!empty($previous_events)) : ?>
            <?php foreach ($previous_events as $event) : ?>
                <a href="../../../Hackentine/Modules/Event Page/View/event.php?id=<?php echo $event['id']; ?>" class="event-link">
                    <div class="challenge">
                        <div class="poster">
                            <img src="<?php echo htmlspecialchars($event['poster'] ?: '../../resources/img/default_poster.jpg'); ?>" alt="Event Poster">
                        </div>
                        <div class="details">
                            <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                            <p><?php echo htmlspecialchars($event['start_date']) . " - " . htmlspecialchars($event['end_date']); ?></p>
                            <p><em>University: <?php echo htmlspecialchars($event['university_name'] ?? 'Unknown'); ?></em></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No previous events found.</p>
        <?php endif; ?>
    </div>
</section>

    </main>

    <script src="../../View/Student Dashboard/student.js"></script>
</body>

</html>
