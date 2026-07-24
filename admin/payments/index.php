<?php

require_once '../includes/auth.php';

$stmt = $pdo->query("
SELECT
    p.*,
    s.fullname,
    s.student_id,
    c.course_title,
    c.course_fee,

    (
        SELECT COALESCE(SUM(amount),0)
        FROM payments
        WHERE registration_id = p.registration_id
        AND status='Approved'
    ) AS total_paid

FROM payments p

INNER JOIN registrations r
    ON p.registration_id = r.id

INNER JOIN students s
    ON r.student_id = s.id

INNER JOIN courses c
    ON r.course_id = c.id

ORDER BY p.created_at DESC
");

$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Payment Management";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<h2 class="mb-4">Payment Management</h2>

<div class="row mb-4">

    <div class="col-md-3"><div class="card shadow"><div class="card-body">
        <h6>Total Payments</h6>
        <h2><?= count($payments); ?></h2>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow"><div class="card-body">
        <h6>Approved</h6>
        <h2><?= count(array_filter($payments, fn($p)=>$p['status']=='Approved')); ?></h2>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow"><div class="card-body">
        <h6>Pending</h6>
        <h2><?= count(array_filter($payments, fn($p)=>$p['status']=='Pending')); ?></h2>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow"><div class="card-body">
        <h6>Total Revenue</h6>
        <h2><?= number_format(array_sum(array_column($payments,'amount')),0); ?></h2>
    </div></div></div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
<h5 class="mb-0">Payments</h5>
<a href="create.php" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> Record Payment</a>
</div>

<div class="card-body">

<table id="paymentsTable" class="table table-striped table-hover">
<thead>
<tr>
<th>#</th>
<th>Payment ID</th>
<th>Student</th>
<th>Course</th>
<th>Course Fee</th>
<th>Paid</th>
<th>Balance</th>
<th>Method</th>
<th>Status</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php foreach($payments as $payment):

$balance=$payment['course_fee']-$payment['total_paid'];

if($balance<=0){
    $paymentStatus="Paid";
}elseif($payment['total_paid']>0){
    $paymentStatus="Partially Paid";
}else{
    $paymentStatus="Unpaid";
}
?>

<tr>

<td><?= $payment['id']; ?></td>
<td><?= htmlspecialchars($payment['payment_id']); ?></td>
<td><?= htmlspecialchars($payment['fullname']); ?></td>
<td><?= htmlspecialchars($payment['course_title']); ?></td>
<td><?= number_format($payment['course_fee']); ?> FCFA</td>
<td><?= number_format($payment['total_paid']); ?> FCFA</td>

<td>
<?php if($balance<=0): ?>
<span class="text-success fw-bold"><?= number_format($balance); ?> FCFA</span>
<?php else: ?>
<span class="text-danger fw-bold"><?= number_format($balance); ?> FCFA</span>
<?php endif; ?>
</td>

<td><?= htmlspecialchars($payment['payment_method']); ?></td>

<td>
<?php
switch($paymentStatus){
case "Paid":
echo '<span class="badge bg-success">Paid</span>';
break;
case "Partially Paid":
echo '<span class="badge bg-warning text-dark">Partially Paid</span>';
break;
default:
echo '<span class="badge bg-danger">Unpaid</span>';
}
?>
</td>

<td><?= date('d M Y',strtotime($payment['created_at'])); ?></td>

<td>
<div class="btn-group" role="group">

<a href="view.php?id=<?= $payment['id']; ?>" class="btn btn-primary btn-sm" title="View Receipt">
<i class="fas fa-eye"></i>
</a>

<a href="print.php?id=<?= $payment['id']; ?>" class="btn btn-info btn-sm" title="Print Receipt" target="_blank">
<i class="fas fa-print"></i>
</a>

<a href="pdf.php?id=<?= $payment['id']; ?>" class="btn btn-secondary btn-sm" title="Download PDF" target="_blank">
<i class="fas fa-file-pdf"></i>
</a>

<a href="edit.php?id=<?= $payment['id']; ?>" class="btn btn-warning btn-sm" title="Edit Payment">
<i class="fas fa-edit"></i>
</a>

<a href="delete.php?id=<?= $payment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this payment?');">
<i class="fas fa-trash"></i>
</a>

</div>
</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>
</div>

<?php require_once '../includes/footer.php'; ?>
