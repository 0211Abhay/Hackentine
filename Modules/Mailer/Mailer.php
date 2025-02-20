<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "./PHPMailer/src/Exception.php";
    require "./PHPMailer/src/PHPMailer.php";
    require "./PHPMailer/src/SMTP.php";

    if(isset($_POST["send"])) {
        $email = $_POST["email"];
        $subject = $_POST["subject"];
        $message = $_POST["message"];

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aryan.langhanoja119561@marwadiuniversity.ac.in';
        $mail->Password = 'cclq ktox eqlz ljdx'; // Warning: Do not expose sensitive credentials in code
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom('aryan.langhanoja119561@marwadiuniversity.ac.in');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        try {
            $mail->send();
            echo "<script>
                    alert('Sent Successfully');
                    document.location.href = './index.php';
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    alert('Failed to send email. Mailer Error: {$mail->ErrorInfo}');
                  </script>";
        }
    }
?>
