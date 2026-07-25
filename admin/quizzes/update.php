<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location:index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$module_id = (int)($_POST['module_id'] ?? 0);

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

$pass_mark = (int)($_POST['pass_mark'] ?? 0);
$duration = (int)($_POST['duration'] ?? 0);
$attempts = (int)($_POST['attempts'] ?? 1);

$status = trim($_POST['status'] ?? 'Inactive');

if (
    !$id ||
    !$module_id ||
    $title == '' ||
    $pass_mark <= 0 ||
    $duration <= 0 ||
    $attempts <= 0
) {
    $_SESSION['error'] = "Please complete all required fields.";
    header("Location:edit.php?id=".$id);
    exit;
}

try {

    $stmt = $pdo->prepare("
        UPDATE quizzes
        SET
            module_id = ?,
            title = ?,
            description = ?,
            pass_mark = ?,
            duration = ?,
            attempts = ?,
            status = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $module_id,
        $title,
        $description,
        $pass_mark,
        $duration,
        $attempts,
        $status,
        $id
    ]);

    $_SESSION['success'] = "Quiz updated successfully.";

} catch (PDOException $e) {

    $_SESSION['error'] = $e->getMessage();

}

header("Location:index.php");
exit;