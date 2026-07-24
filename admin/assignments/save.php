<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$lesson_id    = trim($_POST['lesson_id']);
$title        = trim($_POST['title']);
$instructions = trim($_POST['instructions']);
$deadline     = trim($_POST['deadline']);

if (
    empty($lesson_id) ||
    empty($title) ||
    empty($instructions) ||
    empty($deadline)
) {
    die("Please fill in all required fields.");
}

try {

    $stmt = $pdo->prepare("
        INSERT INTO assignments
        (
            lesson_id,
            title,
            instructions,
            deadline
        )
        VALUES
        (
            :lesson_id,
            :title,
            :instructions,
            :deadline
        )
    ");

    $stmt->execute([
        ':lesson_id'    => $lesson_id,
        ':title'        => $title,
        ':instructions' => $instructions,
        ':deadline'     => $deadline
    ]);

    header("Location: index.php?success=Assignment created successfully");
    exit;

} catch (PDOException $e) {

    die("Database Error: " . $e->getMessage());

}