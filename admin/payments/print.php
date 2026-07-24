<?php
require_once '../includes/auth.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid payment.");
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
        WHERE registration_id=p.registration_id
        AND status='Approved'
    ) AS total_paid
FROM payments p
INNER JOIN registrations r ON p.registration_id=r.id
INNER JOIN students s ON r.student_id=s.id
INNER JOIN courses c ON r.course_id=c.id
WHERE p.id=?
LIMIT 1
");

$stmt->execute([$id]);
$payment=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$payment){
    die("Payment not found.");
}

$balance=$payment['course_fee']-$payment['total_paid'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Payment Receipt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f5f5f5}
.receipt{max-width:850px;margin:30px auto;background:#fff;padding:40px;border:1px solid #ddd}
.header{text-align:center;border-bottom:2px solid #0d6efd;padding-bottom:20px;margin-bottom:25px}
.header img{width:80px}
.table th{width:220px}
.footer{text-align:center;margin-top:40px;font-size:14px;color:#666}
@media print{
body{background:#fff}
.no-print{display:none}
.receipt{border:none;margin:0;max-width:100%}
}
</style>
</head>
<body>

<div class="receipt">

<div class="header">
<img src="../../assets/images/icon.png" alt="Logo">
<h2>SolveTech Academy</h2>
<p>Official Payment Receipt</p>
</div>

<h5>Student Information</h5>
<table class="table table-bordered">
<tr><th>Student ID</th><td><?= htmlspecialchars($payment['student_id']); ?></td></tr>
<tr><th>Full Name</th><td><?= htmlspecialchars($payment['fullname']); ?></td></tr>
<tr><th>Email</th><td><?= htmlspecialchars($payment['email']); ?></td></tr>
<tr><th>Phone</th><td><?= htmlspecialchars($payment['phone']); ?></td></tr>
</table>

<h5>Course Information</h5>
<table class="table table-bordered">
<tr><th>Course</th><td><?= htmlspecialchars($payment['course_title']); ?></td></tr>
<tr><th>Course Fee</th><td><?= number_format($payment['course_fee']); ?> FCFA</td></tr>
</table>

<h5>Payment Information</h5>
<table class="table table-bordered">
<tr><th>Receipt No.</th><td><?= htmlspecialchars($payment['payment_id']); ?></td></tr>
<tr><th>Amount Paid</th><td><?= number_format($payment['amount']); ?> FCFA</td></tr>
<tr><th>Total Paid</th><td><?= number_format($payment['total_paid']); ?> FCFA</td></tr>
<tr><th>Outstanding Balance</th><td><?= number_format($balance); ?> FCFA</td></tr>
<tr><th>Method</th><td><?= htmlspecialchars($payment['payment_method']); ?></td></tr>
<tr><th>Transaction ID</th><td><?= htmlspecialchars($payment['transaction_id'] ?: 'N/A'); ?></td></tr>
<tr><th>Date</th><td><?= date('d F Y H:i',strtotime($payment['created_at'])); ?></td></tr>
</table>

<div class="footer">
<p><strong>SolveTech Academy</strong></p>
<p>Head Office: NewTown, Airport - Douala</p>
<p>+237 654 178 586 | +237 687 09 58 87</p>
<p>info@solvetechacademy.org</p>
<p>www.solvetechacademy.org</p>
</div>

<div class="text-center mt-4 no-print">
<button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
<a href="index.php" class="btn btn-secondary">Back</a>
</div>

</div>

<script>
window.onload=function(){
    window.print();
};
</script>

</body>
</html>
