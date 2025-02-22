<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
require '../../Includes/db_connect.php'; // Database connection
require '../../../vendor/autoload.php'; // Load PHPMailer

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    die("Invalid request method.");
}

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Valid Event ID is required.");
}

$event_id = intval($_GET['event_id']);

// Fetch event details
$eventQuery = $conn->prepare("SELECT title, start_date FROM events WHERE id = :event_id");
$eventQuery->execute(['event_id' => $event_id]);
$event = $eventQuery->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Invalid event ID.");
}

$eventTitle = strtoupper($event['title']);
$eventStartDate = date("d M Y", strtotime($event['start_date']));

// Fetch registered users
$query = $conn->prepare("SELECT u.first_name, u.last_name, u.email FROM users u 
                         JOIN event_registrations er ON u.id = er.user_id 
                         WHERE er.event_id = :event_id");
$query->execute(['event_id' => $event_id]);
$registrations = $query->fetchAll(PDO::FETCH_ASSOC);

if (empty($registrations)) {
    die("No registrations found for this event.");
}

// Ensure certificates directory exists
$certDir = "./certificates/";
if (!is_dir($certDir)) {
    mkdir($certDir, 0777, true);
}

// Load certificate template
$templatePath = './Certificate.png';
$fontPath = './TIMES.TTF'; // Ensure this file exists
$fontSize = 75;

if (!file_exists($templatePath)) {
    die("Certificate template not found.");
}

if (!file_exists($fontPath)) {
    die("Font file not found. Please check the path.");
}

foreach ($registrations as $row) {
    $name = strtoupper(trim($row['first_name'] . " " . $row['last_name']));
    $email = trim($row['email']);

    // Create image from template
    $image = imagecreatefrompng($templatePath);
    $color = imagecolorallocate($image, 255, 255, 255); // White color

    // Get image dimensions
    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);

    // Calculate text bounding box
    $textBox = imagettfbbox($fontSize, 0, $fontPath, $name);
    if (!$textBox) {
        die("Error loading font or rendering text.");
    }

    $textWidth = abs($textBox[4] - $textBox[0]);
    $textHeight = abs($textBox[5] - $textBox[1]);

    // Centering the name on the certificate
    $nameX = ($imageWidth - $textWidth) / 2;
    $nameY = ($imageHeight / 2) + ($textHeight / 4) + 40; // Adjust Y position as needed

    // Add text to certificate
    imagettftext($image, $fontSize, 0, $nameX, $nameY, $color, $fontPath, $name);

    // Save certificate
    $certificatePath = $certDir . str_replace(" ", "_", $name) . ".png";
    imagepng($image, $certificatePath);
    imagedestroy($image);

    // Send email with certificate
    sendCertificateEmail($email, $name, $certificatePath, $eventTitle);
}

if (isset($_SESSION['id'])) {
    switch ($_SESSION['role']) {
        case 'mentor':
            header('Location: ../../../../../../Hackentine/View/10X Mentor/mentor.php');
            exit();
        case 'coordinator':
            header('Location: ../../../../../../../Hackentine/Modules/Club Coordinator/View/core.php');
            exit();
        case 'member':
            header('Location: ../../../../../../../Hackentine/View/Student Dashboard/student.php');
            exit();
        default:
            // Redirect to a default page if role is unknown
            header('Location: ../../../../../../../Hackentine/View/default.php');
            exit();
    }
}

/**
 * Function to send the certificate via email
 */
function sendCertificateEmail($toEmail, $recipientName, $certificatePath, $eventTitle) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aryan.langhanoja119561@marwadiuniversity.ac.in'; // Your SMTP email
        $mail->Password = 'cclq ktox eqlz ljdx'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('aryan.langhanoja119561@marwadiuniversity.ac.in', 'Coding Ninjas 10X Club');
        $mail->addAddress($toEmail, $recipientName);
        $mail->addAttachment($certificatePath);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Certificate of Participation - $eventTitle";
        $mail->Body = "Dear $recipientName,<br><br>Congratulations!🥳🥳 Please find attached your certificate for participating in <b>$eventTitle</b>.<br><br>Best Regards,<br>Coding Ninjas 10X Club";

        $mail->send();
        echo "Certificate sent to: $toEmail<br>";
    } catch (Exception $e) {
        echo "Failed to send to $toEmail: {$mail->ErrorInfo}<br>";
    }
}
?>
