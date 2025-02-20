<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get university ID from GET request
$uni_id = isset($_GET['uni_id']) ? intval($_GET['uni_id']) : 0;

// Fetch events related to the university
$sql_events = "
    SELECT e.id, e.title, e.start_date, e.end_date, 
           COALESCE(es.total_registrations, 0) AS total_participation
    FROM events e
    LEFT JOIN event_statistics es ON e.id = es.event_id
    WHERE e.university_id = $uni_id"; // Filter by university_id

$result_events = $conn->query($sql_events);

// Fetch members related to the university
$sql_members = "SELECT id, first_name, last_name, role FROM users WHERE university_id = $uni_id"; // Filter by university_id

$result_members = $conn->query($sql_members);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>10X Mentor Page</title>
    <link rel="stylesheet" href="../../View/10X Chapter/chapter.css">
</head>
<body>
    <header>
        <div class="logo"><img src="../../resources/img/10x Mini.png" alt="10X Club Logo"></div>
        <a href="../Event Creation Page/event.html">
            <button class="create-event">Create an Event</button>
        </a>

        <div class="user-info">Username</div>
        <div>
            <?php
            if ($uni_id > 0) {
                // echo "<p style='color:white;'>University ID: " . htmlspecialchars($uni_id) . "</p>";
            } else {
                // echo "<p style='color:white;'>No University Selected</p>";
            }
            ?>
        </div>
    </header>

    <div class="container">
        <div class="event-poster">Event Poster</div>
        <br>
        <div class="TableInput">
            <div class="statistics">
                <button class="toggle-btn" id="show-events">Total Events</button>
                <button class="toggle-btn" id="show-members">10X Members</button>
            </div>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search...">
            </div>
        </div>
        <br>

        <!-- Event Table -->
        <table class="event-table" id="event-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event Name</th>
                    <th>Total Participation</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_events->num_rows > 0): ?>
                    <?php while ($row = $result_events->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo $row['total_participation']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No events found for this university</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Member Table (Hidden Initially) -->
        <table class="event-table" id="member-table" style="display: none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_members->num_rows > 0): ?>
                    <?php while ($row = $result_members->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                            <td>
                                <select class="role-dropdown" data-id="<?php echo $row['id']; ?>">
                                    <option value="member" <?php echo ($row['role'] == 'member') ? 'selected' : ''; ?>>Member</option>
                                    <option value="coordinator" <?php echo ($row['role'] == 'coordinator') ? 'selected' : ''; ?>>Coordinator</option>
                                    <option value="mentor" <?php echo ($row['role'] == 'mentor') ? 'selected' : ''; ?>>Mentor</option>
                                </select>
                            </td>
                            <td><button class="save-btn" data-id="<?php echo $row['id']; ?>">Save</button></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No members found for this university</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById("show-events").addEventListener("click", function() {
            document.getElementById("event-table").style.display = "table";
            document.getElementById("member-table").style.display = "none";
        });

        document.getElementById("show-members").addEventListener("click", function() {
            document.getElementById("event-table").style.display = "none";
            document.getElementById("member-table").style.display = "table";
        });

        // Update role on Save button click
        document.querySelectorAll(".save-btn").forEach(button => {
            button.addEventListener("click", function() {
                let userId = this.getAttribute("data-id");
                let selectedRole = document.querySelector(`select[data-id='${userId}']`).value;

                fetch("update_role.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${userId}&role=${selectedRole}`
                })
                .then(response => response.text())
                .then(data => alert(data))
                .catch(error => console.error("Error:", error));
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
