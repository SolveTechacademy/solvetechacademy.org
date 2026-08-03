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

<div class="card mb-4">

<div class="card-body">

<div class="row align-items-center">

<div class="col-lg-9">

<span class="badge bg-primary mb-3">

Lesson

</span>

<h2 class="fw-bold text-primary">

<?= htmlspecialchars($lesson['lesson_title']) ?>

</h2>

<p class="text-muted mb-0">

Complete this lesson to unlock the next lesson and increase your overall course progress.

</p>

</div>

<div class="col-lg-3 text-center">

<i class="fas fa-play-circle"
style="font-size:90px;color:#FF8A3D;"></i>

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-body"></div>

<div class="card-body">

   <h5 class="fw-bold text-primary">

Course Progress

</h5>

<div class="progress mb-4"
style="height:18px;border-radius:20px;">

        <div
            class="progress-bar bg-warning"
            role="progressbar"
            style="width: <?= $percentage ?>%;"
            aria-valuenow="<?= $percentage ?>"
            aria-valuemin="0"
            aria-valuemax="100">

            <?= $percentage ?>%

        </div>

    </div>

<?php if(!empty($lesson['video_url'])): ?>

<div class="ratio ratio-16x9 mb-4 shadow rounded overflow-hidden">

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

<div class="alert alert-warning shadow-sm">

<?= nl2br(htmlspecialchars($lesson['notes'])) ?>

</div>

<?php endif; ?>

<?php if(!empty($lesson['file_path'])): ?>

<a
href="../../uploads/lessons/<?= urlencode($lesson['file_path']) ?>"
class="btn btn-outline-success rounded-pill"
target="_blank">

Download Lesson Material

</a>

<?php endif; ?>

<hr>

<div class="d-flex justify-content-between">

<div>

<?php if($previousLesson): ?>

<a
class="btn btn-outline-secondary rounded-pill"
href="lesson.php?id=<?= $previousLesson['id'] ?>">

Previous Lesson

</a>

<?php endif; ?>

</div>

<div>

<?php if($nextLesson): ?>

<a
class="btn btn-primary"class="btn btn-primary rounded-pill"
href="progress.php?lesson=<?= $lessonId ?>&next=<?= $nextLesson['id']; ?>">

Next Lesson →

</a>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>
<hr>

<div class="text-center mt-4">

<a
href="progress.php?lesson=<?= $lessonId ?>"
class="btn btn-success rounded-pill px-5 py-3">

<i class="fas fa-check-circle me-2"></i>

Mark Lesson Complete

</a>

</div>

<?php require_once '../../includes/footer.php'; ?>