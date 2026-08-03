<?php

require_once '../../config/student_auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$moduleId = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT *
FROM course_modules
WHERE id=?
LIMIT 1
");

$stmt->execute([$moduleId]);

$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Lesson Progress
|--------------------------------------------------------------------------
*/

foreach ($lessons as &$lesson) {

    $progress = $pdo->prepare("
        SELECT completed
        FROM lesson_progress
        WHERE student_id = ?
        AND lesson_id = ?
        LIMIT 1
    ");

    $progress->execute([
        $_SESSION['student_db_id'],
        $lesson['id']
    ]);

    $record = $progress->fetch(PDO::FETCH_ASSOC);

    $lesson['completed'] = ($record && $record['completed'] == 1);
}

unset($lesson);

if(!$module){
    header("Location:index.php");
    exit;
}

$stmt = $pdo->prepare("
SELECT *
FROM lessons
WHERE module_id=?
ORDER BY lesson_order ASC,id ASC
");

$stmt->execute([$moduleId]);

$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="container-fluid">

<div class="card mb-4">

<div class="card-body">

<div class="row align-items-center">

<div class="col-lg-9">

<span class="badge bg-primary mb-3">

Learning Module

</span>

<h2 class="fw-bold text-primary">

<?= htmlspecialchars($module['module_title']) ?>

</h2>

<p class="text-muted">

Complete every lesson below to unlock the quiz and earn your certificate.

</p>

</div>

<div class="col-lg-3 text-center">

<i class="fas fa-book-reader"
style="font-size:90px;color:#FF8A3D;"></i>

</div>

</div>

</div>

</div>

<div class="card">

<div class="card-header bg-white">

<h4 class="fw-bold text-primary mb-0">

📖 Module Lessons

</h4>

</div>

<div class="list-group list-group-flush"></div>

<div class="list-group list-group-flush">

<?php if($lessons): ?>

<?php foreach($lessons as $lesson): ?>

<a
href="lesson.php?id=<?= $lesson['id']; ?>"
class="list-group-item list-group-item-action py-3">

<div class="d-flex justify-content-between align-items-center">

<div>

<h5 class="mb-1">

<?= htmlspecialchars($lesson['lesson_title']); ?>

</h5>

<small class="text-muted">

Click to start or continue this lesson.

</small>

</div>

<div>

<?php if($lesson['completed']){ ?>

<span class="badge bg-success">

<i class="fas fa-check-circle"></i>

Completed

</span>

<?php }else{ ?>

<span class="badge bg-warning text-dark">

<i class="fas fa-play"></i>

Start Lesson

</span>

<?php } ?>

</div>

</div>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="list-group-item">

<div class="text-center py-5">

<i class="fas fa-book-open"
style="font-size:60px;color:#FF8A3D;"></i>

<h4 class="mt-3">

No Lessons Available

</h4>

<p class="text-muted">

Lessons will appear here once they are published.

</p>

</div>

</div>

<?php endif; ?>

</div>

</div>

</div>
<div class="text-center mt-4">

<a
href="index.php"
class="btn btn-outline-secondary rounded-pill">

<i class="fas fa-arrow-left"></i>

Back to Courses

</a>

</div>

<?php require_once '../../includes/footer.php'; ?>