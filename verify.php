<?php

require_once 'config/database.php';

if (!isset($_GET['code'])) {

    die("Invalid verification request.");

}

$code = trim($_GET['code']);

$stmt = $pdo->prepare("

SELECT

c.*,

s.fullname,

co.course_title

FROM certificates c

INNER JOIN students s
ON s.id=c.student_id

INNER JOIN courses co
ON co.id=c.course_id

WHERE

c.verification_code=?

LIMIT 1

");

$stmt->execute([$code]);

$certificate=$stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Certificate Verification

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{

background:#eef3fb;

}

.card{

border:none;

border-radius:15px;

box-shadow:0 10px 30px rgba(0,0,0,.08);

}

.logo{

height:80px;

}

.valid{

color:#198754;

font-size:65px;

}

.invalid{

color:red;

font-size:65px;

}

</style>

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">
    <?php if(!$certificate): ?>

<div class="card">

<div class="card-body text-center p-5">

<div class="invalid">

❌

</div>

<h2 class="text-danger mt-3">

Certificate Not Found

</h2>

<p class="text-muted">

The verification code you entered is invalid or this certificate does not exist.

</p>

</div>

</div>

<?php else: ?>

<div class="card">

<div class="card-body p-5">

<div class="text-center">

<img
src="assets/images/logo.png"
class="logo mb-3">

<?php if($certificate['status']=="Issued"): ?>

<div class="valid">

✔

</div>

<h2 class="text-success">

VALID CERTIFICATE

</h2>

<p class="text-muted">

This certificate has been successfully verified by

<strong>SolveTech Academy</strong>

</p>

<?php else: ?>

<div class="invalid">

✖

</div>

<h2 class="text-danger">

CERTIFICATE REVOKED

</h2>

<p>

This certificate has been revoked by SolveTech Academy.

</p>

<?php endif; ?>

</div>

<hr>

<table class="table table-bordered">

<tr>

<th width="35%">

Student Name

</th>

<td>

<?= htmlspecialchars($certificate['fullname']) ?>

</td>

</tr>

<tr>

<th>

Course

</th>

<td>

<?= htmlspecialchars($certificate['course_title']) ?>

</td>

</tr>

<tr>

<th>

Certificate Number

</th>

<td>

<?= htmlspecialchars($certificate['certificate_number']) ?>

</td>

</tr>

<tr>

<th>

Completion Date

</th>

<td>

<?= date("d F Y",strtotime($certificate['completion_date'])) ?>

</td>

</tr>

<tr>

<th>

Verification Code

</th>

<td>

<?= htmlspecialchars($certificate['verification_code']) ?>

</td>

</tr>

<tr>

<th>

Issued On

</th>

<td>

<?= date("d F Y",strtotime($certificate['issue_date'])) ?>

</td>

</tr>

<tr>

<th>

Status

</th>

<td>

<?php if($certificate['status']=="Issued"): ?>

<span class="badge bg-success">

VALID

</span>

<?php else: ?>

<span class="badge bg-danger">

REVOKED

</span>

<?php endif; ?>

</td>

</tr>

</table>

<div class="text-center mt-4">

<p class="text-muted">

This certificate was issued by

<strong>SolveTech Academy</strong>

and can be independently verified using the verification code above.

</p>

<a href="index.php" class="btn btn-primary">

Back to Website

</a>

</div>

</div>

</div>

<?php endif; ?>

</div>

</div>

</div>

</body>

</html>