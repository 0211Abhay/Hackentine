<?php
session_start();
require_once '../../../../Hackentine/Modules/Includes/db_connect.php';

if (!isset($_SESSION['first_name']) || !isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    header("Location: ../Authentication_&_Authorization/View/Login/login.php");
    exit();
}

// Get logged-in user's role
$user_role = $_SESSION['role'];
$user_id = $_SESSION['id'];

// Ensure an event ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Event ID");
}

$event_id = $_GET['id'];

try {
    // Fetch event details
    $query = "SELECT * FROM events WHERE id = :event_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }

    // Check if the user has already registered
    $is_registered = false;
    if ($user_role === 'member') {
        $checkQuery = "SELECT * FROM event_registrations WHERE event_id = :event_id AND user_id = :user_id";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->execute();
        $is_registered = $checkStmt->rowCount() > 0;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?></title>
    <link rel="stylesheet" href="event.css">
</head>

<body>
<header>
    <div class="logo-container">
        <img src="../../../resources/img/10x Mini.png" alt="10X Club Logo">
        <button class="logout-btn" onclick="redirectToDashboard()">Back</button>
    </div>
    <div class="user-info">
        <img src="../../../Modules/Assets/Images/Admin.jpg" alt="User Image" class="user-image">
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


    <div class="container">
        <div class="event-poster">
            <img src="../../Event Creation Page/uploads/<?php echo htmlspecialchars($event['poster']); ?>"
                 onerror="this.onerror=null; this.src='../../resources/event_posters/default.jpg';"
                 alt="Event Poster">
        </div>

        <div class="content">
            <div class="announcement">
                <h3>Announcement</h3>
                <h2><?php echo htmlspecialchars($event['title']); ?></h2> <!-- Display Event Name -->
                <?php
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'registered') {
        echo "<p style='color: green;'>Successfully registered!</p>";
    } elseif ($_GET['msg'] == 'already_registered') {
        echo "<p style='color: red;'>You have already registered for this event.</p>";
    }
}
?>

                <p>&rarr; <b>Description</b>: <?php echo htmlspecialchars($event['description']); ?></p>
                <p>&rarr; <b>Timelines</b>: <?php echo htmlspecialchars(date('d M Y', strtotime($event['start_date']))); ?> - <?php echo htmlspecialchars(date('d M Y', strtotime($event['end_date']))); ?></p>
                <p>&rarr; <b>Eligibility</b>: Every Undergraduate student is eligible.</p>
                <p>&rarr; <b>Rules</b>: <?php echo htmlspecialchars($event['rules']); ?></p>
                <p>&rarr; <b>Rewards</b>: <?php echo htmlspecialchars($event['rewards']); ?></p>
            </div>

            <div class="register-box">
                <?php if ($user_role === 'member'): ?>
                    <?php if ($is_registered): ?>
                        <button disabled>Already Registered</button>
                    <?php else: ?>
                        <form action="register_event.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                            <button type="submit">Register</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="view_participants.php?event_id=<?php echo $event_id; ?>"><button>View Participants</button></a>
                    <br>
                    <br>
                    <a href="./generate.php?event_id=<?php echo $event_id; ?>"><button>Generate Certificate</button></a>
                <?php endif; ?>
                
                <p>Deadline: <?php echo htmlspecialchars(date('d M Y', strtotime($event['end_date']))); ?></p>
            </div>
        </div>
    </div>
    <script>
    function redirectToDashboard() {
        <?php if (isset($_SESSION['id'])): ?>
            switch ("<?php echo $_SESSION['role']; ?>") {
                case 'mentor':
                    window.location.href = '../../../../../../Hackentine/View/10X Mentor/mentor.php';
                    break;
                case 'coordinator':
                    window.location.href = '../../../../../../../Hackentine/Modules/Club Coordinator/View/core.php';
                    break;
                case 'member':
                    window.location.href = '../../../../../../../Hackentine/View/Student Dashboard/student.php';
                    break;
                default:
                    window.location.href = '../../../../../../../Hackentine/View/default.php';
            }
        <?php else: ?>
            window.location.href = '../../../../../../../Hackentine/View/default.php';
        <?php endif; ?>
    }
    </script>
</body>

</html>
