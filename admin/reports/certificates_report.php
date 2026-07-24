<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Certificates Report";

try{

$stmt = $pdo->query("
SELECT
c.*,
s.fullname,
co.course_title
FROM certificates c
LEFT JOIN students s
ON c.student_id=s.id
LEFT JOIN courses co
ON c.course_id=co.id
ORDER BY c.id DESC
");

$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalCertificates = count($certificates);

$totalIssued = $pdo->query("
SELECT COUNT(*)
FROM certificates
WHERE status='Issued'
")->fetchColumn();

$totalRevoked = $pdo->query("
SELECT COUNT(*)
FROM certificates
WHERE status='Revoked'
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

<h2 class="fw-bold">Certificates Report</h2>

<p class="text-muted">
Issued certificates within the LMS.
</p>

</div>

<div>

<button onclick="window.print();" class="btn btn-dark">
<i class="fas fa-print"></i> Print
</button>

<a href="export_excel.php?type=certificates" class="btn btn-success">
<i class="fas fa-file-excel"></i> Excel
</a>

<a href="export_pdf.php?type=certificates" class="btn btn-danger">
<i class="fas fa-file-pdf"></i> PDF
</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-4">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Certificates</h6>

<h2><?= $totalCertificates ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow-sm">

<div class="card-body">

<h6>Issued</h6>

<h2 class="text-success"><?= $totalIssued ?></h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow-sm">

<div class="card-body">

<h6>Revoked</h6>

<h2 class="text-danger"><?= $totalRevoked ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-info text-white">

<h5 class="mb-0">

Certificates Report

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="dataTable" class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>#</th>
<th>Certificate No.</th>
<th>Student</th>
<th>Course</th>
<th>Registration ID</th>
<th>Grade</th>
<th>Issue Date</th>
<th>Completion Date</th>
<th>PDF</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($certificates as $certificate): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($certificate['certificate_number']); ?></td>

<td><?= htmlspecialchars($certificate['fullname'] ?? 'N/A'); ?></td>

<td><?= htmlspecialchars($certificate['course_title'] ?? 'N/A'); ?></td>

<td><?= htmlspecialchars($certificate['registration_id']); ?></td>

<td><?= htmlspecialchars($certificate['grade']); ?></td>

<td>

<?= !empty($certificate['issue_date']) ? date('d M Y',strtotime($certificate['issue_date'])) : '-'; ?>

</td>

<td>

<?= !empty($certificate['completion_date']) ? date('d M Y',strtotime($certificate['completion_date'])) : '-'; ?>

</td>

<td>

<?php if(!empty($certificate['pdf_file'])): ?>

<a href="../../uploads/certificates/<?= htmlspecialchars($certificate['pdf_file']); ?>"
target="_blank"
class="btn btn-sm btn-primary">

<i class="fas fa-file-pdf"></i>

View

</a>

<?php else: ?>

<span class="badge bg-secondary">

No PDF

</span>

<?php endif; ?>

</td>

<td>

<?php if($certificate['status']=="Issued"): ?>

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

$(function(){

$('#dataTable').DataTable({

responsive:true,

pageLength:10,

order:[[0,'desc']]

});

});

</script>