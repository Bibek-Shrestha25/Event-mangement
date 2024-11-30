
<?php
// Include PHPMailer classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); // Create a new PHPMailer instance

try {
    // Server settings
    $mail->isSMTP();                                    // Use SMTP
    $mail->Host = 'sandbox.smtp.mailtrap.io';                   // Set the SMTP server
    $mail->SMTPAuth = true;                             // Enable SMTP authentication
    $mail->Username = '518802855ffb30';         // SMTP username
    $mail->Password = 'df5cbf85e81548';                 // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587;                                  // TCP port to connect to

    // Recipients
    $mail->setFrom('test@example.com', 'Your Name');
    $mail->addAddress('teste@example.com', 'Recipient Name'); // Add a recipient
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments (optional)
    // $mail->addAttachment('/path/to/file.txt');         // Add attachments
    // $mail->addAttachment('/path/to/image.jpg', 'new.jpg'); // Optional name

    // Content
    $mail->isHTML(true);                                // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
