<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['registration_id'])) {
    die("Invalid Request");
}

$registration_id = (int)$_GET['registration_id'];

$stmt = $pdo->prepare("
SELECT

cert.*,

s.student_id,
s.fullname,

c.course_title,
c.duration,

r.training_mode

FROM certificates cert

INNER JOIN students s
ON cert.student_id=s.id

INNER JOIN courses c
ON cert.course_id=c.id

INNER JOIN registrations r
ON cert.registration_id=r.id

WHERE cert.registration_id=?

LIMIT 1
");

$stmt->execute([$registration_id]);

$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$certificate){

die("Certificate not found.");

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Certificate

</title>

<style>

body{

font-family:Georgia,serif;
background:#f2f2f2;
margin:0;
padding:40px;

}

.certificate{

background:#fff;
border:12px solid #0d6efd;
padding:60px;
max-width:1100px;
margin:auto;

}

h1{

text-align:center;
font-size:50px;
margin-bottom:5px;
color:#0d6efd;

}

h2{

text-align:center;
font-size:34px;

}

p{

font-size:22px;
text-align:center;
line-height:1.8;

}

.student{

font-size:42px;
font-weight:bold;
color:#198754;

}

.footer{

margin-top:80px;
display:flex;
justify-content:space-between;

}

.signature{

text-align:center;

}

.line{

width:250px;
border-top:2px solid #000;
margin:auto;

}

@media print{

button{

display:none;

}

body{

background:#fff;

}

}

</style>

</head>

<body>

<button onclick="window.print();">

Print Certificate

</button>

<div class="certificate">

<h1>

SolveTech Academy

</h1>

<h2>

CERTIFICATE OF COMPLETION

</h2>
<p>

This Certificate is proudly presented to

</p>

<p class="student">

<?= strtoupper($certificate['fullname']); ?>

</p>

<p>

For successfully completing the professional training programme in

</p>

<p>

<strong>

<?= $certificate['course_title']; ?>

</strong>

</p>

<p>

Training Mode:
<strong><?= $certificate['training_mode']; ?></strong>

</p>

<p>

Course Duration:
<strong><?= $certificate['duration']; ?></strong>

</p>

<p>

Completion Date:

<strong>

<?= date('d F Y', strtotime($certificate['completion_date'])); ?>

</strong>

</p>

<p>

Certificate Number:

<strong>

<?= $certificate['certificate_number']; ?>

</strong>

</p>

<div class="footer">

<div class="signature">

<div class="line"></div>

Academic Director

</div>

<div class="signature">

<div class="line"></div>

Executive Director

</div>

</div>

</div>

</body>

</html>