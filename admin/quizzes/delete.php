<?php
require_once '../includes/auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error'] = "Invalid quiz.";
    header("Location:index.php");
    exit;
}

try {

    // Check if the quiz exists
    $stmt = $pdo->prepare("
        SELECT id
        FROM quizzes
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = "Quiz not found.";
        header("Location:index.php");
        exit;
    }

    // Delete related student answers
    $stmt = $pdo->prepare("
        DELETE FROM quiz_answers
        WHERE attempt_id IN (
            SELECT id FROM quiz_attempts WHERE quiz_id = ?
        )
    ");
    $stmt->execute([$id]);

    // Delete student attempts
    $stmt = $pdo->prepare("
        DELETE FROM quiz_attempts
        WHERE quiz_id = ?
    ");
    $stmt->execute([$id]);

    // Delete quiz questions
    $stmt = $pdo->prepare("
        DELETE FROM quiz_questions
        WHERE quiz_id = ?
    ");
    $stmt->execute([$id]);

    // Delete the quiz
    $stmt = $pdo->prepare("
        DELETE FROM quizzes
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    $_SESSION['success'] = "Quiz deleted successfully.";

} catch (PDOException $e) {

    $_SESSION['error'] = $e->getMessage();

}

header("Location:index.php");
exit;