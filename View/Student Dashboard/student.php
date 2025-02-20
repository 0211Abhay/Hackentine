<?php
session_start();

// Correct condition using OR (||) to check if either condition fails
if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || $_SESSION['role'] != "member") {
    header("Location: ../../../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
    exit(); // Stop further execution after redirection
}

include "../../Modules/Includes/db_connect.php";
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
            <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
            <div class="user-info">
                <img src="../../Modules/Assets/Images/Admin.jpg" alt="User Image" class="user-image">
                <?php


                    if (isset($_SESSION['first_name']) && !empty($_SESSION['first_name'])) {
                        echo htmlspecialchars($_SESSION['first_name']); 
                    }

                    else {
                        echo "Guest";
                    }

                    if (isset($_SESSION['university_id']) && !empty($_SESSION['university_id'])) {
                        $uni_id = $_SESSION['university_id'];
                    
                        // Prepare and execute query using PDO
                        $stmt = $conn->prepare("SELECT name FROM universities WHERE id = :uni_id");
                        $stmt->bindValue(':uni_id', $uni_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $uni_name = $stmt->fetchColumn();
                    
                        // Display university name if found
                        if ($uni_name) {
                            echo "<p>" . htmlspecialchars($uni_name) . "</p>";
                        } else {
                            echo "<p>University Not Found</p>";
                        }
                    }

                    else {
                        echo "No University Found";
                    }
                ?>
                <button type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'" class="logout-btn">Logout</button>
            </div>
            
        </header>
    <main>
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

        <!-- <div class="carousel">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div> -->
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