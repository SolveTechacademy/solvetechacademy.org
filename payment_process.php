<?php
session_start();

require_once 'config/database.php';
require_once 'config/mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$registrationID = $_POST['registration_id'];
$registrationNumber = $_POST['registration_number'];

$studentName = trim($_POST['student_name']);
$studentEmail = trim($_POST['student_email']);
$studentID = trim($_POST['student_id']);
$course = trim($_POST['course']);

$amount = trim($_POST['amount']);
$paymentMethod = trim($_POST['payment_method']);
$transactionID = trim($_POST['transaction_id']);

if (
    empty($amount) ||
    empty($paymentMethod) ||
    empty($transactionID)
) {
    die("Please complete all payment fields.");
}

if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] != 0) {
    die("Please upload your payment proof.");
}

$allowed = ['jpg','jpeg','png','pdf'];

$extension = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));

if (!in_array($extension,$allowed)) {
    die("Invalid payment proof format.");
}

$fileName = uniqid('PAY_').".".$extension;

move_uploaded_file(
    $_FILES['payment_proof']['tmp_name'],
    "assets/uploads/payments/".$fileName
);

$paymentID = "STA-PAY-".date('Y')."-".rand(100000,999999);

$stmt = $pdo->prepare("
INSERT INTO payments
(
payment_id,
registration_id,
amount,
payment_method,
transaction_id,
payment_proof,
status
)
VALUES
(?,?,?,?,?,?,?)
");

$stmt->execute([
    $paymentID,
    $registrationID,
    $amount,
    $paymentMethod,
    $transactionID,
    $fileName,
    'Pending'
]);

$subject="Payment Submitted Successfully";

$message="

<h2>Payment Received</h2>

<p>Hello <strong>$studentName</strong>,</p>

<p>Your payment has been received successfully.</p>

<table border='1' cellpadding='10' cellspacing='0'>

<tr>

<td>Student ID</td>

<td>$studentID</td>

</tr>

<tr>

<td>Registration ID</td>

<td>$registrationNumber</td>

</tr>

<tr>

<td>Payment ID</td>

<td>$paymentID</td>

</tr>

<tr>

<td>Amount</td>

<td>$amount FCFA</td>

</tr>

<tr>

<td>Status</td>

<td>Pending Verification</td>

</tr>

</table>

<p>We will notify you once your payment has been verified.</p>

";

sendMail(
    $studentEmail,
    $studentName,
    $subject,
    $message
);

$adminSubject="New Payment Submitted";

$adminMessage="

<h2>New Student Payment</h2>

<table border='1' cellpadding='10' cellspacing='0'>

<tr>

<td>Name</td>

<td>$studentName</td>

</tr>

<tr>

<td>Email</td>

<td>$studentEmail</td>

</tr>

<tr>

<td>Student ID</td>

<td>$studentID</td>

</tr>

<tr>

<td>Registration ID</td>

<td>$registrationNumber</td>

</tr>

<tr>

<td>Course</td>

<td>$course</td>

</tr>

<tr>

<td>Payment ID</td>

<td>$paymentID</td>

</tr>

<tr>

<td>Amount</td>

<td>$amount FCFA</td>

</tr>

<tr>

<td>Method</td>

<td>$paymentMethod</td>

</tr>

<tr>

<td>Transaction ID</td>

<td>$transactionID</td>

</tr>

</table>

";

sendMail(
    "info@solvetechacademy.org",
    "Administrator",
    $adminSubject,
    $adminMessage
);

header("Location: login.php?payment=success");
exit();