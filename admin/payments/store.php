<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$payment_id      = trim($_POST['payment_id']);
$registration_id = (int) $_POST['registration_id'];
$amount          = (float) $_POST['amount'];
$payment_method  = trim($_POST['payment_method']);
$transaction_id  = trim($_POST['transaction_id']);

// Admin recorded payments are automatically approved
$status = "Pending";

$payment_proof = "";

// Upload payment proof
if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {

    $uploadDir = "../../uploads/payments/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES['payment_proof']['name']);
    $target = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target)) {
        $payment_proof = $filename;
    }
}

/*
|--------------------------------------------------------------------------
| Check Outstanding Balance
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        c.course_fee,
        COALESCE(SUM(p.amount), 0) AS total_paid
    FROM registrations r
    INNER JOIN courses c
        ON r.course_id = c.id
    LEFT JOIN payments p
        ON p.registration_id = r.id
        AND p.status = 'Approved'
    WHERE r.id = ?
    GROUP BY c.course_fee
");

$stmt->execute([$registration_id]);

$finance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$finance) {
    $_SESSION['error'] = "Registration not found.";
    header("Location: create.php");
    exit();
}

$courseFee = (float) $finance['course_fee'];
$totalPaid = (float) $finance['total_paid'];
$balance   = $courseFee - $totalPaid;

// Prevent overpayment
if ($amount > $balance) {

    $_SESSION['error'] =
        "Payment exceeds the outstanding balance of " .
        number_format($balance) . " FCFA.";

    header("Location: create.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Save Payment
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    INSERT INTO payments
    (
        payment_id,
        registration_id,
        amount,
        payment_method,
        transaction_id,
        payment_proof,
        status
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?)
");

$success = $stmt->execute([
    $payment_id,
    $registration_id,
    $amount,
    $payment_method,
    $transaction_id,
    $payment_proof,
    $status
]);

if ($success) {

    /*
    |--------------------------------------------------------------------------
    | Activate Registration
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        UPDATE registrations
        SET approval_status='Approved'
        WHERE id=?
    ");

    $stmt->execute([$registration_id]);

    /*
    |--------------------------------------------------------------------------
    | Activate Student
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        UPDATE students s
        INNER JOIN registrations r
            ON r.student_id=s.id
        SET s.status='Active'
        WHERE r.id=?
    ");

    $stmt->execute([$registration_id]);

    $_SESSION['success'] = "Payment recorded and student activated successfully.";

} else {

    $_SESSION['error'] = "Unable to record payment.";

}
header("Location: index.php");
exit();