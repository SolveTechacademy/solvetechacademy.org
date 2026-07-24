<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Announcements Report";

try{

$stmt = $pdo->query("
SELECT
a.*,
c.course_title
FROM announcements a
LEFT JOIN courses c
ON a.course_id=c.id
ORDER BY a.id DESC
");

$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalAnnouncements = count($announcements);

$published = $pdo->query("
SELECT COUNT(*)
FROM announcements
WHERE status='Published'
")->fetchColumn();

$drafts = $pdo->query("
SELECT COUNT(*)
FROM announcements
WHERE status='Draft'
")->fetchColumn();

$today = $pdo->query("
SELECT COUNT(*)
FROM announcements
WHERE publish_date=CURDATE()
")->fetchColumn();

}catch(PDOException $e){

die($e->getMessage());

}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">Announcements Report</h2>

<p class="text-muted">
All announcements published in the LMS.
</p>

</div>

<div>

<button onclick="window.print();" class="btn btn-dark">

<i class="fas fa-print"></i>

Print

</button>

<a href="export_excel.php?type=announcements"
class="btn btn-success">

<i class="fas fa-file-excel"></i>

Excel

</a>

<a href="export_pdf.php?type=announcements"
class="btn btn-danger">

<i class="fas fa-file-pdf"></i>

PDF

</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total</h6>

<h2><?= $totalAnnouncements ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Published</h6>

<h2 class="text-success"><?= $published ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Drafts</h6>

<h2 class="text-warning"><?= $drafts ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Today</h6>

<h2 class="text-primary"><?= $today ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

Announcements Report

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="dataTable" class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>#</th>
<th>Title</th>
<th>Audience</th>
<th>Course</th>
<th>Status</th>
<th>Publish Date</th>
<th>Expiry Date</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($announcements as $announcement): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($announcement['title']); ?></td>

<td><?= htmlspecialchars($announcement['audience']); ?></td>

<td>

<?= $announcement['course_title'] ?
htmlspecialchars($announcement['course_title']) :
'All Courses'; ?>

</td>

<td>

<?php if($announcement['status']=="Published"): ?>

<span class="badge bg-success">

Published

</span>

<?php else: ?>

<span class="badge bg-warning">

Draft

</span>

<?php endif; ?>

</td>

<td>

<?= date('d M Y',strtotime($announcement['publish_date'])); ?>

</td>

<td>

<?= !empty($announcement['expiry_date'])
? date('d M Y',strtotime($announcement['expiry_date']))
: '-'; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>

<script>

$(document).ready(function(){

$('#dataTable').DataTable({

responsive:true,

pageLength:10,

order:[[0,'desc']]

});

});

</script>