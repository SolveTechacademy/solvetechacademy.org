<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id = intval($_POST['id']);
$status = trim($_POST['status']);
$amount = trim($_POST['amount']);
$reference = trim($_POST['reference']);

try {

    $stmt = $pdo->prepare("
        UPDATE payments
        SET
            amount = ?,
            payment_reference = ?,
            status = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([
        $amount,
        $reference,
        $status,
        $id
    ]);

    if ($status === 'Approved') {

        $stmt = $pdo->prepare("
            SELECT registration_id
            FROM payments
            WHERE id=?
        ");

        $stmt->execute([$id]);

        $registration = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registration) {

            $stmt = $pdo->prepare("
                UPDATE registrations
                SET approval_status='Approved'
                WHERE id=?
            ");

            $stmt->execute([
                $registration['registration_id']
            ]);

            $stmt = $pdo->prepare("
                UPDATE students s
                JOIN registrations r
                ON r.student_id=s.id
                SET s.status='Active'
                WHERE r.id=?
            ");

            $stmt->execute([
                $registration['registration_id']
            ]);
        }
    }

    $_SESSION['success'] = "Payment updated successfully.";

} catch (PDOException $e) {

    $_SESSION['error'] = $e->getMessage();

}

header("Location: index.php");
exit;