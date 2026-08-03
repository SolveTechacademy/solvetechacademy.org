<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../../login.php");
    exit();
}

$student_db_id = $_SESSION['student_db_id'];

if (!isset($_GET['attempt_id']) || !is_numeric($_GET['attempt_id'])) {
    die("Invalid attempt.");
}

$attempt_id = (int)$_GET['attempt_id'];

$stmt = $pdo->prepare("
SELECT
qa.*,
q.title,
q.pass_mark,
q.duration

FROM quiz_attempts qa

INNER JOIN quizzes q
ON qa.quiz_id=q.id

WHERE
qa.id=?
AND qa.student_id=?

LIMIT 1
");

$stmt->execute([
    $attempt_id,
    $student_db_id
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    die("Result not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Quiz Result</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f7fb;
}

.card{
border:none;
border-radius:12px;
}

.navbar{
background:#0d6efd;
}

.navbar-brand{
color:#fff!important;
font-weight:bold;
}

.result{
font-size:60px;
font-weight:bold;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand">

SolveTech Academy Student Portal

</a>

<a href="index.php" class="btn btn-light">

Back

</a>

</div>

</nav>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>

Quiz Result

</h4>

</div>

<div class="card-body text-center">

<h3>

<?= htmlspecialchars($result['title']) ?>

</h3>

<hr>

<div class="result text-primary">

<?= number_format($result['percentage'],2) ?>%

</div>

<hr>

<div class="row">

<div class="col-md-4">

<h5>Score</h5>

<p>

<?= $result['score'] ?>

/

<?= $result['total_questions'] ?>

</p>

</div>

<div class="col-md-4">

<h5>Pass Mark</h5>

<p>

<?= $result['pass_mark'] ?>%

</p>

</div>

<div class="col-md-4">

<h5>Status</h5>

<?php if($result['status']=="Passed"){ ?>

<span class="badge bg-success fs-6">

PASSED

</span>

<?php }else{ ?>

<span class="badge bg-danger fs-6">

FAILED

</span>

<?php } ?>

</div>

</div>

<hr>
<?php

$certificate = null;

if ($result['status'] == "Passed") {

    // Get course linked to the quiz
    $stmt = $pdo->prepare("
        SELECT cm.course_id
        FROM quizzes q
        INNER JOIN course_modules cm
            ON cm.id = q.module_id
        WHERE q.id = ?
        LIMIT 1
    ");

    $stmt->execute([$result['quiz_id']]);

    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {

        $stmt = $pdo->prepare("
            SELECT *
            FROM certificates
            WHERE student_id = ?
            AND course_id = ?
            LIMIT 1
        ");

        $stmt->execute([
            $student_db_id,
            $course['course_id']
        ]);

        $certificate = $stmt->fetch(PDO::FETCH_ASSOC);

    }

}

if ($certificate):
?>

<div class="alert alert-success mt-4">

    <h4 class="mb-3">

        🎉 Congratulations!

    </h4>

    <p>

        You successfully passed your assessment.

        <br>

        Your certificate has been generated successfully.

    </p>

    <div class="mt-3">

        <a href="../certificates/download.php?id=<?= $certificate['id']; ?>"
           class="btn btn-success">

            <i class="fas fa-download"></i>

            Download Certificate

        </a>

        <a href="../certificates/verify.php?code=<?= $certificate['verification_code']; ?>"
           class="btn btn-primary">

            <i class="fas fa-shield-alt"></i>

            Verify Certificate

        </a>

    </div>

</div>

<?php endif; ?>

<p>

Started:

<strong>

<?= date('d M Y h:i A',strtotime($result['started_at'])) ?>

</strong>

</p>

<p>

Completed:

<strong>

<?= date('d M Y h:i A',strtotime($result['completed_at'])) ?>

</strong>

</p>

<div class="mt-4">

<a
href="review.php?attempt_id=<?= $attempt_id ?>"
class="btn btn-primary">

Review Answers

</a>

<a
href="index.php"
class="btn btn-secondary">

Finish

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>