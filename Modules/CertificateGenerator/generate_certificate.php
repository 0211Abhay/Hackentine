<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../Includes/db_connect.php'; // Database connection
require '../../vendor/autoload.php'; // Load PHPMailer

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request method.");
}

if (!isset($_POST['event_id'])) {
    die("Event ID is required.");
}

$event_id = intval($_POST['event_id']);

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

// Load certificate template
$templatePath = './Certificate.png';
$fontPath = './TIMES.TTF'; // Ensure font file exists
$fontSize = 75;

foreach ($registrations as $row) {
    $name = strtoupper($row['first_name'] . " " . $row['last_name']);
    $email = $row['email'];

    // Create image from template
    $image = imagecreatefrompng($templatePath);
    $color = imagecolorallocate($image, 255, 255, 255);
    
    // Get image dimensions
    $imageWidth = imagesx($image);

    // Calculate text bounding box
    $textBox = imagettfbbox($fontSize, 0, $fontPath, $name);
    $textWidth = $textBox[2] - $textBox[0];
    
    // Calculate centered position
    $nameX = ($imageWidth - $textWidth) / 2;
    $nameY = 765; // Keep Y position fixed

    // Add text to certificate
    imagettftext($image, $fontSize, 0, $nameX, $nameY, $color, $fontPath, $name);

    // Save certificate
    $certificatePath = "./certificates/$name.png";
    imagepng($image, $certificatePath);
    imagedestroy($image);

    // Send email with certificate
    sendCertificateEmail($email, $name, $certificatePath, $eventTitle);
}

echo "Certificates generated and mailed successfully.";

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
        $mail->setFrom('aryan.langhanoja119561@marwadiuniversity.ac.in', 'Event Team');
        $mail->addAddress($toEmail, $recipientName);
        $mail->addAttachment($certificatePath);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Certificate of Participation - $eventTitle";
        $mail->Body = "Dear $recipientName,<br><br>Congratulations! Please find attached your certificate for participating in <b>$eventTitle</b>.<br><br>Best Regards,<br>Event Team";

        $mail->send();
        echo "Certificate sent to: $toEmail<br>";
    } catch (Exception $e) {
        echo "Failed to send to $toEmail: {$mail->ErrorInfo}<br>";
    }
}
?>
