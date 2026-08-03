<?php

require_once '../../config/auth.php';

$pageTitle = "My Learning";

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../helpers/student_engine.php';

if (!isset($_SESSION['student_db_id'])) {
    header("Location: ../../login.php");
    exit();
}

$student_id = $_SESSION['student_db_id'];
$stmt = $pdo->prepare("
SELECT
    c.*,
    r.training_mode,
    r.start_date
FROM registrations r
INNER JOIN courses c
    ON c.id = r.course_id
LEFT JOIN student_course_progress scp
    ON scp.course_id = c.id
    AND scp.student_id = r.student_id
WHERE
    r.student_id = ?
AND
    r.approval_status = 'Approved'
ORDER BY c.course_title ASC
");

$stmt->execute([$student_id]);

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container-fluid">

   <div class="card mb-4">

<div class="card-body">

<?php

$hour = date("H");

if($hour < 12){

$greeting = "☀ Good Morning";

}elseif($hour < 17){

$greeting = "🌤 Good Afternoon";

}else{

$greeting = "🌙 Good Evening";

}

?>

<h2 class="fw-bold">

<?= $greeting; ?>,

<?= htmlspecialchars($_SESSION['student_name'] ?? 'Student'); ?>

</h2>

<p class="text-muted mb-0">

Continue your learning journey.

You have

<strong>

<?= count($courses); ?>

</strong>

active course(s).

</p>

</div>

</div>

    <div class="row">
    <?php if(count($courses) > 0): ?>

<?php foreach($courses as $course): ?>

<div class="col-12 col-md-6 col-lg-4 mb-4">

<div class="card h-100">

<?php if(!empty($course['thumbnail'])): ?>

<img
src="../../assets/uploads/courses/<?= $course['thumbnail']; ?>"
class="card-img-top"
style="height:220px;object-fit:cover;">

<?php endif; ?>

<div class="card-body d-flex flex-column">

<span class="badge bg-primary mb-2">

<?= htmlspecialchars($course['level']); ?>

</span>
<h5 class="fw-bold">

<?= htmlspecialchars($course['course_title']); ?>

</h5>

<p class="text-muted small">

<?= htmlspecialchars($course['duration']); ?>
<br>

<span class="badge bg-success mt-2">

<?= htmlspecialchars($course['training_mode']); ?>

</span>

•
<?= htmlspecialchars($course['level']); ?>

</p>
<?php

$progress = getCourseProgress(
    $pdo,
    $student_id,
    $course['id']
);

?>

<div class="progress mb-3" style="height:10px;">

<div
class="progress-bar bg-info"
style="width:<?= $progress; ?>%;">

</div>

</div>

<div class="d-flex justify-content-between">

<span>

Progress

</span>

<strong>

<?= number_format($progress,0); ?>%

</strong>

</div>
<a
href="course.php?id=<?= $course['id']; ?>"
class="btn btn-primary mt-auto w-100 rounded-pill">

Continue Learning

</a>

</div>

</div>

</div>

<?php endforeach; ?>

<?php else: ?>
    <div class="col-12">

<div class="alert alert-info">

You have no approved courses yet.

</div>

</div>

<?php endif; ?>

</div>

</div>

<?php

require_once '../../includes/footer.php';

?>