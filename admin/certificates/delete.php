<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("DELETE FROM certificates WHERE id = ?");

if ($stmt->execute([$id])) {
    $_SESSION['success'] = "Certificate deleted successfully.";
} else {
    $_SESSION['error'] = "Failed to delete certificate.";
}

header("Location: index.php");
exit;