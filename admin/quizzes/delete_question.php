<?php
require_once '../includes/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error'] = "Invalid question.";
    header("Location:index.php");
    exit;
}

try {

    // Get quiz id before deleting
    $stmt = $pdo->prepare("
        SELECT quiz_id
        FROM quiz_questions
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$question) {
        $_SESSION['error'] = "Question not found.";
        header("Location:index.php");
        exit;
    }

    $quiz_id = $question['quiz_id'];

    $stmt = $pdo->prepare("
        DELETE FROM quiz_questions
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    $_SESSION['success'] = "Question deleted successfully.";

    header("Location:questions.php?quiz_id=".$quiz_id);
    exit;

} catch (PDOException $e) {

    $_SESSION['error'] = $e->getMessage();

    header("Location:index.php");
    exit;
}