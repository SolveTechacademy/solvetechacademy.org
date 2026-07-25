<?php
session_start();

require_once 'config/database.php';
require_once 'includes/mailer.php';

$message = "";

if (isset($_SESSION['student_login'])) {
    header("Location: student/dashboard.php");
    exit();
}

if (isset($_POST['submit'])) {

    $email = trim($_POST['email']);

    if (empty($email)) {

        $message = "
        <div class='alert alert-danger'>
            Please enter your email address.
        </div>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "
        <div class='alert alert-danger'>
            Please enter a valid email address.
        </div>";

    } else {

        $stmt = $pdo->prepare("
            SELECT id, fullname, email
            FROM students
            WHERE email=?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        if ($stmt->rowCount() == 1) {

            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            $token = bin2hex(random_bytes(32));

            $expiry = date(
                "Y-m-d H:i:s",
                strtotime("+1 hour")
            );

            $update = $pdo->prepare("
                UPDATE students
                SET
                    reset_token=?,
                    reset_token_expiry=?
                WHERE id=?
            ");

            $update->execute([
                $token,
                $expiry,
                $student['id']
            ]);

            $resetLink =
                "https://solvetechacademy.org/reset-password.php?token=" . $token;
                            $subject = "Reset Your SolveTech Academy Password";

            $body = '

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Password Reset</title>

</head>

<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0">

<tr>

<td align="center" style="padding:40px;">

<table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;">

<tr>

<td style="background:#0d6efd;color:#fff;padding:30px;text-align:center;">

<h2 style="margin:0;">
SolveTech Academy
</h2>

<p style="margin-top:10px;">
Password Reset Request
</p>

</td>

</tr>

<tr>

<td style="padding:35px;">

<h3>Hello ' . htmlspecialchars($student['fullname']) . ',</h3>

<p>

We received a request to reset your password for your
SolveTech Academy account.

</p>

<p>

Click the button below to create a new password.

</p>

<p style="text-align:center;margin:40px 0;">

<a href="' . $resetLink . '"

style="
background:#0d6efd;
color:#ffffff;
padding:15px 35px;
text-decoration:none;
border-radius:6px;
display:inline-block;
font-size:16px;
font-weight:bold;
">

Reset My Password

</a>

</p>

<p>

If the button above doesn\'t work,
copy and paste this URL into your browser.

</p>

<p style="word-break:break-all;color:#0d6efd;">

' . $resetLink . '

</p>

<p>

This password reset link expires in
<strong>1 hour</strong>.

</p>

<p>

If you didn\'t request this password reset,
you can safely ignore this email.

</p>

<br>

<p>

Regards,

<br>

<strong>SolveTech Academy Team</strong>

</p>

</td>

</tr>

<tr>

<td style="background:#f5f5f5;padding:20px;text-align:center;font-size:13px;color:#777;">

© ' . date('Y') . ' SolveTech Academy.
All Rights Reserved.

</td>

</tr>

</table>

</td>

</tr>

</table>

</body>

</html>

';
            if (sendMail(
                $student['email'],
                $student['fullname'],
                $subject,
                $body
            )) {

                $message = "
                <div class='alert alert-success'>
                    If an account exists with that email address,
                    a password reset link has been sent.
                    Please check your inbox.
                </div>";

            } else {

                $message = "
                <div class='alert alert-danger'>
                    Unable to send the reset email.
                    Please try again later.
                </div>";

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Security
            | Don't reveal whether the email exists
            |--------------------------------------------------------------------------
            */

            $message = "
            <div class='alert alert-success'>
                If an account exists with that email address,
                a password reset link has been sent.
                Please check your inbox.
            </div>";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Forgot Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card shadow mt-5">

<div class="card-header bg-primary text-white text-center">

<h3>Forgot Password</h3>

</div>

<div class="card-body">

<?= $message ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Email Address

</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<button
type="submit"
name="submit"
class="btn btn-primary w-100">

Send Password Reset Link

</button>

</form>

<hr>

<div class="text-center">

<a href="login.php">

Back to Login

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>