<?php
session_start();

if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "mentor") {
    header("Location: ../../../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_university"])) {
    $university_name = trim($_POST["university_name"]);

    if (!empty($university_name)) {
        // Check if university already exists
        $check_stmt = $conn->prepare("SELECT id FROM universities WHERE name = ?");
        $check_stmt->bind_param("s", $university_name);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<script>alert('University already exists!');</script>";
        } else {
            // Insert only if it doesn't exist
            $stmt = $conn->prepare("INSERT INTO universities (name) VALUES (?)");
            $stmt->bind_param("s", $university_name);

            if ($stmt->execute()) {
                echo "<script>alert('University added successfully!'); window.location.href = window.location.href;</script>";
            } else {
                echo "<script>alert('Error adding university. Try again.');</script>";
            }

            $stmt->close();
        }

        $check_stmt->close();
    } else {
        echo "<script>alert('University name cannot be empty.');</script>";
    }
}


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

    <div class="carousel-container">
            <div class="carousel">
                <!-- <div class="slider">
                    <div class="slide-content">
                        <h1 class="movie-title">loki</h1>
                        <p class="movie-des">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit saepe eius ratione nostrum mollitia explicabo quae nam pariatur. Sint, odit?</p>
                    </div>
                    <img src="images/slider 1.PNG" alt="">
                </div> -->
            </div>
    </div>
    <div class="container">
        <!-- <div class="event-poster">Event Poster</div> -->
  
        <br>
        <div class="TableInput">
            <div>
                <button class="chapter-btn" id="show-chapters">10X Chapters</button>
                <button class="chapter-btn" id="show-events">Events</button>
                <button class="chapter-btn" id="add-university">Add University</button>
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
            <th>#</th>
            <th>University Name</th>
            <th>No Of Members</th>
            <th>No Of Events</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter = 1; // Initialize counter for numbering
        if ($chapters_result->num_rows > 0) {
            while ($row = $chapters_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>"; // Print incremented counter
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . $row["total_members"] . "</td>";
                echo "<td>" . $row["total_events"] . "</td>";
                echo "<td><a href='../10X Chapter/chapter.php?uni_id=" . $row["id"] . "'>
                        <button class='view-details'>View Details</button>
                    </a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No universities found</td></tr>";
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
        $counter = 1; // Reset counter for events table
        if ($events_result->num_rows > 0) {
            while ($row = $events_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>"; // Print incremented counter
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
<!-- Add University Form (Hidden by Default) -->
<div id="add-university-form" style="display: none;">
    <h3>Add a New University</h3>
    <form action="" method="POST">
        <label>University Name:</label>
        <input type="text" name="university_name" required>
        <button type="submit" name="submit_university">Submit</button>
    </form>
</div>

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
