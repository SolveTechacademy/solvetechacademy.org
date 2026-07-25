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

$course = $stmt->fetch(PDO::FETCH_ASSOC);

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

<div class="card shadow-sm mb-4">

<div class="card-body">

<h2><?= htmlspecialchars($course['course_title']) ?></h2>

<p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

</div>

</div>

<div class="card shadow-sm">

<div class="card-header">

<h5 class="mb-0">Course Modules</h5>

</div>

<div class="list-group list-group-flush">

<?php if(count($modules)): ?>

<?php foreach($modules as $module): ?>

<a
href="module.php?id=<?= $module['id']; ?>"
class="list-group-item list-group-item-action">

<?= htmlspecialchars($module['module_title']); ?>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="list-group-item">

No modules available.

</div>

<?php endif; ?>

</div>

</div>

</div>

<?php require_once '../../includes/footer.php'; ?>