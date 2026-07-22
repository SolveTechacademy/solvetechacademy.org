<?php

function sendRegistrationEmail($studentName, $studentEmail, $studentID, $registrationID)
{

    $subject = "Welcome to SolveTech Academy";

    $message = "
    <html>

    <head>

    <style>

    body{
        font-family:Arial,sans-serif;
        background:#f5f5f5;
    }

    .container{
        width:650px;
        margin:auto;
        background:#ffffff;
        padding:30px;
        border-radius:8px;
    }

    h2{
        color:#0d6efd;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    td{
        padding:10px;
        border-bottom:1px solid #ddd;
    }

    </style>

    </head>

    <body>

    <div class='container'>

    <h2>Welcome to SolveTech Academy</h2>

    <p>Dear <strong>$studentName</strong>,</p>

    <p>Your registration has been received successfully.</p>

    <table>

        <tr>

            <td><strong>Student ID</strong></td>

            <td>$studentID</td>

        </tr>

        <tr>

            <td><strong>Registration ID</strong></td>

            <td>$registrationID</td>

        </tr>

        <tr>

            <td><strong>Status</strong></td>

            <td>Pending Payment</td>

        </tr>

    </table>

    <br>

    <p>
    Please complete your payment to activate your account.
    </p>

    <br>

    <p>

    Regards,<br>

    SolveTech Academy

    </p>

    </div>

    </body>

    </html>
    ";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: SolveTech Academy <info@solvetechacademy.org>\r\n";

    mail($studentEmail,$subject,$message,$headers);

}