<?php

require_once '../includes/auth.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT

    p.*,

    s.student_id,
    s.fullname,
    s.email,
    s.phone,

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

WHERE p.id=?

LIMIT 1
");

$stmt->execute([$id]);

$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    die("Payment record not found.");
}

$balance = $payment['course_fee'] - $payment['total_paid'];

$pageTitle = "Payment Receipt";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<style>

.receipt-card{

    max-width:900px;
    margin:auto;
    background:#fff;
    border-radius:10px;
    box-shadow:0 3px 15px rgba(0,0,0,.12);

}

.receipt-header{

    background:#0d6efd;
    color:#fff;
    padding:25px;
    text-align:center;

}

.receipt-header img{

    width:85px;
    margin-bottom:10px;

}

.receipt-title{

    font-size:30px;
    font-weight:bold;

}

.receipt-sub{

    font-size:15px;

}

.section-title{

    font-size:18px;
    font-weight:bold;
    color:#0d6efd;
    border-bottom:2px solid #eee;
    padding-bottom:8px;
    margin-bottom:15px;
    margin-top:30px;

}

.info-table td{

    padding:8px;

}

.amount-box{

    background:#f8f9fa;
    border:2px dashed #0d6efd;
    padding:20px;
    text-align:center;
    border-radius:8px;

}

.amount-box h1{

    margin:0;
    color:#198754;

}

.footer{

    margin-top:40px;
    text-align:center;
    color:#777;
    font-size:14px;

}

@media print{

.sidebar,
.topbar,
.btn,
.card-header{

display:none !important;

}

body{

background:#fff;

}

.receipt-card{

box-shadow:none;
border:none;

}

}

</style>

<div class="receipt-card">

<div class="receipt-header">

<img src="../../assets/images/icon.png" alt="Logo">

<div class="receipt-title">

SolveTech Academy

</div>

<div class="receipt-sub">

Empowering the Future Through Technology

</div>

</div>

<div class="card-body p-5">

<div class="d-flex justify-content-between">

<div>

<h4>

PAYMENT RECEIPT

</h4>

</div>

<div class="text-end">

<strong>

Receipt No

</strong>

<br>

<?= htmlspecialchars($payment['payment_id']); ?>

<br><br>

<strong>

Date

</strong>

<br>

<?= date('d F Y',strtotime($payment['created_at'])); ?>

</div>

</div>

<div class="section-title">

Student Information

</div>

<table class="table table-bordered info-table">

<tr>

<th width="220">

Student ID

</th>

<td>

<?= htmlspecialchars($payment['student_id']); ?>

</td>

</tr>

<tr>

<th>

Full Name

</th>

<td>

<?= htmlspecialchars($payment['fullname']); ?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?= htmlspecialchars($payment['email']); ?>

</td>

</tr>

<tr>

<th>

Phone

</th>

<td>

<?= htmlspecialchars($payment['phone']); ?>

</td>

</tr>

</table>

<div class="section-title">

Course Information

</div>

<table class="table table-bordered">

<tr>

<th width="220">

Course

</th>

<td>

<?= htmlspecialchars($payment['course_title']); ?>

</td>

</tr>

<tr>

<th>

Course Fee

</th>

<td>

<?= number_format($payment['course_fee']); ?>

FCFA

</td>

</tr>

</table>
<div class="section-title">

Payment Information

</div>

<table class="table table-bordered info-table">

<tr>

<th width="220">

Payment ID

</th>

<td>

<?= htmlspecialchars($payment['payment_id']); ?>

</td>

</tr>

<tr>

<th>

Amount Paid

</th>

<td>

<?= number_format($payment['amount']); ?> FCFA

</td>

</tr>

<tr>

<th>

Payment Method

</th>

<td>

<?= htmlspecialchars($payment['payment_method']); ?>

</td>

</tr>

<tr>

<th>

Transaction ID

</th>

<td>

<?= !empty($payment['transaction_id']) ? htmlspecialchars($payment['transaction_id']) : 'N/A'; ?>

</td>

</tr>

<tr>

<th>

Status

</th>

<td>

<?php

$statusClass = match($payment['status']){

    'Approved' => 'success',
    'Pending'  => 'warning',
    'Rejected' => 'danger',
    default    => 'secondary'

};

?>

<span class="badge bg-<?= $statusClass; ?>">

<?= htmlspecialchars($payment['status']); ?>

</span>

</td>

</tr>

</table>

<div class="section-title">

Financial Summary

</div>

<div class="row">

<div class="col-md-4">

<div class="amount-box">

<h6>

Course Fee

</h6>

<h2>

<?= number_format($payment['course_fee']); ?>

</h2>

<p class="mb-0">

FCFA

</p>

</div>

</div>

<div class="col-md-4">

<div class="amount-box">

<h6>

Total Paid

</h6>

<h2>

<?= number_format($payment['total_paid']); ?>

</h2>

<p class="mb-0">

FCFA

</p>

</div>

</div>

<div class="col-md-4">

<div class="amount-box">

<h6>

Outstanding Balance

</h6>

<h2 class="<?= ($balance <= 0) ? 'text-success' : 'text-danger'; ?>">

<?= number_format($balance); ?>

</h2>

<p class="mb-0">

FCFA

</p>

</div>

</div>

</div>

<?php if(!empty($payment['payment_proof'])): ?>

<div class="section-title">

Payment Proof

</div>

<div class="text-center">

<img
src="../../uploads/payments/<?= htmlspecialchars($payment['payment_proof']); ?>"
class="img-fluid rounded shadow"
style="max-height:500px;">

</div>

<?php endif; ?>

<hr class="my-5">

<div class="text-center">

<h5>

Thank You!

</h5>

<p>

Thank you for choosing <strong>SolveTech Academy</strong>.

This receipt serves as proof of payment.

</p>

<p>

Please keep this receipt for future reference.

</p>

</div>

<div class="footer">

<hr>

<h5>

SolveTech Academy

</h5>

<p>

Head Office: NewTown, Airport - Douala

</p>

<p>

+237 654 178 586 | +237 687 09 58 87

</p>

<p>

info@solvetechacademy.org

</p>

<p>

www.solvetechacademy.org

</p>

<p class="text-muted">

Receipt generated on

<?= date('d M Y H:i'); ?>

</p>

</div>

<div class="mt-4 text-center">

<button
class="btn btn-primary"
onclick="window.print();">

<i class="fas fa-print"></i>

Print Receipt

</button>

<a
href="index.php"
class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Back

</a>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>