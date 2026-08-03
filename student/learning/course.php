<?php

require_once '../../config/student_auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$courseId = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT *
FROM courses
WHERE id=?
LIMIT 1
");
$stmt->execute([$courseId]);

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| CHECK MODULE PROGRESS
|--------------------------------------------------------------------------
*/

foreach ($modules as &$module) {

    $progress = $pdo->prepare("
        SELECT completed
        FROM student_module_progress
        WHERE student_id = ?
        AND module_id = ?
        LIMIT 1
    ");

    $progress->execute([
        $_SESSION['student_db_id'],
        $module['id']
    ]);

    $moduleProgress = $progress->fetch(PDO::FETCH_ASSOC);

    $module['completed'] =
        $moduleProgress['completed'] ?? 0;
}
unset($module);

if (!$course) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
SELECT *
FROM course_modules
WHERE course_id=?
ORDER BY module_order ASC,id ASC
");

$stmt->execute([$courseId]);

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="container-fluid">

<div class="card mb-4">

<div class="card-body">

<div class="row align-items-center">

<div class="col-lg-9">

<span class="badge bg-primary mb-3">

Course

</span>

<h2 class="fw-bold text-primary">

<?= htmlspecialchars($course['course_title']) ?>

</h2>

<p class="text-muted">

<?= nl2br(htmlspecialchars($course['description'])) ?>

</p>

<div class="mt-3">

<span class="badge bg-success">

<?= htmlspecialchars($course['level']) ?>

</span>

<span class="badge bg-dark ms-2">

<?= htmlspecialchars($course['duration']) ?>

</span>

</div>

</div>

<div class="col-lg-3 text-center">

<i
class="fas fa-graduation-cap"
style="font-size:90px;color:#FF8A3D;">

</i>

</div>

</div>

</div>

</div>

<div class="card shadow-sm">

<div class="card-header">

<h4 class="fw-bold text-primary mb-0">

📚 Learning Modules

</h4>

</div>

<div class="list-group list-group-flush">

<?php if(count($modules)): ?>

<?php foreach($modules as $module): ?>

<a
href="module.php?id=<?= $module['id']; ?>"
class="list-group-item list-group-item-action py-3">

<div class="d-flex justify-content-between align-items-center">

<div>

<h5 class="mb-1">

<?= htmlspecialchars($module['module_title']); ?>

</h5>

<small class="text-muted">

Click to begin learning

</small>

</div>

<div>

<?php if($module['completed']){ ?>

<span class="badge bg-success">

Completed

</span>

<?php }else{ ?>

<span class="badge bg-warning text-dark">

Start Module

</span>

<?php } ?>

</div>

</div>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="list-group-item">

<div class="text-center py-5">

<i
class="fas fa-book-open"
style="font-size:60px;color:#FF8A3D;">

</i>

<h4 class="mt-3">

No Modules Yet

</h4>

<p class="text-muted">

Your instructor will add learning modules soon.

</p>

</div>

</div>

<?php endif; ?>

</div>

</div>

</div>

<?php require_once '../../includes/footer.php'; ?>