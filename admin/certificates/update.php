<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$id = (int)$_POST['id'];
$registration_id = (int)$_POST['registration_id'];

$certificate_number = trim($_POST['certificate_number']);
$issue_date = $_POST['issue_date'];
$completion_date = $_POST['completion_date'];
$grade = trim($_POST['grade']);
$status = trim($_POST['status']);

if (
    empty($certificate_number) ||
    empty($issue_date) ||
    empty($completion_date) ||
    empty($grade) ||
    empty($status)
) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: edit.php?registration_id=".$registration_id);
    exit;
}

$check = $pdo->prepare("
SELECT id
FROM certificates
WHERE certificate_number = ?
AND id <> ?
LIMIT 1
");

$check->execute([$certificate_number, $id]);

if ($check->fetch()) {
    $_SESSION['error'] = "Certificate number already exists.";
    header("Location: edit.php?registration_id=".$registration_id);
    exit;
}

$stmt = $pdo->prepare("
UPDATE certificates
SET
certificate_number=?,
issue_date=?,
completion_date=?,
grade=?,
status=?
WHERE id=?
");

$success = $stmt->execute([
    $certificate_number,
    $issue_date,
    $completion_date,
    $grade,
    $status,
    $id
]);

if ($success) {
    $_SESSION['success'] = "Certificate updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update certificate.";
}

header("Location: index.php");
exit;