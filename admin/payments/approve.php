<?php

require_once '../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid payment.";
    header("Location: index.php");
    exit;
}

$paymentId = (int) $_GET['id'];

try {

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT
            p.id,
            p.registration_id,
            r.student_id
        FROM payments p
        INNER JOIN registrations r
            ON r.id = p.registration_id
        WHERE p.id = ?
    ");

    $stmt->execute([$paymentId]);

    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        throw new Exception("Payment not found.");
    }

    $stmt = $pdo->prepare("
        UPDATE payments
        SET status='Approved'
        WHERE id=?
    ");

    $stmt->execute([$paymentId]);

    $stmt = $pdo->prepare("
        UPDATE registrations
        SET approval_status='Approved'
        WHERE id=?
    ");

    $stmt->execute([$payment['registration_id']]);

    $stmt = $pdo->prepare("
        UPDATE students
        SET status='Active'
        WHERE id=?
    ");

    $stmt->execute([$payment['student_id']]);

    $pdo->commit();

    $_SESSION['success'] = "Payment approved successfully.";

} catch (Exception $e) {

    $pdo->rollBack();

    $_SESSION['error'] = $e->getMessage();

}

header("Location: index.php");
exit;