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
s.email,
s.phone,

c.course_title,
c.course_code,
c.duration,

r.training_mode

FROM certificates cert

INNER JOIN students s
ON cert.student_id = s.id

INNER JOIN courses c
ON cert.course_id = c.id

INNER JOIN registrations r
ON cert.registration_id = r.id

WHERE cert.registration_id=?

LIMIT 1
");

$stmt->execute([$registration_id]);

$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$certificate){

die("Certificate not found.");

}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

Certificate Details

</h2>

<div>

<a href="print.php?registration_id=<?= $registration_id; ?>"
class="btn btn-primary">

<i class="fas fa-print"></i>

Print

</a>

<a href="pdf.php?registration_id=<?= $registration_id; ?>"
class="btn btn-danger">

<i class="fas fa-file-pdf"></i>

PDF

</a>

<a href="index.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="250">

Certificate Number

</th>

<td>

<?= $certificate['certificate_number']; ?>

</td>

</tr>

<tr>

<th>

Student Name

</th>

<td>

<?= $certificate['fullname']; ?>

</td>

</tr>

<tr>

<th>

Student ID

</th>

<td>

<?= $certificate['student_id']; ?>

</td>

</tr>
<tr>

<th>

Student ID

</th>

<td>

<?= $certificate['student_id']; ?>

</td>

</tr>
<tr>

<th>

Course

</th>

<td>

<?= $certificate['course_title']; ?>

</td>

</tr>

<tr>

<th>

Course Code

</th>

<td>

<?= $certificate['course_code']; ?>

</td>

</tr>

<tr>

<th>

Training Mode

</th>

<td>

<?= $certificate['training_mode']; ?>

</td>

</tr>

<tr>

<th>

Course Duration

</th>

<td>

<?= $certificate['duration']; ?>

</td>

</tr>

<tr>

<th>

Issue Date

</th>

<td>

<?= date('d F Y', strtotime($certificate['issue_date'])); ?>

</td>

</tr>

<tr>

<th>

Completion Date

</th>

<td>

<?= date('d F Y', strtotime($certificate['completion_date'])); ?>

</td>

</tr>

<tr>

<th>

Grade

</th>

<td>

<span class="badge bg-success">

<?= $certificate['grade']; ?>

</span>

</td>

</tr>

<tr>

<th>

Status

</th>

<td>

<?php if($certificate['status']=='Issued'): ?>

<span class="badge bg-success">

Issued

</span>

<?php else: ?>

<span class="badge bg-danger">

Revoked

</span>

<?php endif; ?>

</td>

</tr>

</table>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>