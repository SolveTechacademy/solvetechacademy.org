<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:index.php");
    exit;
}

$quiz_id = (int)($_POST['quiz_id'] ?? 0);

$question = trim($_POST['question'] ?? '');

$option_a = trim($_POST['option_a'] ?? '');
$option_b = trim($_POST['option_b'] ?? '');
$option_c = trim($_POST['option_c'] ?? '');
$option_d = trim($_POST['option_d'] ?? '');

$correct_option = strtoupper(trim($_POST['correct_option'] ?? ''));

$marks = (int)($_POST['marks'] ?? 1);

if (
    !$quiz_id ||
    $question == '' ||
    $option_a == '' ||
    $option_b == '' ||
    $option_c == '' ||
    $option_d == '' ||
    !in_array($correct_option, ['A','B','C','D'])
) {

    $_SESSION['error'] = "Please complete all required fields.";

    header("Location:add_question.php?quiz_id=".$quiz_id);

    exit;
}

try {

    $stmt = $pdo->prepare("
        INSERT INTO quiz_questions
        (
            quiz_id,
            question,
            option_a,
            option_b,
            option_c,
            option_d,
            correct_option,
            marks
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $quiz_id,
        $question,
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $correct_option,
        $marks
    ]);

    $_SESSION['success'] = "Question added successfully.";

} catch (PDOException $e) {

    $_SESSION['error'] = $e->getMessage();

}

header("Location:questions.php?quiz_id=".$quiz_id);
exit;