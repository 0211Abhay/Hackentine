<?php
include("../Includes/db_connect.php");

session_start();

// Check if user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    die("User not logged in, or role not found.");
}

$created_by = $_SESSION['id'];
$university_id = $_SESSION['university_id'];
$user_role = $_SESSION['role']; // Either 'mentor' or 'coordinator'

$event_id = null;
$title = $description = $event_type = $timeline_description = $rules = $rewards = $start_date = $end_date = $poster = "";
$access_type = "university-specific"; // Default value

// **Check if Editing an Existing Event**
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch event data
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($event = $result->fetch_assoc()) {
        $title = $event['title'];
        $description = $event['description'];
        $event_type = $event['event_type'];
        $timeline_description = $event['timeline_description'];
        $rules = $event['rules'];
        $rewards = $event['rewards'];
        $start_date = $event['start_date'];
        $end_date = $event['end_date'];
        $poster = $event['poster'];
        $university_id = $event['university_id'];
        
        // Determine access type
        $access_type = is_null($university_id) ? "open-for-all" : "university-specific";
    }
}

// **Handle Form Submission**
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_type = $_POST['event_type'];
    $timeline_description = $_POST['timeline_description'] ?? null;
    $rules = $_POST['rules'] ?? null;
    $rewards = $_POST['rewards'] ?? null;
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    // Determine university_id based on access type
    if ($_POST["access_type"] == "open-for-all" && $user_role == "mentor") {
        $university_id = NULL;
    } elseif ($_POST["access_type"] == "university-specific" && $user_role == "mentor") {
        $university_id = $_POST["university_id"];
    }

    // Handle File Upload
    if (!empty($_FILES['poster']['name'])) {
        $target_dir = "uploads/";
        $poster = $target_dir . basename($_FILES["poster"]["name"]);
        move_uploaded_file($_FILES["poster"]["tmp_name"], $poster);
    }

    try {
        if ($event_id) {
            // **UPDATE EXISTING EVENT**
            $stmt = $conn->prepare("UPDATE events SET 
            title = :title, description = :description, event_type = :event_type, university_id = :university_id, 
            start_date = :start_date, end_date = :end_date, timeline_description = :timeline_description, 
            rules = :rules, rewards = :rewards, poster = :poster WHERE id = :id");
        
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        $stmt->bindParam(":event_type", $event_type, PDO::PARAM_STR);
        $stmt->bindParam(":university_id", $university_id, PDO::PARAM_INT);
        $stmt->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        $stmt->bindParam(":end_date", $end_date, PDO::PARAM_STR);
        $stmt->bindParam(":timeline_description", $timeline_description, PDO::PARAM_STR);
        $stmt->bindParam(":rules", $rules, PDO::PARAM_STR);
        $stmt->bindParam(":rewards", $rewards, PDO::PARAM_STR);
        $stmt->bindParam(":poster", $poster, PDO::PARAM_STR);
        $stmt->bindParam(":id", $event_id, PDO::PARAM_INT);
        
        } else {
            // **CREATE NEW EVENT**
            $stmt = $conn->prepare("INSERT INTO events 
            (title, description, event_type, university_id, created_by, status, start_date, end_date, timeline_description, rules, rewards, poster) 
            VALUES (:title, :description, :event_type, :university_id, :created_by, 'pending', :start_date, :end_date, :timeline_description, :rules, :rewards, :poster)");
        
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        $stmt->bindParam(":event_type", $event_type, PDO::PARAM_STR);
        $stmt->bindParam(":university_id", $university_id, PDO::PARAM_INT);
        $stmt->bindParam(":created_by", $created_by, PDO::PARAM_INT);
        $stmt->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        $stmt->bindParam(":end_date", $end_date, PDO::PARAM_STR);
        $stmt->bindParam(":timeline_description", $timeline_description, PDO::PARAM_STR);
        $stmt->bindParam(":rules", $rules, PDO::PARAM_STR);
        $stmt->bindParam(":rewards", $rewards, PDO::PARAM_STR);
        $stmt->bindParam(":poster", $poster, PDO::PARAM_STR);
        
        }

        $stmt->execute();
        echo "Event " . ($event_id ? "updated" : "created") . " successfully!";
        header("Location: ../../../Hackentine/View/Club Coordinator/core.php");
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event_id ? "Edit Event" : "Create Event"; ?></title>
</head>
<body>
    <h2><?php echo $event_id ? "Edit Event" : "Create Event"; ?></h2>

    <form action="create_event.php<?php echo $event_id ? "?id=$event_id" : ""; ?>" method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br>

        <label>Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea><br>

        <label>Event Type:</label>
        <input type="text" name="event_type" value="<?php echo htmlspecialchars($event_type); ?>" required><br>

        <label>Start Date:</label>
        <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required><br>

        <label>End Date:</label>
        <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required><br>

        <label>Timeline Description:</label>
        <textarea name="timeline_description"><?php echo htmlspecialchars($timeline_description); ?></textarea><br>

        <label>Rules:</label>
        <textarea name="rules"><?php echo htmlspecialchars($rules); ?></textarea><br>

        <label>Rewards:</label>
        <textarea name="rewards"><?php echo htmlspecialchars($rewards); ?></textarea><br>

        <label>Access Type:</label>
        <select name="access_type">
            <option value="university-specific" <?php echo ($access_type == "university-specific") ? "selected" : ""; ?>>University-Specific</option>
            <option value="open-for-all" <?php echo ($access_type == "open-for-all") ? "selected" : ""; ?>>Open for All</option>
        </select><br>

        <label>Upload Poster:</label>
        <input type="file" name="poster"><br>
        <?php if ($poster) : ?>
            <img src="<?php echo $poster; ?>" alt="Current Poster" width="100"><br>
        <?php endif; ?>

        <button type="submit"><?php echo $event_id ? "Update Event" : "Create Event"; ?></button>
    </form>

</body>
</html>
