<?php

require_once '../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

try {

    $pdo->beginTransaction();

    // Activate student account
    $stmt = $pdo->prepare("
        UPDATE students
        SET status = 'Active'
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    // Approve student's registration
    $stmt = $pdo->prepare("
        UPDATE registrations
        SET approval_status = 'Approved'
        WHERE student_id = ?
    ");
    $stmt->execute([$id]);

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();

}

header("Location: index.php?success=approved");
exit;