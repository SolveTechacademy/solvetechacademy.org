<?php

require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Student not found.";
    header("Location: index.php");
    exit();
}
$id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Student
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
SELECT *
FROM students
WHERE id = ?
LIMIT 1
");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Lesson Progress
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
SELECT
    slp.*,
    l.lesson_title,
    c.course_title
FROM student_lesson_progress slp
INNER JOIN lessons l
    ON l.id = slp.lesson_id
INNER JOIN course_modules cm
    ON cm.id = l.module_id
INNER JOIN courses c
    ON c.id = cm.course_id
WHERE slp.student_id = ?
ORDER BY slp.id DESC
LIMIT 10
");
$stmt->execute([$id]);
$lessonProgress = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*
|--------------------------------------------------------------------------
| Courses Enrolled
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
SELECT
    r.*,
    c.course_title,
    c.thumbnail,
    c.level,
    c.duration
FROM registrations r
INNER JOIN courses c
ON c.id = r.course_id
WHERE r.student_id=?
ORDER BY r.created_at DESC
");
$stmt->execute([$id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Certificates
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
SELECT *
FROM certificates
WHERE student_id=?
ORDER BY id DESC
");
$stmt->execute([$id]);
$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
/*
|--------------------------------------------------------------------------
| Payments
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
SELECT
    p.*
FROM payments p
INNER JOIN registrations r
    ON r.id = p.registration_id
WHERE r.student_id = ?
ORDER BY p.id DESC
");
$stmt->execute([$id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
SELECT *
FROM student_course_progress
WHERE student_id=?
");
$stmt->execute([$id]);
$progress = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Student Timeline
|--------------------------------------------------------------------------
*/

$timeline = [];

/* Registration */
foreach ($courses as $course) {
    $timeline[] = [
        'date' => $course['created_at'],
        'icon' => 'fa-book',
        'title' => 'Enrolled in '.$course['course_title']
    ];
}

/* Payments */
foreach ($payments as $payment) {
    $timeline[] = [
        'date' => $payment['created_at'],
        'icon' => 'fa-credit-card',
        'title' => 'Payment '.$payment['status'].' ('.$payment['payment_id'].')'
    ];
}

/* Certificates */
foreach ($certificates as $certificate) {
    $timeline[] = [
        'date' => $certificate['issued_at'],
        'icon' => 'fa-award',
        'title' => 'Certificate Issued'
    ];
}

usort($timeline,function($a,$b){
    return strtotime($b['date'])-strtotime($a['date']);
});
$pageTitle = "Student Profile";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="container-fluid">

<div class="row">

<div class="col-lg-4">

<div class="card shadow-sm">

<div class="card-body text-center">

<?php
$image = !empty($student['profile_photo'])
    ? "../../uploads/students/".$student['profile_photo']
    : "../../assets/images/default-user.png";
?>

<img src="<?= $image ?>"
class="rounded-circle mb-3"
width="170"
height="170"
style="object-fit:cover;">

<h3><?= htmlspecialchars($student['fullname']) ?></h3>

<p class="text-muted mb-1">
<?= htmlspecialchars($student['email']) ?>
</p>

<p class="text-muted">
<?= htmlspecialchars($student['phone']) ?>
</p>

<hr>

<table class="table table-sm">

<tr>
<th>Student ID</th>
<td><?= htmlspecialchars($student['student_id']) ?></td>
</tr>

<tr>
<th>Status</th>
<td><?= htmlspecialchars($student['status']) ?></td>
</tr>

<tr>
<th>Joined</th>
<td><?= date('d M Y',strtotime($student['created_at'])) ?></td>
</tr>

</table>

<a href="edit.php?id=<?= $student['id'] ?>"
class="btn btn-primary w-100">

<i class="fas fa-edit"></i>

Edit Profile

</a>

</div>

</div>

</div>

<div class="col-lg-8">

<div class="row g-3">

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body text-center">

<h5><?= count($courses) ?></h5>

<small>Courses</small>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body text-center">

<?php
$totalProgress = 0;

foreach($progress as $p){
    $totalProgress += (float)$p['progress_percentage'];
}

$overallProgress = count($progress)
    ? round($totalProgress / count($progress))
    : 0;
?>

<div class="progress mb-2" style="height:10px;">

    <div class="progress-bar bg-success"
         role="progressbar"
         style="width: <?= $overallProgress ?>%;">

    </div>

</div>

<h4 class="mb-0"><?= $overallProgress ?>%</h4>

<small>Overall Progress</small>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body text-center">

<h5><?= count($certificates) ?></h5>

<small>Certificates</small>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow-sm">

<div class="card-body text-center">

<h5><?= count($payments) ?></h5>

<small>Payments</small>

</div>

</div>

</div>

</div>

<br>

<div class="card shadow-sm">

<div class="card-header">

<strong>Enrolled Courses</strong>

</div>

<div class="card shadow-sm mt-4">

    <div class="card-header">

        <strong>Certificates</strong>

    </div>
    <div class="card shadow-sm mt-4">

    <div class="card-header">
        <strong>Payment History</strong>
    </div>
    <div class="card shadow-sm mt-4">

    <div class="card-header">

        <strong>Student Activity Timeline</strong>

    </div>

    <div class="card-body">

        <?php if($timeline): ?>

            <?php foreach($timeline as $item): ?>

                <div class="d-flex mb-3">

                    <div class="me-3">

                        <i class="fas <?= $item['icon'] ?> fa-lg text-primary"></i>

                    </div>

                    <div>

                        <strong><?= htmlspecialchars($item['title']) ?></strong>

                        <br>

                        <small class="text-muted">

                            <?= date('d M Y h:i A',strtotime($item['date'])) ?>

                        </small>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <p class="text-muted mb-0">

                No student activity yet.

            </p>

        <?php endif; ?>

    </div>

</div>

    <div class="table-responsive">

        <table class="table table-hover mb-0">

            <thead>

                <tr>

                    <th>Payment ID</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>

                </tr>

            </thead>

            <tbody>

            <?php if($payments): ?>

                <?php foreach($payments as $payment): ?>

                <tr>

                    <td><?= htmlspecialchars($payment['payment_id']) ?></td>

                    <td>
                        <?= number_format($payment['amount'],2) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($payment['payment_method']) ?>
                    </td>

                    <td>

                        <?php

                        $badge = match($payment['status']){

                            'Approved' => 'success',
                            'Rejected' => 'danger',
                            default => 'warning'

                        };

                        ?>

                        <span class="badge bg-<?= $badge ?>">
                            <?= htmlspecialchars($payment['status']) ?>
                        </span>

                    </td>

                    <td>

                        <?= date('d M Y',strtotime($payment['created_at'])) ?>

                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="5" class="text-center">

                        No payment records found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

    <div class="card shadow-sm mt-4">

    <div class="card-header">

        <strong>Recent Lesson Progress</strong>

    </div>

    <div class="table-responsive">

        <table class="table table-hover mb-0">

            <thead>

            <tr>

                <th>Course</th>
                <th>Lesson</th>
                <th>Status</th>

            </tr>

            </thead>

            <tbody>

            <?php if($lessonProgress): ?>

                <?php foreach($lessonProgress as $lesson): ?>

                <tr>

                    <td><?= htmlspecialchars($lesson['course_title']) ?></td>

                    <td><?= htmlspecialchars($lesson['lesson_title']) ?></td>

                    <td>

                        <?php if($lesson['completed']): ?>

                            <span class="badge bg-success">
                                Completed
                            </span>

                        <?php else: ?>

                            <span class="badge bg-warning">
                                In Progress
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="3" class="text-center">

                        No lesson activity found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

    <div class="table-responsive">

        <table class="table table-hover mb-0">

            <thead>

            <tr>

                <th>Certificate</th>

                <th>Date Issued</th>

                <th>Status</th>

            </tr>

            </thead>

            <tbody>

            <?php if($certificates): ?>

                <?php foreach($certificates as $certificate): ?>

                <tr>

                    <td><?= htmlspecialchars($certificate['certificate_no']) ?></td>

                    <td><?= date('d M Y',strtotime($certificate['issued_at'])) ?></td>

                    <td>
                        <span class="badge bg-success">
                            Issued
                        </span>
                    </td>

                </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="3" class="text-center">

                        No certificate earned yet.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<div class="table-responsive">

<table class="table table-hover mb-0">

<thead>

<tr>

<th>Course</th>
<th>Level</th>
<th>Duration</th>
<th>Payment</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php if($courses): ?>

<?php foreach($courses as $course): ?>

<tr>

<td><?= htmlspecialchars($course['course_title']) ?></td>

<td><?= htmlspecialchars($course['level']) ?></td>

<td><?= htmlspecialchars($course['duration']) ?></td>

<td>

<?php

$paymentColor = match($course['payment_status']){

    'Paid' => 'success',

    'Pending' => 'warning',

    default => 'secondary'

};

?>

<span class="badge bg-<?= $paymentColor ?>">

<?= htmlspecialchars($course['payment_status']) ?>

</span>

</td>

<td>

<?php

$statusColor = match($student['status']){

    'Active' => 'success',

    'Suspended' => 'danger',

    'Graduated' => 'primary',

    default => 'warning'

};

?>

<span class="badge bg-<?= $statusColor ?>">

<?= htmlspecialchars($student['status']) ?>

</span>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5" class="text-center">

No enrolled course.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>
<?php require_once '../includes/footer.php'; ?>