<?php

require_once '../../config/auth.php';

$pageTitle = "My Learning";

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

if (!isset($_SESSION['student_db_id'])) {
    header("Location: ../../login.php");
    exit();
}

$student_id = $_SESSION['student_db_id'];
$stmt = $pdo->prepare("
SELECT
    c.*,
    r.training_mode,
    r.start_date,
    scp.progress,
    scp.completed
FROM registrations r
INNER JOIN courses c
    ON c.id = r.course_id
LEFT JOIN student_course_progress scp
    ON scp.course_id = c.id
    AND scp.student_id = r.student_id
WHERE
    r.student_id = ?
AND
    r.payment_status = 'Paid'
AND
    r.approval_status = 'Approved'
ORDER BY c.course_title ASC
");

$stmt->execute([$student_id]);

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container-fluid">

    <div class="row mb-4">

        <div class="col-12">

            <h2 class="fw-bold">
                My Learning
            </h2>

            <p class="text-muted">
                Continue learning where you left off.
            </p>

        </div>

    </div>

    <div class="row">
    <?php if(count($courses) > 0): ?>

<?php foreach($courses as $course): ?>

<div class="col-12 col-md-6 col-lg-4 mb-4"></div>
<div class="card h-100 shadow-sm border-0">

<?php if(!empty($course['thumbnail'])): ?>

<img
src="../../assets/uploads/courses/<?= $course['thumbnail']; ?>"
class="card-img-top"
style="height:220px;object-fit:cover;">

<?php endif; ?>

<div class="card-body d-flex flex-column"></div>
<h5 class="fw-bold">

<?= htmlspecialchars($course['course_title']); ?>

</h5>

<p class="text-muted small">

<?= htmlspecialchars($course['duration']); ?>

•
<?= htmlspecialchars($course['level']); ?>

</p>
<?php

$progress = $course['progress'] ?? 0;

?>

<div class="progress mb-3" style="height:10px;">

<div
class="progress-bar bg-info"
style="width:<?= $progress; ?>%;">

</div>

</div>

<p class="small">

Progress:

<strong>

<?= number_format($progress,0); ?>%

</strong>

</p>
<a
href="course.php?id=<?= $course['id']; ?>"
class="btn btn-primary mt-auto w-100">

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