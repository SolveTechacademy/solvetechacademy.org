<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Courses Report";

try{

$stmt = $pdo->query("
SELECT *
FROM courses
ORDER BY id DESC
");

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalCourses = count($courses);

$activeCourses = $pdo->query("
SELECT COUNT(*)
FROM courses
WHERE status='Active'
")->fetchColumn();

$inactiveCourses = $pdo->query("
SELECT COUNT(*)
FROM courses
WHERE status='Inactive'
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

<h2 class="fw-bold">Courses Report</h2>

<p class="text-muted mb-0">
View all courses available in the LMS.
</p>

</div>

<div>

<button onclick="window.print();" class="btn btn-dark">
<i class="fas fa-print"></i> Print
</button>

<a href="export_excel.php?type=courses" class="btn btn-success">
<i class="fas fa-file-excel"></i> Excel
</a>

<a href="export_pdf.php?type=courses" class="btn btn-danger">
<i class="fas fa-file-pdf"></i> PDF
</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-4">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Courses</h6>

<h2><?= $totalCourses ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow-sm">

<div class="card-body">

<h6>Active</h6>

<h2 class="text-success"><?= $activeCourses ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow-sm">

<div class="card-body">

<h6>Inactive</h6>

<h2 class="text-danger"><?= $inactiveCourses ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-success text-white">

<h5 class="mb-0">
Courses Report
</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="dataTable" class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>#</th>
<th>Code</th>
<th>Thumbnail</th>
<th>Course Title</th>
<th>Category</th>
<th>Instructor</th>
<th>Duration</th>
<th>Level</th>
<th>Mode</th>
<th>Fee</th>
<th>Status</th>
<th>Created</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($courses as $course): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($course['course_code']); ?></td>

<td>

<?php if(!empty($course['thumbnail'])): ?>

<img
src="../../uploads/courses/<?= htmlspecialchars($course['thumbnail']); ?>"
width="70"
height="45"
style="object-fit:cover;border-radius:6px;">

<?php else: ?>

<span class="badge bg-secondary">
No Image
</span>

<?php endif; ?>

</td>

<td><?= htmlspecialchars($course['course_title']); ?></td>

<td><?= htmlspecialchars($course['category']); ?></td>

<td><?= htmlspecialchars($course['instructor']); ?></td>

<td><?= htmlspecialchars($course['duration']); ?></td>

<td><?= htmlspecialchars($course['level']); ?></td>

<td>

<span class="badge bg-info">

<?= htmlspecialchars($course['mode']); ?>

</span>

</td>

<td>

<?= number_format($course['course_fee'],2); ?>

</td>

<td>

<?php if($course['status']=="Active"): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactive

</span>

<?php endif; ?>

</td>

<td>

<?= date('d M Y',strtotime($course['created_at'])); ?>

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