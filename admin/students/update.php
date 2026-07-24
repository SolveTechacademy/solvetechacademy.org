<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$id       = (int) $_POST['id'];
$fullname = trim($_POST['fullname']);
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);
$status   = trim($_POST['status']);

$stmt = $pdo->prepare("
    UPDATE students
    SET
        fullname = ?,
        email = ?,
        phone = ?,
        status = ?
    WHERE id = ?
");

$success = $stmt->execute([
    $fullname,
    $email,
    $phone,
    $status,
    $id
]);

if ($success) {
    $_SESSION['success'] = "Student updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update student.";
}

header("Location: index.php");
exit();