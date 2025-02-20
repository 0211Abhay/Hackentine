<?php
session_start();

if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || !$_SESSION['role']) {
    header("Location: ../../../../../Hackentine/Modules/Authentication_&_Authorization/View/Login/login.php");
    exit(); // Always exit after header redirection
}

$isMentor = ($_SESSION['role'] == "mentor");


require_once '../Includes/db_connect.php';
require_once '../Event Creation Page/University.php';



// Fetch all universities
$universities = getAllUniversities();

// For debugging
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Creation Page</title>
    <link rel="stylesheet" href="event.css" />
</head>

<body>
<script src="event.js"></script>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="../../resources/img/10X Logo.jpg" alt="Logo">
                <span>Uni Name</span>
            </div>
            <span>
            <?php
        if (isset($_SESSION['first_name']) && !empty($_SESSION['first_name'])) {
                echo htmlspecialchars($_SESSION['first_name']); 
            } else {
                echo "Guest";
            }
            ?>
             <button type="button" onclick="window.location.href='../../Modules/Authentication_&_Authorization/View/Logout/Logout.php'">Logout</button>
            </span>
        </div>
        <form id="eventForm" action="./create_event.php" method="POST" enctype="multipart/form-data">
            <div class="left-right">
                <div class="left">
                    <label>Event Name</label>
                    <input type="text" name="title" placeholder="Enter event name" required>

                    <label>Description</label>
                    <textarea name="description" placeholder="Enter event description" required></textarea>

                    <label>Timeline</label>
                    <input type="text" name="timeline_description" placeholder="Enter event timeline">

                    <label>Rules</label>
                    <input type="text" name="rules" placeholder="Enter event rules">

                    <label>Rewards</label>
                    <input type="text" name="rewards" placeholder="Enter event rewards">
                </div>
                <div class="right">
                    <label>Upload Poster</label>
                    <input type="file" name="poster">

                    <div class="event-type">
                    <label>Type of Event</label>
                    <label><input type="radio" name="event_type" value="Hackathon"> Hackathon</label>
                    <label><input type="radio" name="event_type" value="Coding Challenge"> Coding Challenge</label>
                    <label><input type="radio" name="event_type" value="Other" id="otherType"> Other</label>
                    <input type="text" id="customTypeInput" placeholder="Custom Type" style="display:none;">


                    <?php if ($isMentor): ?>
                        <div class="event-access">
    <label>Access Type</label>
    <label>
        <input type="radio" name="access_type" value="university-specific" checked onclick="toggleUniversityDropdown()"> 
        University-Specific
    </label>
    <label>
        <input type="radio" name="access_type" value="open-for-all" onclick="toggleUniversityDropdown()"> 
        Open-for-All
    </label>

    <!-- University Dropdown -->
    <div id="universityDropdown" style="display: block;">
        <label>Select University</label>
        <select name="university_id">
            <option value="">Select University</option>
            <?php foreach ($universities as $university): ?>
                <option value="<?php echo $university['id']; ?>">
                    <?php echo htmlspecialchars($university['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
                    <?php else: ?>
                        <input type="hidden" name="access_type" value="university-specific">
                    <?php endif; ?>
                </div>

                    <label>Start Date</label>
                    <input type="datetime-local" name="start_date" required>

                    <label>End Date</label>
                    <input type="datetime-local" name="end_date" required>
                </div>
            </div>
            <div class="submit-btn">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

</body>

</html>
