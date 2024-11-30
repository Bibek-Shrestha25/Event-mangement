<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
    }

    // Initialize PHPMailer settings
    private function configureSMTP()
    {
        $this->mail->isSMTP();
        $this->mail->Host = getenv('SMTP_HOST');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = getenv('SMTP_USERNAME');
        $this->mail->Password = getenv('SMTP_PASSWORD');
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = getenv('SMTP_PORT');
    }

    // Set email recipient details
    private function setRecipient($email, $name)
    {
        $this->mail->setFrom('test@example.com', 'Your Name');
        $this->mail->addAddress($email, $name);
    }

    // Set subject and body for the email
    private function setMessage($subject, $body, $altBody = '')
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AltBody = $altBody;
    }

    // 
    public function sendConfirmationMail() {}

    // Send email with basic settings
    public function sendBasicMail($email, $name, $subject, $body)
    {
        try {
            $this->configureSMTP();
            $this->setRecipient($email, $name);
            $this->setMessage($subject, $body);
            $this->mail->send();
            return 'Message has been sent successfully';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    // Send email with an attachment
    public function sendMailWithAttachment($email, $name, $subject, $body, $attachmentPath)
    {
        try {
            $this->configureSMTP();
            $this->setRecipient($email, $name);
            $this->setMessage($subject, $body);

            // Add an attachment if specified
            if ($attachmentPath) {
                $this->mail->addAttachment($attachmentPath);
            }

            $this->mail->send();
            return 'Message with attachment has been sent successfully';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    // Send email in plain text format
    public function sendPlainMail($email, $name, $subject, $body)
    {
        try {
            $this->configureSMTP();
            $this->setRecipient($email, $name);
            $this->mail->isHTML(false); // Send as plain text
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            $this->mail->send();
            return 'Plain text message has been sent successfully';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    function generateOTP($length = 6)
    {
        // Define the characters that can be used in the OTP
        $characters = '0123456789';
        $otp = '';

        // Loop to generate the OTP of the desired length
        for ($i = 0; $i < $length; $i++) {
            // Randomly select a character from the defined string
            $otp .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $otp;
    }
}
