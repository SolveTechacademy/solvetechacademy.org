<?php

require_once '../../config/student_auth.php';

if (!isset($_GET['lesson']) || !is_numeric($_GET['lesson'])) {
    exit;
}

$lessonId = (int)$_GET['lesson'];
$studentId = $_SESSION['student_db_id'];

$stmt = $pdo->prepare("
SELECT id
FROM lesson_progress
WHERE student_id=?
AND lesson_id=?
LIMIT 1
");

$stmt->execute([
    $studentId,
    $lessonId
]);

if($stmt->rowCount()){

    $pdo->prepare("
    UPDATE lesson_progress
    SET
        completed=1,
        completed_at=NOW()
    WHERE
        student_id=?
    AND lesson_id=?
    ")->execute([
        $studentId,
        $lessonId
    ]);

}else{

    $pdo->prepare("
    INSERT INTO lesson_progress
    (
        student_id,
        lesson_id,
        completed,
        completed_at
    )
    VALUES
    (
        ?,?,?,NOW()
    )
    ")->execute([
        $studentId,
        $lessonId,
        1
    ]);

}

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

if (isset($_GET['next']) && is_numeric($_GET['next'])) {

    header("Location: lesson.php?id=" . (int)$_GET['next']);
    exit;

}

header("Location: lesson.php?id=" . $lessonId);
exit;