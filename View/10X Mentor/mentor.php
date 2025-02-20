<?php
session_start();

if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "mentor") {
    header("Location: ../../../../Modules/Authentication_&_Authorization/View/Login/login.php");
    exit(); // Always exit after header redirection
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>10X Mentor Page</title>
    <link rel="stylesheet" href="../../View/10X Mentor/mentor.css">
</head>

<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <a href="../../../Hackentine/Modules/Event Creation Page/event.php">
            <button class="create-event">Create an Event</button>
        </a>
        <div class="user-info"><?php
        if (isset($_SESSION['first_name']) && !empty($_SESSION['first_name'])) {
                echo htmlspecialchars($_SESSION['first_name']); 
            } else {
                echo "Guest";
            }
            ?>
             <button type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'">Logout</button></div>
    </header>
    <div class="container">
        <div class="event-poster">Event Poster</div>
        <br>
        <div class="TableInput">
            <div>
                <button class="chapter-btn">10X Chapter</button>
                <button class="chapter-btn">Events</button>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
        </div>
        <br>
        <table class="event-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>University Name</th>
                    <th>No Of Members</th>
                    <th>No Of Events</th>
                    <th>Total Participations</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Marwadi University</td>
                    <td>200</td>
                    <td>10</td>
                    <td>1500</td>
                    <td><button class="view-details">View Details</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Marwadi University</td>
                    <td>200</td>
                    <td>10</td>
                    <td>1500</td>
                    <td><button class="view-details">View Details</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Marwadi University</td>
                    <td>200</td>
                    <td>10</td>
                    <td>1500</td>
                    <td><button class="view-details">View Details</button></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Marwadi University</td>
                    <td>200</td>
                    <td>10</td>
                    <td>1500</td>
                    <td><button class="view-details">View Details</button></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Marwadi University</td>
                    <td>200</td>
                    <td>10</td>
                    <td>1500</td>
                    <td><button class="view-details">View Details</button></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Marwadi University</td>
                    <td>200</td>
                    <td>10</td>
                    <td>1500</td>
                    <td><button class="view-details">View Details</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="../../View/10X Mentor/mentor.js"></script>
</body>

</html>