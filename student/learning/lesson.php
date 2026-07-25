<?php

require_once '../../config/student_auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$lessonId = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT l.*, cm.course_id
FROM lessons l
INNER JOIN course_modules cm
ON cm.id=l.module_id
WHERE l.id=?
LIMIT 1
");

$stmt->execute([$lessonId]);

$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$lesson){
    header("Location:index.php");
    exit;
}
$totalLessons=$pdo->prepare("
SELECT COUNT(*)
FROM lessons l
INNER JOIN course_modules m
ON m.id=l.module_id
WHERE m.course_id=?
");

$totalLessons->execute([
    $lesson['course_id']
]);

$totalLessons=$totalLessons->fetchColumn();

$completedLessons=$pdo->prepare("
SELECT COUNT(*)
FROM lesson_progress lp
INNER JOIN lessons l
ON l.id=lp.lesson_id
INNER JOIN course_modules m
ON m.id=l.module_id
WHERE
m.course_id=?
AND lp.student_id=?
AND lp.completed=1
");

$completedLessons->execute([
    $lesson['course_id'],
    $_SESSION['student_db_id']
]);

$completedLessons=$completedLessons->fetchColumn();

$percentage=0;

if($totalLessons>0){

$percentage=round(($completedLessons/$totalLessons)*100);

}

$previous = $pdo->prepare("
SELECT id
FROM lessons
WHERE module_id=?
AND lesson_order<?
ORDER BY lesson_order DESC
LIMIT 1
");

$previous->execute([
    $lesson['module_id'],
    $lesson['lesson_order']
]);

$previousLesson=$previous->fetch(PDO::FETCH_ASSOC);

$next=$pdo->prepare("
SELECT id
FROM lessons
WHERE module_id=?
AND lesson_order>?
ORDER BY lesson_order ASC
LIMIT 1
");

$next->execute([
    $lesson['module_id'],
    $lesson['lesson_order']
]);

$nextLesson=$next->fetch(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

?>

<div class="container-fluid">

<div class="card shadow">

<div class="card-header">

    <h3><?= htmlspecialchars($lesson['lesson_title']) ?></h3>

</div>

<div class="card-body">

    <div class="progress mb-4">

        <div
            class="progress-bar bg-success"
            role="progressbar"
            style="width: <?= $percentage ?>%;"
            aria-valuenow="<?= $percentage ?>"
            aria-valuemin="0"
            aria-valuemax="100">

            <?= $percentage ?>%

        </div>

    </div>

<?php if(!empty($lesson['video_url'])): ?>

<div class="ratio ratio-16x9 mb-4">

<iframe
src="<?= htmlspecialchars($lesson['video_url']) ?>"
allowfullscreen>

</iframe>

</div>

<?php endif; ?>

<?php if(!empty($lesson['description'])): ?>

<p>

<?= nl2br(htmlspecialchars($lesson['description'])) ?>

</p>

<?php endif; ?>

<?php if(!empty($lesson['notes'])): ?>

<div class="alert alert-info">

<?= nl2br(htmlspecialchars($lesson['notes'])) ?>

</div>

<?php endif; ?>

<?php if(!empty($lesson['file_path'])): ?>

<a
href="../../uploads/lessons/<?= urlencode($lesson['file_path']) ?>"
class="btn btn-success"
target="_blank">

Download Lesson Material

</a>

<?php endif; ?>

<hr>

<div class="d-flex justify-content-between">

<div>

<?php if($previousLesson): ?>

<a
class="btn btn-secondary"
href="lesson.php?id=<?= $previousLesson['id'] ?>">

Previous Lesson

</a>

<?php endif; ?>

</div>

<div>

<?php if($nextLesson): ?>

<a
class="btn btn-primary"
href="lesson.php?id=<?= $nextLesson['id'] ?>">

Next Lesson

</a>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>
<hr>

<a
href="progress.php?lesson=<?= $lessonId ?>"
class="btn btn-success">

<i class="fas fa-check-circle"></i>

Mark Lesson Complete

</a>

<?php require_once '../../includes/footer.php'; ?>