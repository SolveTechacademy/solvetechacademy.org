<?php
require_once '../includes/auth.php';
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$payment){
    die("Payment not found.");
}

$balance = $payment['course_fee'] - $payment['total_paid'];

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html = '
<h2 style="text-align:center;">SolveTech Academy</h2>
<h4 style="text-align:center;">Official Payment Receipt</h4>
<hr>

<h3>Student Information</h3>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<tr><td><b>Student ID</b></td><td>'.htmlspecialchars($payment['student_id']).'</td></tr>
<tr><td><b>Full Name</b></td><td>'.htmlspecialchars($payment['fullname']).'</td></tr>
<tr><td><b>Email</b></td><td>'.htmlspecialchars($payment['email']).'</td></tr>
<tr><td><b>Phone</b></td><td>'.htmlspecialchars($payment['phone']).'</td></tr>
</table>

<h3>Course Information</h3>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<tr><td><b>Course</b></td><td>'.htmlspecialchars($payment['course_title']).'</td></tr>
<tr><td><b>Course Fee</b></td><td>'.number_format($payment['course_fee']).' FCFA</td></tr>
</table>

<h3>Payment Information</h3>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
<tr><td><b>Receipt No.</b></td><td>'.htmlspecialchars($payment['payment_id']).'</td></tr>
<tr><td><b>Amount Paid</b></td><td>'.number_format($payment['amount']).' FCFA</td></tr>
<tr><td><b>Total Paid</b></td><td>'.number_format($payment['total_paid']).' FCFA</td></tr>
<tr><td><b>Balance</b></td><td>'.number_format($balance).' FCFA</td></tr>
<tr><td><b>Method</b></td><td>'.htmlspecialchars($payment['payment_method']).'</td></tr>
<tr><td><b>Transaction ID</b></td><td>'.htmlspecialchars($payment['transaction_id'] ?: 'N/A').'</td></tr>
<tr><td><b>Date</b></td><td>'.date('d F Y H:i', strtotime($payment['created_at'])).'</td></tr>
</table>

<br><br>
<div style="text-align:center;font-size:12px;">
<strong>SolveTech Academy</strong><br>
Head Office: NewTown, Airport - Douala<br>
+237 654 178 586 | +237 687 09 58 87<br>
info@solvetechacademy.org<br>
www.solvetechacademy.org
</div>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream($payment['payment_id'].'-receipt.pdf', ['Attachment'=>false]);
exit;
