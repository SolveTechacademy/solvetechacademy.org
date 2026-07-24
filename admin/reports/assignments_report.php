<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Assignments Report";

try{

$stmt = $pdo->query("
SELECT
a.*,
l.lesson_title
FROM assignments a
LEFT JOIN lessons l
ON a.lesson_id = l.id
ORDER BY a.id DESC
");

$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalAssignments = count($assignments);

$todayAssignments = $pdo->query("
SELECT COUNT(*)
FROM assignments
WHERE deadline = CURDATE()
")->fetchColumn();

$expiredAssignments = $pdo->query("
SELECT COUNT(*)
FROM assignments
WHERE deadline < CURDATE()
")->fetchColumn();

$upcomingAssignments = $pdo->query("
SELECT COUNT(*)
FROM assignments
WHERE deadline > CURDATE()
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

<h2 class="fw-bold">Assignments Report</h2>

<p class="text-muted mb-0">
Assignments created within the LMS.
</p>

</div>

<div>

<button onclick="window.print()" class="btn btn-dark">
<i class="fas fa-print"></i> Print
</button>

<a href="export_excel.php?type=assignments" class="btn btn-success">
<i class="fas fa-file-excel"></i> Excel
</a>

<a href="export_pdf.php?type=assignments" class="btn btn-danger">
<i class="fas fa-file-pdf"></i> PDF
</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Assignments</h6>

<h2><?= $totalAssignments ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Due Today</h6>

<h2 class="text-warning"><?= $todayAssignments ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Upcoming</h6>

<h2 class="text-success"><?= $upcomingAssignments ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Expired</h6>

<h2 class="text-danger"><?= $expiredAssignments ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-secondary text-white">

<h5 class="mb-0">Assignments Report</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="dataTable" class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>#</th>
<th>Lesson</th>
<th>Assignment Title</th>
<th>Instructions</th>
<th>Deadline</th>
<th>Created</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($assignments as $assignment): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($assignment['lesson_title'] ?? 'N/A'); ?></td>

<td><?= htmlspecialchars($assignment['title']); ?></td>

<td>

<?= strlen($assignment['instructions']) > 80
? htmlspecialchars(substr($assignment['instructions'],0,80))."..."
: htmlspecialchars($assignment['instructions']); ?>

</td>

<td>

<?php

if(empty($assignment['deadline'])){

echo "<span class='badge bg-secondary'>No Deadline</span>";

}elseif(strtotime($assignment['deadline']) < strtotime(date('Y-m-d'))){

echo "<span class='badge bg-danger'>".date('d M Y',strtotime($assignment['deadline']))."</span>";

}elseif($assignment['deadline']==date('Y-m-d')){

echo "<span class='badge bg-warning'>".date('d M Y',strtotime($assignment['deadline']))."</span>";

}else{

echo "<span class='badge bg-success'>".date('d M Y',strtotime($assignment['deadline']))."</span>";

}

?>

</td>

<td>

<?= date('d M Y',strtotime($assignment['created_at'])); ?>

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