<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Reports Dashboard";

$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalCourses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$totalPayments = $pdo->query("SELECT COUNT(*) FROM payments")->fetchColumn();
$totalCertificates = $pdo->query("SELECT COUNT(*) FROM certificates")->fetchColumn();
$totalAssignments = $pdo->query("SELECT COUNT(*) FROM assignments")->fetchColumn();
$totalAnnouncements = $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="mb-4">

<h2 class="fw-bold">
Reports Dashboard
</h2>

<p class="text-muted">
Generate professional reports for every module in the LMS.
</p>

</div>

<div class="row">

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow border-0">

<div class="card-body">

<h5>Students Report</h5>

<h2><?= $totalStudents ?></h2>

<a href="students_report.php" class="btn btn-primary mt-3">
Open Report
</a>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow border-0">

<div class="card-body">

<h5>Courses Report</h5>

<h2><?= $totalCourses ?></h2>

<a href="courses_report.php" class="btn btn-success mt-3">
Open Report
</a>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow border-0">

<div class="card-body">

<h5>Payments Report</h5>

<h2><?= $totalPayments ?></h2>

<a href="payments_report.php" class="btn btn-warning mt-3">
Open Report
</a>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow border-0">

<div class="card-body">

<h5>Certificates Report</h5>

<h2><?= $totalCertificates ?></h2>

<a href="certificates_report.php" class="btn btn-info mt-3">
Open Report
</a>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow border-0">

<div class="card-body">

<h5>Assignments Report</h5>

<h2><?= $totalAssignments ?></h2>

<a href="assignments_report.php" class="btn btn-secondary mt-3">
Open Report
</a>

</div>

</div>

</div>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow border-0">

<div class="card-body">

<h5>Announcements Report</h5>

<h2><?= $totalAnnouncements ?></h2>

<a href="announcements_report.php" class="btn btn-danger mt-3">
Open Report
</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>