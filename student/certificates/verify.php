<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_GET['code'])) {
    die("Invalid verification code.");
}

$code = trim($_GET['code']);

$sql = "
SELECT

    c.*,

    s.fullname,

    s.email,

    co.course_title

FROM certificates c

INNER JOIN students s
    ON s.id = c.student_id

INNER JOIN courses co
    ON co.id = c.course_id

WHERE c.verification_code = ?
LIMIT 1
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$code]);

$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$certificate) {

    die("
    <div style='margin:80px auto;width:700px;text-align:center;font-family:Arial;'>

        <h1 style='color:red;'>
            Invalid Certificate
        </h1>

        <p>
            This certificate could not be verified.
        </p>

    </div>
    ");

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Certificate Verification</title>

<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

<link href="../../assets/fontawesome/css/all.min.css" rel="stylesheet">

<style>

body{

background:#f5f7fb;

}

.verify-card{

margin-top:60px;

border-radius:12px;

box-shadow:0 5px 20px rgba(0,0,0,.08);

}

.logo{

height:90px;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-9">

<div class="card verify-card">

<div class="card-body text-center">
<?php if($certificate['status']=="Issued"): ?>

<div class="mb-4">

    <i class="fas fa-check-circle text-success"
       style="font-size:70px;"></i>

    <h2 class="mt-3 text-success">

        VERIFIED CERTIFICATE

    </h2>

    <p class="text-muted">

        This certificate is authentic and was issued by
        <strong>SolveTech Academy</strong>.

    </p>

</div>

<?php else: ?>

<div class="mb-4">

    <i class="fas fa-times-circle text-danger"
       style="font-size:70px;"></i>

    <h2 class="mt-3 text-danger">

        CERTIFICATE REVOKED

    </h2>

    <p class="text-muted">

        This certificate is no longer valid.

    </p>

</div>

<?php endif; ?>

<hr>

<div class="row text-start">

<div class="col-md-6 mb-3">

<label class="fw-bold">

Student Name

</label>

<p>

<?= htmlspecialchars($certificate['fullname']); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Course

</label>

<p>

<?= htmlspecialchars($certificate['course_title']); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Certificate Number

</label>

<p class="text-primary fw-bold">

<?= htmlspecialchars($certificate['certificate_number']); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Verification Code

</label>

<p>

<?= htmlspecialchars($certificate['verification_code']); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Issue Date

</label>

<p>

<?= date('d M Y', strtotime($certificate['issue_date'])); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Completion Date

</label>

<p>

<?= date('d M Y', strtotime($certificate['completion_date'])); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Grade

</label>

<p>

<?= htmlspecialchars($certificate['grade']); ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Final Score

</label>

<p>

<?= number_format($certificate['final_score'],2); ?>%

</p>

</div>

</div>

<hr>

<div class="mt-4">

<img src="../../assets/images/logo.png"
     class="logo mb-3">

<h5 class="fw-bold">

SolveTech Academy

</h5>

<p class="text-muted">

Professional IT Training & Certification

</p>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>