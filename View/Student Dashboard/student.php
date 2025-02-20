<?php
session_start();

// Correct condition using OR (||) to check if either condition fails
if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "member") {
    header("Location: ../../../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
    exit(); // Stop further execution after redirection
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard Page</title>
    <link rel="stylesheet" href="../../View/Student Dashboard/student.css">
</head>

<body>
    <header>
        <div class="logo">Uni Name</div>
        <div class="user">
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
    <main>
        <section class="top-section">
            <div class="event-announcements">Event Announcements</div>
            <div class="leaderboard">Leaderboard</div>
        </section>
        <div class="carousel">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
        <section class="previous-challenges">
            <h2>PREVIOUS CHALLENGES</h2>
            <div class="challenges-container">
                <div class="challenge">
                    <div class="poster">Event Poster</div>
                    <div class="details">
                        <p><strong>Event Name</strong></p>
                        <p>Date</p>
                        <p><em>No of Participation</em></p>
                    </div>
                </div>
                <div class="challenge">
                    <div class="poster">Event Poster</div>
                    <div class="details">
                        <p><strong>Event Name</strong></p>
                        <p>Date</p>
                        <p><em>No of Participation</em></p>
                    </div>
                </div>
                <div class="challenge">
                    <div class="poster">Event Poster</div>
                    <div class="details">
                        <p><strong>Event Name</strong></p>
                        <p>Date</p>
                        <p><em>No of Participation</em></p>
                    </div>
                </div>
                <div class="challenge">
                    <div class="poster">Event Poster</div>
                    <div class="details">
                        <p><strong>Event Name</strong></p>
                        <p>Date</p>
                        <p><em>No of Participation</em></p>
                    </div>
                </div>

            </div>
        </section>
    </main>
    <script src="../../View/Student Dashboard/student.js"></script>
</body>

</html>