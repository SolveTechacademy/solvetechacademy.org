<?php
session_start();

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'mails/send_registration_email.php';
require_once 'config/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit();
}

$fullname = clean($_POST['fullname']);
$email = clean($_POST['email']);
$phone = clean($_POST['phone']);
$country = clean($_POST['country']);
$city = clean($_POST['city']);
$qualification = clean($_POST['qualification']);
$occupation = clean($_POST['occupation']);
$training_mode = clean($_POST['mode']);
$course_id = intval($_POST['course']);

$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if (
    empty($fullname) ||
    empty($email) ||
    empty($phone) ||
    empty($country) ||
    empty($city) ||
    empty($qualification) ||
    empty($occupation) ||
    empty($training_mode) ||
    empty($course_id) ||
    empty($password) ||
    empty($confirm_password)
) {
    die("Please fill in all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

if (strlen($password) < 8) {
    die("Password must be at least 8 characters.");
}

$check = $pdo->prepare("SELECT id FROM students WHERE email=?");
$check->execute([$email]);

if ($check->rowCount() > 0) {
    die("An account already exists with this email.");
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$studentID = generateStudentID($pdo);
$registrationID = generateRegistrationID($pdo);

try {

    $pdo->beginTransaction();

    // Insert Student

    $studentQuery = $pdo->prepare("
        INSERT INTO students
        (
            student_id,
            fullname,
            email,
            phone,
            country,
            city,
            qualification,
            occupation,
            password,
            status
        )
        VALUES
        (?,?,?,?,?,?,?,?,?,?)
    ");

    $studentQuery->execute([

        $studentID,
        $fullname,
        $email,
        $phone,
        $country,
        $city,
        $qualification,
        $occupation,
        $passwordHash,
        'Pending'

    ]);

    $student = $pdo->lastInsertId();

    // Insert Registration

    $registrationQuery = $pdo->prepare("
        INSERT INTO registrations
        (
            registration_id,
            student_id,
            course_id,
            training_mode,
            payment_status,
            approval_status
        )
        VALUES
        (?,?,?,?,?,?)
    ");

    $registrationQuery->execute([

        $registrationID,
        $student,
        $course_id,
        $training_mode,
        'Pending',
        'Pending'

    ]);

    $pdo->commit();

$_SESSION['student_id'] = $studentID;
$_SESSION['registration_id'] = $registrationID;
$_SESSION['student_name'] = $fullname;

$subject="Welcome to SolveTech Academy";

$message="

<h2>Registration Successful</h2>

<p>Hello <strong>$fullname</strong>,</p>

<p>

Welcome to SolveTech Academy.

</p>

<table border='1' cellpadding='10' cellspacing='0'>

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

Please proceed with payment to activate your account.

";

sendMail(

$email,

$fullname,

$subject,

$message

);

$adminSubject="New Student Registration";

$adminMessage="

<h2>New Registration</h2>

<table border='1' cellpadding='10' cellspacing='0'>

<tr>

<td>Name</td>

<td>$fullname</td>

</tr>

<tr>

<td>Email</td>

<td>$email</td>

</tr>

<tr>

<td>Student ID</td>

<td>$studentID</td>

</tr>

<tr>

<td>Registration ID</td>

<td>$registrationID</td>

</tr>

<tr>

<td>Country</td>

<td>$country</td>

</tr>

<tr>

<td>Training Mode</td>

<td>$training_mode</td>

</tr>

</table>

";

sendMail(

"info@solvetechacademy.org",

"Administrator",

$adminSubject,

$adminMessage

);

header("Location: payment.php?reg=".$registrationID);
exit();

} catch (Exception $e) {

    $pdo->rollBack();

    die("Registration Failed : " . $e->getMessage());

}