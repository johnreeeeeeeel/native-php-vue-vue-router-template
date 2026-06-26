<?php

require __DIR__ . "/../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

function sendResetPasswordEmail($email, $link)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USERNAME'];
        $mail->Password   = $_ENV['SMTP_PASSWORD']; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($_ENV['SMTP_FROM'], $_ENV['SMTP_TITLE']);
        $mail->addAddress($email);
        $mail->addReplyTo($_ENV['SMTP_TO'], $_ENV['SMTP_TITLE']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Reset Your Password - " . $_ENV['SMTP_TITLE'];
        $mail->Body = buildEmailBody($link);
        $mail->AltBody = "Reset your password at Bulls Fitness Gym. Click this link: $link";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $e->getMessage());
        return false;
    }
}

function buildEmailBody($link) {
    return "
        <h2>Password Reset Request</h2>

        <p>Please click the button below to set a new password:</p>
        <a href='{$link}'>Reset My Password</a>

        <p>This link will expire in <strong>30 minutes</strong>.</p>
        <p>If you did not request a password reset, please ignore this email. Your account is safe and no changes have been made.</p>

    ";
}