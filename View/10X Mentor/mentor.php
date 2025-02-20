<?php
session_start();

if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "mentor") {
    header("Location: ../../../../Modules/Authentication_&_Authorization/View/Login/login.php");
    exit(); // Always exit after header redirection
}


// Database connection
$servername = "localhost:3306";
$username = "root"; // Change this if necessary
$password = ""; // Change this if necessary
$dbname = "event_management"; // Change this to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 10X Chapters Data (Universities, Member Count, Event Count)
$chapters_query = "
    SELECT u.id, u.name, 
           (SELECT COUNT(*) FROM users WHERE university_id = u.id) AS total_members, 
           (SELECT COUNT(*) FROM events WHERE university_id = u.id) AS total_events
    FROM universities u
";
$chapters_result = $conn->query($chapters_query);

// Fetch Events Data with University Names
$events_query = "
    SELECT e.id, e.title, e.event_type, u.name AS university_name, COUNT(*) AS total_participations 
    FROM events e 
    LEFT JOIN universities u ON e.university_id = u.id 
    GROUP BY e.id";
$events_result = $conn->query($events_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>10X Mentor Page</title>
    <link rel="stylesheet" href="../../View/10X Mentor/mentor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"/>
</head>

<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <a href="../../../Hackentine/Modules/Event Creation Page/event.php">
            <button class="create-event">Create an Event</button>
        </a>
        <div class="user-info">
            <img src="../../Modules/Assets/Images/Admin.jpg" alt="User Image" class="user-image">
            <?php
                if (isset($_SESSION['first_name']) && !empty($_SESSION['first_name'])) {
                    echo htmlspecialchars($_SESSION['first_name']); 
                }

                else {
                    echo "Guest";
                }
            ?>
        <button type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'" class="logout-btn">Logout</button>
    </div>
    </header>

    <div class="container">
        <div class="event-poster">Event Poster</div>
        <br>
        <div class="TableInput">
            <div>
                <button class="chapter-btn" id="show-chapters">10X Chapters</button>
                <button class="chapter-btn" id="show-events">Events</button>
            </div>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search...">
            </div>
        </div>
        <br>

        <!-- 10X Chapters Table -->
        <table class="event-table" id="chapter-table">
            <thead>
                <tr>
                    <th>University Name</th>
                    <th>No Of Members</th>
                    <th>No Of Events</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($chapters_result->num_rows > 0) {
                    while ($row = $chapters_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . $row["total_members"] . "</td>";
                        echo "<td>" . $row["total_events"] . "</td>";
                        echo "<td><a href='../10X Chapter/chapter.php?uni_id=" . $row["id"] . "'>
                                <button class='view-details'>View Details</button>
                            </a></td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No universities found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Events Table (Initially Hidden) -->
        <table class="event-table" id="event-table" style="display: none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Event Type</th>
                    <th>Hosted By</th>
                    <th>Total Participations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($events_result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $events_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $counter++ . "</td>";
                        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["event_type"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["university_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["total_participations"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No events found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="../../View/10X Mentor/mentor.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buttons = document.querySelectorAll(".view-details");

            buttons.forEach(button => {
                button.addEventListener("click", function () {
                    const universityId = this.getAttribute("data-university-id");
                    if (universityId) {
                        window.location.href = `../../10X Chapter/chapter.php?university_id=${universityId}`;
                    }
                });
            });
        });
    </script>

</body>

</html>

<?php
$conn->close();
?>
