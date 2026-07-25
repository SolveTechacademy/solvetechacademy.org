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

$module = $stmt->fetch(PDO::FETCH_ASSOC);

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

<div class="card shadow-sm">

<div class="card-header">

<h4><?= htmlspecialchars($module['module_title']) ?></h4>

</div>

<div class="list-group list-group-flush">

<?php if($lessons): ?>

<?php foreach($lessons as $lesson): ?>

<a
class="list-group-item list-group-item-action"
href="lesson.php?id=<?= $lesson['id']; ?>">

<?= htmlspecialchars($lesson['lesson_title']); ?>

</a>

<?php endforeach; ?>

<?php else: ?>

<div class="list-group-item">

No lessons available.

</div>

<?php endif; ?>

</div>

</div>

</div>

<?php require_once '../../includes/footer.php'; ?>