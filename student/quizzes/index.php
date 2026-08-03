<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../../login.php");
    exit();
}

$student_db_id = $_SESSION['student_db_id'];

$stmt = $pdo->prepare("
SELECT *
FROM students
WHERE id = ?
LIMIT 1
");

$stmt->execute([$student_db_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    session_destroy();
    header("Location: ../../login.php");
    exit();
}

if (strcasecmp(trim($student['status']), 'Active') !== 0) {
    session_destroy();
    $_SESSION['error'] = "Your account is awaiting approval.";
    header("Location: ../../login.php");
    exit();
}

$quizQuery = $pdo->prepare("
SELECT
    q.id,
    cm.id AS module_id,
    q.title,
    q.description,
    q.pass_mark,
    q.duration,
    q.attempts,
    c.course_title,
    cm.module_title,

    (
        SELECT COUNT(*)
        FROM quiz_attempts qa
        WHERE qa.quiz_id=q.id
        AND qa.student_id=?
    ) AS attempts_used

FROM registrations r

INNER JOIN courses c
ON c.id=r.course_id

INNER JOIN course_modules cm
ON cm.course_id=c.id

INNER JOIN quizzes q
ON q.module_id=cm.id

WHERE
r.student_id=?
AND r.approval_status='Approved'
AND q.status='Active'

ORDER BY
c.course_title,
cm.module_order,
q.title
");

$quizQuery->execute([
    $student_db_id,
    $student_db_id
]);

$quizzes = $quizQuery->fetchAll(PDO::FETCH_ASSOC);
foreach ($quizzes as &$quiz) {

    /*
    |--------------------------------------------------------------------------
    | Total Lessons
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM lessons
        WHERE module_id=?
    ");

    $stmt->execute([$quiz['module_id']]);

    $totalLessons = $stmt->fetchColumn();

    /*
    |--------------------------------------------------------------------------
    | Completed Lessons
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM lesson_progress lp
        INNER JOIN lessons l
            ON l.id=lp.lesson_id
        WHERE l.module_id=?
        AND lp.student_id=?
        AND lp.completed=1
    ");

    $stmt->execute([
        $quiz['module_id'],
        $student_db_id
    ]);

    $completedLessons = $stmt->fetchColumn();

    $quiz['unlocked'] =
        ($totalLessons > 0 &&
         $completedLessons == $totalLessons);

}

unset($quiz);
?>

<?php

$pageTitle = "My Quizzes";

require_once '../../includes/header.php';

?>

<div class="container main-content">

<div class="container mt-4">

<div class="card mb-4">

<div class="card-header bg-white">

<h3 class="fw-bold text-primary mb-0">

📝 My Quizzes

</h3>

<p class="text-muted mb-0 mt-2">

Complete your lessons before attempting quizzes.

</p>

</div>

<div class="card-body">

<?php if(count($quizzes)==0){ ?>

<div class="alert alert-info mb-0">
No quizzes are currently available.
</div>

<?php } else { ?>

<table class="table table-hover align-middle">

<thead class="table-primary">

<tr>

<th>Course</th>
<th>Module</th>
<th>Quiz</th>
<th>Duration</th>
<th>Pass Mark</th>
<th>Attempts</th>
<th width="150">Action</th>

</tr>

</thead>

<tbody>

<?php foreach($quizzes as $quiz){ ?>

<tr>

<td><?= htmlspecialchars($quiz['course_title']) ?></td>

<td><?= htmlspecialchars($quiz['module_title']) ?></td>

<td>

<strong><?= htmlspecialchars($quiz['title']) ?></strong>

<?php if(!empty($quiz['description'])){ ?>

<br>

<small class="text-muted">

<?= htmlspecialchars($quiz['description']) ?>

</small>

<?php } ?>

</td>

<td>

<?= (int)$quiz['duration'] ?> mins

</td>

<td>

<?= (int)$quiz['pass_mark'] ?>%

</td>

<td>

<?= (int)$quiz['attempts_used'] ?>

/

<?= (int)$quiz['attempts'] ?>

</td>

<td>

<?php if(!$quiz['unlocked']){ ?>

<button
class="btn btn-primary rounded-pill w-100"
disabled>

Complete Lessons First

</button>

<?php } elseif($quiz['attempts_used'] >= $quiz['attempts']){ ?>

<button
class="btn btn-outline-secondary rounded-pill w-100"
disabled>

Attempts Exhausted

</button>

<?php } else { ?>

<a
href="take.php?id=<?= $quiz['id'] ?>"
class="btn btn-success btn-sm w-100">

Start Quiz

</a>

<?php } ?>

<button class="btn btn-secondary btn-sm w-100" disabled>

Attempts Exhausted

</button>

<?php }else{ ?>

<a
href="take.php?id=<?= $quiz['id'] ?>"
class="btn btn-success btn-sm w-100">

Start Quiz

</a>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php } ?>

</div>

</div>

</div>

<?php require_once '../../includes/footer.php'; ?>