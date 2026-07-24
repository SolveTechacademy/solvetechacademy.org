<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$id           = $_POST['id'];
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
    die("All fields are required.");
}

try {

    $stmt = $pdo->prepare("
        UPDATE assignments
        SET
            lesson_id = ?,
            title = ?,
            instructions = ?,
            deadline = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $lesson_id,
        $title,
        $instructions,
        $deadline,
        $id
    ]);

    header("Location: index.php?updated=1");
    exit;

} catch(PDOException $e){

    die("Database Error: ".$e->getMessage());

}