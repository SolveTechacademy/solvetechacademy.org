<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once _DIR_.'/../PHPMailer/src/Exception.php';
require_once _DIR_.'/../PHPMailer/src/PHPMailer.php';
require_once _DIR_.'/../PHPMailer/src/SMTP.php';

function sendMail($to,$name,$subject,$body)
{

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();

        $mail->Host='mail.solvetechacademy.org';

        $mail->SMTPAuth=true;

        $mail->Username='info@solvetechacademy.org';

        $mail->Password='YOUR_EMAIL_PASSWORD';

        $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port=587;

        $mail->setFrom(
            'info@solvetechacademy.org',
            'SolveTech Academy'
        );

        $mail->addAddress($to,$name);

        $mail->isHTML(true);

        $mail->Subject=$subject;

        $mail->Body=$body;

        return $mail->send();

    }

    catch(Exception $e){

        return false;

    }

}