<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Students Report";

try {

    $stmt = $pdo->query("
        SELECT *
        FROM students
        ORDER BY id DESC
    ");

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalStudents = count($students);

    $activeStudents = $pdo->query("
        SELECT COUNT(*)
        FROM students
        WHERE status='Active'
    ")->fetchColumn();

    $pendingStudents = $pdo->query("
        SELECT COUNT(*)
        FROM students
        WHERE status='Pending'
    ")->fetchColumn();

    $suspendedStudents = $pdo->query("
        SELECT COUNT(*)
        FROM students
        WHERE status='Suspended'
    ")->fetchColumn();

} catch(PDOException $e) {
    die($e->getMessage());
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">Students Report</h2>

<p class="text-muted mb-0">
View and print all registered students.
</p>

</div>

<div>

<button onclick="window.print();" class="btn btn-dark">
<i class="fas fa-print"></i> Print
</button>

<a href="export_excel.php?type=students" class="btn btn-success">
<i class="fas fa-file-excel"></i> Excel
</a>

<a href="export_pdf.php?type=students" class="btn btn-danger">
<i class="fas fa-file-pdf"></i> PDF
</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Students</h6>

<h2><?= $totalStudents ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Active</h6>

<h2 class="text-success"><?= $activeStudents ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Pending</h6>

<h2 class="text-warning"><?= $pendingStudents ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Suspended</h6>

<h2 class="text-danger"><?= $suspendedStudents ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">
Registered Students
</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="dataTable" class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>#</th>
<th>Student ID</th>
<th>Photo</th>
<th>Full Name</th>
<th>Email</th>
<th>Phone</th>
<th>Country</th>
<th>City</th>
<th>Qualification</th>
<th>Occupation</th>
<th>Status</th>
<th>Registered</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($students as $student): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($student['student_id']); ?></td>

<td>

<?php if(!empty($student['profile_photo'])): ?>

<img src="../../uploads/students/<?= htmlspecialchars($student['profile_photo']); ?>"
width="45"
height="45"
style="object-fit:cover;border-radius:50%;">

<?php else: ?>

<span class="badge bg-secondary">No Photo</span>

<?php endif; ?>

</td>

<td><?= htmlspecialchars($student['fullname']); ?></td>

<td><?= htmlspecialchars($student['email']); ?></td>

<td><?= htmlspecialchars($student['phone']); ?></td>

<td><?= htmlspecialchars($student['country']); ?></td>

<td><?= htmlspecialchars($student['city']); ?></td>

<td><?= htmlspecialchars($student['qualification']); ?></td>

<td><?= htmlspecialchars($student['occupation']); ?></td>

<td>

<?php

$statusColor='secondary';

if($student['status']=='Active'){
    $statusColor='success';
}elseif($student['status']=='Pending'){
    $statusColor='warning';
}elseif($student['status']=='Suspended'){
    $statusColor='danger';
}

?>

<span class="badge bg-<?= $statusColor; ?>">

<?= htmlspecialchars($student['status']); ?>

</span>

</td>

<td>

<?= date('d M Y',strtotime($student['created_at'])); ?>

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