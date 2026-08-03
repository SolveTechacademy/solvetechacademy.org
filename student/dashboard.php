<?php
session_start();

require_once '../config/auth.php';
require_once '../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../login.php");
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

/*
|--------------------------------------------------------------------------
| RECENT ACTIVITY
|--------------------------------------------------------------------------
*/

$activity = [];

try {

    $stmt = $pdo->prepare("
        SELECT
            'Quiz Passed' AS activity,
            q.quiz_title AS details,
            qa.completed_at AS activity_date
        FROM quiz_attempts qa
        INNER JOIN quizzes q
            ON q.id = qa.quiz_id
        WHERE qa.student_id = ?
        AND qa.status='Passed'

        UNION ALL

        SELECT
            'Certificate Issued',
            co.course_title,
            c.issue_date
        FROM certificates c
        INNER JOIN courses co
            ON co.id=c.course_id
        WHERE c.student_id=?

        ORDER BY activity_date DESC

        LIMIT 5
    ");

    $stmt->execute([
        $student_db_id,
        $student_db_id
    ]);

    $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(Exception $e){

    $activity = [];

}

/*
|--------------------------------------------------------------------------
| Student Certificates
|--------------------------------------------------------------------------
*/

$certificateQuery = $pdo->prepare("
SELECT COUNT(*)
FROM certificates
WHERE student_id = ?
AND status = 'Issued'
");

$certificateQuery->execute([$student_db_id]);

$totalCertificates = $certificateQuery->fetchColumn();

if (!$student) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

if (strcasecmp(trim($student['status']), 'Active') !== 0) {
    session_destroy();
    $_SESSION['error'] = "Your account is awaiting approval.";
    header("Location: ../login.php");
    exit;
}

$courseQuery = $pdo->prepare("
SELECT
courses.course_title,
courses.duration,
courses.level,
registrations.training_mode,
registrations.payment_status,
registrations.approval_status

FROM registrations

INNER JOIN courses
ON registrations.course_id = courses.id

WHERE registrations.student_id = ?
AND registrations.approval_status = 'Approved'
");

$courseQuery->execute([$student_db_id]);

$courses = $courseQuery->fetchAll(PDO::FETCH_ASSOC);
/*
|--------------------------------------------------------------------------
| TOTAL QUIZZES
|--------------------------------------------------------------------------
*/

$quizQuery = $pdo->prepare("
SELECT COUNT(*)
FROM quizzes
");

$quizQuery->execute();

$totalQuizzes = $quizQuery->fetchColumn();

/*
|--------------------------------------------------------------------------
| STUDENT PROGRESS
|--------------------------------------------------------------------------
*/

$progressQuery = $pdo->prepare("
SELECT COUNT(*)
FROM lesson_progress
WHERE student_id=?
AND completed=1
");

$progressQuery->execute([$student_db_id]);

$completedLessons = $progressQuery->fetchColumn();

$totalLessonQuery = $pdo->prepare("
SELECT COUNT(*)
FROM lessons
");

$totalLessonQuery->execute();

$totalLessons = $totalLessonQuery->fetchColumn();

$overallProgress = 0;

if($totalLessons>0){

    $overallProgress = round(
        ($completedLessons/$totalLessons)*100
    );

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Student Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

:root{

--primary:#1E2143;
--orange:#FF8A3D;
--green:#6BC048;
--bg:#F6F8FC;

}

body{

background:var(--bg);
font-family:Arial,Helvetica,sans-serif;

}

.navbar{

background:#fff;
box-shadow:0 3px 15px rgba(0,0,0,.08);

}

.nav-link{

font-weight:600;
color:var(--primary)!important;
margin-left:10px;

}

.nav-link:hover{

color:var(--orange)!important;

}

.card{

border:none;
border-radius:18px;
box-shadow:0 8px 25px rgba(0,0,0,.08);

}

.stat-card{

transition:.3s;

}

.stat-card:hover{

transform:translateY(-6px);

}

.btn-primary{

background:var(--orange);
border:none;

}

.btn-primary:hover{

background:#ef7d30;

}

.section-title{

font-size:24px;
font-weight:bold;
color:var(--primary);

}

.footer{

background:var(--primary);
color:white;
padding:40px 0;
margin-top:60px;

}

.footer a{

color:white;
text-decoration:none;

}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">

<div class="container">

<a class="navbar-brand" href="dashboard.php">

<img src="../assets/images/logo.png" style="height:55px;">

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="offcanvas"
data-bs-target="#mobileMenu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse">

<ul class="navbar-nav ms-auto align-items-center">

<li class="nav-item">

<a class="nav-link" href="dashboard.php">

Dashboard

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="learning/index.php">

Learning

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="quizzes/index.php">

Quizzes

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="certificates/index.php">

Certificates

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="profile.php">

Profile

</a>

</li>

<li class="nav-item ms-3">

<a
href="../logout.php"
class="btn btn-danger">

Logout

</a>

</li>

</ul>

</div>

</div>

</nav>

<div
class="offcanvas offcanvas-end"
tabindex="-1"
id="mobileMenu">

<div class="offcanvas-header">

<img src="../assets/images/logo.png" style="height:45px;">

<button
type="button"
class="btn-close"
data-bs-dismiss="offcanvas">

</button>

</div>

<div class="offcanvas-body">

<div class="list-group">

<a href="dashboard.php" class="list-group-item">🏠 Dashboard</a>

<a href="learning/index.php" class="list-group-item">📚 Learning</a>

<a href="quizzes/index.php" class="list-group-item">📝 Quizzes</a>

<a href="certificates/index.php" class="list-group-item">🏆 Certificates</a>

<a href="profile.php" class="list-group-item">👤 Profile</a>

<a href="../logout.php" class="list-group-item text-danger">🚪 Logout</a>

</div>

</div>

</div>

<div class="container py-5">

<?php

$hour=date('H');

if($hour<12){

$greeting="☀ Good Morning";

}elseif($hour<17){

$greeting="🌤 Good Afternoon";

}else{

$greeting="🌙 Good Evening";

}

?>

<div class="card mb-4">

<div class="card-body">

<h2>

<?= $greeting ?>,

<strong>

<?= htmlspecialchars($student['fullname']); ?>

</strong>

</h2>

<p class="text-muted mb-0">

Welcome back to SolveTech Academy.

Continue your learning journey.

</p>

</div>

</div>
<div class="row g-4 mb-4">

    <!-- Learning -->

    <div class="col-lg-3 col-md-6">

        <div class="card stat-card">

            <div class="card-body text-center">

                <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>

                <h2><?= count($courses); ?></h2>

                <p class="text-muted">

                    Learning

                </p>

                <a href="learning/index.php"
                   class="btn btn-primary btn-sm">

                    Open

                </a>

            </div>

        </div>

    </div>

    <!-- Quiz -->

    <div class="col-lg-3 col-md-6">

        <div class="card stat-card">

            <div class="card-body text-center">

                <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>

                <h2><?= $totalQuizzes; ?></h2>

                <p class="text-muted">

                    Quizzes

                </p>

                <a href="quizzes/index.php"
                   class="btn btn-warning btn-sm">

                    Open

                </a>

            </div>

        </div>

    </div>

    <!-- Certificates -->

    <div class="col-lg-3 col-md-6">

        <div class="card stat-card">

            <div class="card-body text-center">

                <i class="fas fa-award fa-3x text-success mb-3"></i>

                <h2><?= $totalCertificates; ?></h2>

                <p class="text-muted">

                    Certificates

                </p>

                <a href="certificates/index.php"
                   class="btn btn-success btn-sm">

                    View

                </a>

            </div>

        </div>

    </div>

    <!-- Progress -->

    <div class="col-lg-3 col-md-6">

        <div class="card stat-card">

            <div class="card-body text-center">


                <i class="fas fa-chart-line fa-3x text-info mb-3"></i>

                <h2>

                    <?= $overallProgress; ?>%

                </h2>

                <div class="progress mb-3">

                    <div
                    class="progress-bar bg-info"
                    style="width:<?= $overallProgress ?>%">

                    </div>

                </div>

                <a href="learning/index.php"
                   class="btn btn-info btn-sm text-white">

                    Continue

                </a>

            </div>

        </div>

    </div>

</div>
<div class="card mb-4">

<div class="card-header bg-white">

<h4 class="section-title">

Continue Learning

</h4>

</div>

<div class="card-body">

<?php

$continue = $pdo->prepare("

SELECT

c.course_title,

cm.module_title,

l.lesson_title,

l.id

FROM registrations r

INNER JOIN courses c
ON c.id=r.course_id

INNER JOIN course_modules cm
ON cm.course_id=c.id

INNER JOIN lessons l
ON l.module_id=cm.id

LEFT JOIN lesson_progress lp

ON lp.lesson_id=l.id

AND lp.student_id=?

WHERE

r.student_id=?

AND r.approval_status='Approved'

AND(

lp.completed IS NULL

OR lp.completed=0

)

ORDER BY

cm.module_order,

l.lesson_order

LIMIT 1

");

$continue->execute([
$student_db_id,
$student_db_id
]);

$lesson=$continue->fetch(PDO::FETCH_ASSOC);

?>

<?php if($lesson){ ?>

<h4>

<?= htmlspecialchars($lesson['course_title']); ?>

</h4>

<p>

<?= htmlspecialchars($lesson['module_title']); ?>

</p>

<p>

<?= htmlspecialchars($lesson['lesson_title']); ?>

</p>

<a
href="learning/lesson.php?id=<?= $lesson['id']; ?>"
class="btn btn-primary">

Resume Learning

</a>

<?php }else{ ?>

<div class="alert alert-info">

No lesson started yet.

</div>

<a
href="learning/index.php"
class="btn btn-success">

Start Learning

</a>

<?php } ?>

</div>

</div>
<div class="card mb-4">

    <div class="card-header bg-white">

        <h4 class="section-title mb-0">

            📚 My Courses

        </h4>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Course</th>

                        <th>Duration</th>

                        <th>Level</th>

                        <th>Mode</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(empty($courses)){ ?>

                    <tr>

                        <td colspan="5" class="text-center py-4">

                            No approved courses yet.

                        </td>

                    </tr>

                <?php }else{ ?>

                    <?php foreach($courses as $course){ ?>

                    <tr>

                        <td>

                            <?= htmlspecialchars($course['course_title']); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($course['duration']); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($course['level']); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($course['training_mode']); ?>

                        </td>

                        <td>

                            <a
                            href="learning/index.php"
                            class="btn btn-sm btn-primary">

                                Continue

                            </a>

                        </td>

                    </tr>

                    <?php } ?>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
<div class="card mb-4">

    <div class="card-header bg-white">

        <h4 class="section-title mb-0">

            🕒 Recent Activity

        </h4>

    </div>

    <div class="card-body">

<?php if(empty($activity)){ ?>

<div class="alert alert-info mb-0">

No activity yet.

</div>

<?php }else{ ?>

<ul class="list-group list-group-flush">

<?php foreach($activity as $item){ ?>

<li class="list-group-item d-flex justify-content-between">

<div>

<strong>

<?= htmlspecialchars($item['activity']); ?>

</strong>

<br>

<small class="text-muted">

<?= htmlspecialchars($item['details']); ?>

</small>

</div>

<div>

<small>

<?= date('d M Y',strtotime($item['activity_date'])); ?>

</small>

</div>

</li>

<?php } ?>

</ul>

<?php } ?>

    </div>

</div>
<div class="card mb-5">

<div class="card-header bg-white">

<h4 class="section-title mb-0">

⚡ Quick Actions

</h4>

</div>

<div class="card-body">

<div class="row g-3">

<div class="col-md-3">

<a
href="learning/index.php"
class="btn btn-primary w-100">

📚 Continue Learning

</a>

</div>

<div class="col-md-3">

<a
href="quizzes/index.php"
class="btn btn-warning w-100">

📝 Take Quiz

</a>

</div>

<div class="col-md-3">

<a
href="certificates/index.php"
class="btn btn-success w-100">

🏆 Certificates

</a>

</div>

<div class="col-md-3">

<a
href="profile.php"
class="btn btn-dark w-100">

👤 Profile

</a>

</div>

</div>

</div>

</div>
<footer class="footer">

<div class="container">

<div class="row">

<div class="col-md-4">

<img
src="../assets/images/logo.png"
style="height:55px;">

<p class="mt-3">

Empowering Future Tech Professionals.

</p>

</div>

<div class="col-md-4">

<h5>

Quick Links

</h5>

<ul class="list-unstyled">

<li><a href="dashboard.php">Dashboard</a></li>

<li><a href="learning/index.php">Learning</a></li>

<li><a href="quizzes/index.php">Quizzes</a></li>

<li><a href="profile.php">Profile</a></li>

</ul>

</div>

<div class="col-md-4">

<h5>

Contact

</h5>

<p>

info@solvetechacademy.org

</p>

<p>

© <?= date('Y'); ?>

SolveTech Academy

</p>

</div>

</div>

</div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
