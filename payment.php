<?php
session_start();

require_once 'config/database.php';

if (!isset($_GET['reg'])) {
    die("Invalid Registration.");
}

$registrationID = $_GET['reg'];

$stmt = $pdo->prepare("
SELECT

registrations.id,
registrations.registration_id,

students.student_id,
students.fullname,
students.email,

courses.course_title,
courses.course_fee

FROM registrations

INNER JOIN students
ON registrations.student_id = students.id

INNER JOIN courses
ON registrations.course_id = courses.id

WHERE registrations.registration_id=?

LIMIT 1

");

$stmt->execute([$registrationID]);

$registration = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$registration){

    die("Registration not found.");

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Course Payment | SolveTech Academy</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,Helvetica,sans-serif;
}

body{
background:#F5F7FB;
}

.wrapper{
padding:50px 0;
}

.payment-card{

background:#fff;

border-radius:25px;

overflow:hidden;

box-shadow:0 15px 50px rgba(0,0,0,.08);

}

.left{

background:linear-gradient(135deg,#1E2143,#FF8A3D);

color:#fff;

padding:60px 45px;

height:100%;

}

.left img{

height:85px;

background:#fff;

padding:12px;

border-radius:15px;

}

.left h2{

margin-top:40px;

font-size:40px;

font-weight:700;

}

.left p{

margin-top:20px;

line-height:1.8;

font-size:18px;

}

.step{

margin-top:40px;

display:flex;

align-items:center;

font-size:18px;

}

.step i{

font-size:25px;

margin-right:15px;

}

.right{

padding:50px;

}

.section-title{

font-weight:700;

color:#1E2143;

margin-bottom:25px;

}

.summary{

background:#F8F9FC;

border-radius:15px;

padding:20px;

margin-bottom:30px;

}

.summary table{

margin-bottom:0;

}

.summary td,
.summary th{

padding:14px;

}

.price{

font-size:34px;

font-weight:bold;

color:#FF8A3D;

}

.form-control,
.form-select{

height:55px;

border-radius:12px;

}

.form-control:focus,
.form-select:focus{

border-color:#FF8A3D;

box-shadow:0 0 0 .2rem rgba(255,138,61,.15);

}

.upload-box{

border:2px dashed #ced4da;

padding:40px;

text-align:center;

border-radius:15px;

background:#fafafa;

}

.btn-pay{

background:#FF8A3D;

border:none;

height:55px;

border-radius:12px;

font-weight:bold;

color:#fff;

}

.btn-pay:hover{

background:#ef7d30;

color:#fff;

}

@media(max-width:992px){

.left{

display:none;

}

.right{

padding:30px;

}

}

</style>

</head>

<body>

<div class="wrapper">

<div class="container">

<div class="payment-card">

<div class="row g-0">

<div class="col-lg-4">

<div class="left">

<img src="assets/images/logo.png">

<h2>

Course Payment

</h2>

<p>

Complete your payment securely and upload your proof of payment for verification.

</p>

<div class="step">

<i class="fas fa-check-circle"></i>

Registration Completed

</div>

<div class="step">

<i class="fas fa-money-check-alt"></i>

Payment Verification

</div>

<div class="step">

<i class="fas fa-graduation-cap"></i>

Begin Learning

</div>

</div>

</div>

<div class="col-lg-8">

<div class="right">

<h2 class="section-title">

Registration Summary

</h2>

<div class="summary">
<table class="table table-borderless align-middle">

<tr>

<th width="35%">Student ID</th>

<td><?= htmlspecialchars($registration['student_id']); ?></td>

</tr>

<tr>

<th>Student Name</th>

<td><?= htmlspecialchars($registration['fullname']); ?></td>

</tr>

<tr>

<th>Email</th>

<td><?= htmlspecialchars($registration['email']); ?></td>

</tr>

<tr>

<th>Registration ID</th>

<td><?= htmlspecialchars($registration['registration_id']); ?></td>

</tr>

<tr>

<th>Course</th>

<td><?= htmlspecialchars($registration['course_title']); ?></td>

</tr>

<tr>

<th>Course Fee</th>

<td>

<div class="price">

<?= number_format($registration['course_fee']); ?> FCFA

</div>

</td>

</tr>

</table>

</div>

<h4 class="section-title">

Payment Methods

</h4>

<div class="row mb-4">

<div class="col-md-6 mb-3">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body">

<h5 class="fw-bold text-warning">

<i class="fas fa-mobile-alt me-2"></i>

MTN Mobile Money

</h5>

<p class="mb-2">

<strong>Number:</strong>

+237 654 178 586

</p>

<p class="text-muted mb-0">

Reference:

<strong><?= htmlspecialchars($registration['registration_id']); ?></strong>

</p>

</div>

</div>

</div>

<div class="col-md-6 mb-3">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body">

<h5 class="fw-bold text-danger">

<i class="fas fa-wallet me-2"></i>

Orange Money

</h5>

<p class="mb-2">

<strong>Number:</strong>

+237 654 178 586

</p>

<p class="text-muted mb-0">

Reference:

<strong><?= htmlspecialchars($registration['registration_id']); ?></strong>

</p>

</div>

</div>

</div>

</div>

<h4 class="section-title">

Upload Payment Proof

</h4>

<form action="payment_process.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="registration_id" value="<?= $registration['id']; ?>">

<input type="hidden" name="registration_number" value="<?= $registration['registration_id']; ?>">

<input type="hidden" name="student_name" value="<?= htmlspecialchars($registration['fullname']); ?>">

<input type="hidden" name="student_email" value="<?= htmlspecialchars($registration['email']); ?>">

<input type="hidden" name="student_id" value="<?= $registration['student_id']; ?>">

<input type="hidden" name="course" value="<?= htmlspecialchars($registration['course_title']); ?>">

<div class="row">

<div class="col-md-6 mb-4">

<label class="form-label fw-semibold">

Amount Paid

</label>

<input
type="number"
name="amount"
class="form-control"
value="<?= $registration['course_fee']; ?>"
required>

</div>

<div class="col-md-6 mb-4">

<label class="form-label fw-semibold">

Payment Method

</label>

<select
name="payment_method"
class="form-select"
required>

<option value="">Choose Payment Method</option>

<option>MTN Mobile Money</option>

<option>Orange Money</option>

<option>Bank Transfer</option>

</select>

</div>

<div class="col-md-12 mb-4">

<label class="form-label fw-semibold">

Transaction ID

</label>

<input
type="text"
name="transaction_id"
class="form-control"
placeholder="Enter transaction reference"
required>

</div>

<div class="col-md-12 mb-4">

<label class="form-label fw-semibold">

Payment Screenshot / Receipt

</label>

<div class="upload-box">

<i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-3"></i>

<input
type="file"
name="payment_proof"
class="form-control"
accept=".jpg,.jpeg,.png,.pdf"
required>

<small class="text-muted d-block mt-3">

Accepted formats: JPG, PNG or PDF

</small>

</div>

</div>

<div class="col-md-12">

<button
type="submit"
name="submit_payment"
class="btn btn-pay w-100">

<i class="fas fa-paper-plane me-2"></i>

Submit Payment for Verification

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>