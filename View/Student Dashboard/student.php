<?php
session_start();

// Redirect if user is not logged in or not a member
if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "member") {
    header("Location: ../../../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
    exit();
}

include "../../Modules/Includes/db_connect.php";

// Fetch Approved Events from the Database
$sql = "SELECT e.id, e.title, e.start_date, e.end_date, e.poster, 
               u.name AS university_name 
        FROM events e
        LEFT JOIN universities u ON e.university_id = u.id
        WHERE e.status = 'approved'
        ORDER BY e.start_date DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        <!-- Previous Challenges Section -->
        <section class="previous-challenges">
            <h2>PREVIOUS CHALLENGES</h2>
            <div class="challenges-container">
                <?php if (!empty($events)) : ?>
                    <?php foreach ($events as $event) : ?>
                        <div class="challenge">
                            <div class="poster">
                                <img src="<?php echo htmlspecialchars($event['poster'] ?: '../../resources/img/default_poster.jpg'); ?>" alt="Event Poster">
                            </div>
                            <div class="details">
                                <p><strong><?php echo htmlspecialchars($event['title']); ?></strong></p>
                                <p><?php echo htmlspecialchars($event['start_date']) . " - " . htmlspecialchars($event['end_date']); ?></p>
                                <p><em>University: <?php echo htmlspecialchars($event['university_name'] ?? 'Unknown'); ?></em></p>
                                <a href="../../../Hackentine/Modules/Event Creation Page/event.php?id=<?php echo $event['id']; ?>" class="view-event-btn">View Event</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No approved events found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="../../View/Student Dashboard/student.js"></script>
</body>

</html>
