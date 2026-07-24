<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$title        = trim($_POST['title']);
$message      = trim($_POST['message']);
$audience     = trim($_POST['audience']);
$course_id    = !empty($_POST['course_id']) ? $_POST['course_id'] : NULL;
$status       = trim($_POST['status']);
$publish_date = trim($_POST['publish_date']);
$expiry_date  = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : NULL;

if (
    empty($title) ||
    empty($message) ||
    empty($audience) ||
    empty($status) ||
    empty($publish_date)
) {
    die("Please fill in all required fields.");
}

try {

    $stmt = $pdo->prepare("
        INSERT INTO announcements
        (
            title,
            message,
            audience,
            course_id,
            status,
            publish_date,
            expiry_date
        )
        VALUES
        (
            :title,
            :message,
            :audience,
            :course_id,
            :status,
            :publish_date,
            :expiry_date
        )
    ");

    $stmt->execute([
        ':title'        => $title,
        ':message'      => $message,
        ':audience'     => $audience,
        ':course_id'    => $course_id,
        ':status'       => $status,
        ':publish_date' => $publish_date,
        ':expiry_date'  => $expiry_date
    ]);

    header("Location: index.php?success=1");
    exit;

} catch(PDOException $e) {

    die("Database Error: " . $e->getMessage());

}