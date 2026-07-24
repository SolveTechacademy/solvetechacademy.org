<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Payments Report";

try{

$stmt = $pdo->query("
SELECT *
FROM payments
ORDER BY id DESC
");

$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPayments = count($payments);

$totalAmount = $pdo->query("
SELECT IFNULL(SUM(amount),0)
FROM payments
WHERE status='Approved'
")->fetchColumn();

$approved = $pdo->query("
SELECT COUNT(*)
FROM payments
WHERE status='Approved'
")->fetchColumn();

$pending = $pdo->query("
SELECT COUNT(*)
FROM payments
WHERE status='Pending'
")->fetchColumn();

$rejected = $pdo->query("
SELECT COUNT(*)
FROM payments
WHERE status='Rejected'
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

<h2 class="fw-bold">Payments Report</h2>

<p class="text-muted mb-0">
View all payment transactions.
</p>

</div>

<div>

<button onclick="window.print();" class="btn btn-dark">
<i class="fas fa-print"></i> Print
</button>

<a href="export_excel.php?type=payments" class="btn btn-success">
<i class="fas fa-file-excel"></i> Excel
</a>

<a href="export_pdf.php?type=payments" class="btn btn-danger">
<i class="fas fa-file-pdf"></i> PDF
</a>

</div>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Payments</h6>

<h2><?= $totalPayments ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body">

<h6>Total Revenue</h6>

<h2><?= number_format($totalAmount,2) ?></h2>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card shadow-sm">

<div class="card-body">

<h6>Approved</h6>

<h2 class="text-success"><?= $approved ?></h2>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card shadow-sm">

<div class="card-body">

<h6>Pending</h6>

<h2 class="text-warning"><?= $pending ?></h2>

</div>

</div>

</div>

<div class="col-md-2">

<div class="card shadow-sm">

<div class="card-body">

<h6>Rejected</h6>

<h2 class="text-danger"><?= $rejected ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-warning">

<h5 class="mb-0">
Payments Report
</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table id="dataTable" class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>

<th>#</th>
<th>Payment ID</th>
<th>Registration ID</th>
<th>Amount</th>
<th>Method</th>
<th>Transaction ID</th>
<th>Proof</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($payments as $payment): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($payment['payment_id']); ?></td>

<td><?= htmlspecialchars($payment['registration_id']); ?></td>

<td><?= number_format($payment['amount'],2); ?></td>

<td><?= htmlspecialchars($payment['payment_method']); ?></td>

<td><?= htmlspecialchars($payment['transaction_id']); ?></td>

<td>

<?php if(!empty($payment['payment_proof'])): ?>

<a href="../../uploads/payments/<?= htmlspecialchars($payment['payment_proof']); ?>" target="_blank" class="btn btn-sm btn-primary">

<i class="fas fa-eye"></i>

View

</a>

<?php else: ?>

<span class="badge bg-secondary">

No File

</span>

<?php endif; ?>

</td>

<td>

<?php

$color='secondary';

if($payment['status']=='Approved'){
    $color='success';
}elseif($payment['status']=='Pending'){
    $color='warning';
}elseif($payment['status']=='Rejected'){
    $color='danger';
}

?>

<span class="badge bg-<?= $color; ?>">

<?= htmlspecialchars($payment['status']); ?>

</span>

</td>

<td>

<?= date('d M Y',strtotime($payment['created_at'])); ?>

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