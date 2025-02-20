<?php
session_start();

if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "coordinator") {
    header("Location: ../Authentication_&_Authorization/View/Login/login.php");
    exit(); // Always exit after header redirection
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Page</title>
    <link rel="stylesheet" href="../../View/Club Coordinator/core.css">
</head>

<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <a href="../../Modules/Event Creation Page/event.php">
            <button class="create-event">Create an Event</button>
        </a>
        <div class="user-info">
        <?php
        if (isset($_SESSION['first_name']) && !empty($_SESSION['first_name'])) {
                echo htmlspecialchars($_SESSION['first_name']); 
            } else {
                echo "Guest";
            }
            ?>
             <button type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'">Logout</button>
        </div>
    </header>

    <section class="mentors">
        <div class="mentor-card">
            <div class="mentor-img"></div>
            <p class="mentor-name">10x Mentor</p>
            <p>Pooja Rathod</p>
        </div>
    </section>

    <section class="challenges">
        <h2>Upcoming Challenges</h2>
        <div class="challenge-list">
            <div class="challenge">
                <div class="poster">Event Poster</div>
                <div class="details">
                    <p><strong>Event Name</strong></p>
                    <p>Date</p>
                    <p>No of Participation</p>
                </div>
            </div>
            <div class="challenge">
                <div class="poster">Event Poster</div>
                <div class="details">
                    <p><strong>Event Name</strong></p>
                    <p>Date</p>
                    <p>No of Participation</p>
                </div>
            </div>
        </div>
    </section>

    <section class="challenges">
        <h2>Previous Challenges</h2>
        <div class="challenge-list">
            <div class="challenge">
                <div class="poster">Event Poster</div>
                <div class="details">
                    <p><strong>Event Name</strong></p>
                    <p>Date</p>
                    <p>No of Participation</p>
                </div>
            </div>
            <div class="challenge">
                <div class="poster">Event Poster</div>
                <div class="details">
                    <p><strong>Event Name</strong></p>
                    <p>Date</p>
                    <p>No of Participation</p>
                </div>
            </div>
            <div class="challenge">
                <div class="poster">Event Poster</div>
                <div class="details">
                    <p><strong>Event Name</strong></p>
                    <p>Date</p>
                    <p>No of Participation</p>
                </div>
            </div>
        </div>
    </section>

    <script src="../../View/Club Coordinator/core.js"></script>
</body>

</html>