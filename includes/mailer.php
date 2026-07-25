<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

/**
 * Send Email
 */
function sendMail($toEmail, $toName, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {

        /*
        |--------------------------------------------------------------------------
        | SMTP Configuration
        |--------------------------------------------------------------------------
        */

        $mail->isSMTP();

        $mail->Host = 'mail.solvetechacademy.org';
        // Change if your hosting provider uses another SMTP server.

        $mail->SMTPAuth = true;

        $mail->Username = 'reset@solvetechacademy.org';

        $mail->Password = 'Nk6Nk614@';
        // Replace with your mailbox password or SMTP password.

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        /*
        |--------------------------------------------------------------------------
        | Sender
        |--------------------------------------------------------------------------
        */

        $mail->setFrom(
            'reset@solvetechacademy.org',
            'SolveTech Academy'
        );

        /*
        |--------------------------------------------------------------------------
        | Recipient
        |--------------------------------------------------------------------------
        */

        $mail->addAddress($toEmail, $toName);

        /*
        |--------------------------------------------------------------------------
        | Email Content
        |--------------------------------------------------------------------------
        */

        $mail->isHTML(true);

        $mail->Subject = $subject;

        $mail->Body = $body;

        $mail->AltBody = strip_tags($body);

        /*
        |--------------------------------------------------------------------------
        | Send
        |--------------------------------------------------------------------------
        */

        return $mail->send();

    } catch (Exception $e) {

        error_log($mail->ErrorInfo);

        return false;

    }
}