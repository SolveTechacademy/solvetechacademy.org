<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$module_id   = isset($_POST['module_id']) ? (int) $_POST['module_id'] : 0;
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$pass_mark   = isset($_POST['pass_mark']) ? (int) $_POST['pass_mark'] : 70;
$duration    = isset($_POST['duration']) ? (int) $_POST['duration'] : 30;
$attempts    = isset($_POST['attempts']) ? (int) $_POST['attempts'] : 3;
$status      = $_POST['status'] ?? 'Active';

if (
    $module_id <= 0 ||
    $title === '' ||
    !in_array($status, ['Active', 'Inactive'])
) {
    $_SESSION['error'] = "Please complete all required fields.";
    header("Location: create.php");
    exit();
}

try {

    $stmt = $pdo->prepare("
        INSERT INTO quizzes
        (
            module_id,
            title,
            description,
            pass_mark,
            duration,
            attempts,
            status
        )
        VALUES
        (
            :module_id,
            :title,
            :description,
            :pass_mark,
            :duration,
            :attempts,
            :status
        )
    ");

    $stmt->execute([
        ':module_id'   => $module_id,
        ':title'       => $title,
        ':description' => $description,
        ':pass_mark'   => $pass_mark,
        ':duration'    => $duration,
        ':attempts'    => $attempts,
        ':status'      => $status
    ]);

    $_SESSION['success'] = "Quiz created successfully.";

    header("Location: index.php");
    exit();

} catch (PDOException $e) {

    $_SESSION['error'] = "Database Error: " . $e->getMessage();

    header("Location: create.php");
    exit();
}