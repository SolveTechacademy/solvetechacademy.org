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
WHERE id=?
LIMIT 1
");

$stmt->execute([$student_db_id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

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

WHERE registrations.student_id=?
");

$courseQuery->execute([$student_db_id]);

$courses = $courseQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Dashboard</title>

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

color:white !important;

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

<a href="../logout.php" class="btn btn-danger">

Logout

</a>

</div>

</nav>

<div class="container mt-4">

<div class="card shadow">

<div class="card-body">

<h3>

Welcome,

<?= htmlspecialchars($student['fullname']); ?>

</h3>

<hr>

<div class="row">

<div class="col-md-4">

<strong>Student ID</strong>

<p><?= htmlspecialchars($student['student_id']); ?></p>

</div>

<div class="col-md-4">

<strong>Email</strong>

<p><?= htmlspecialchars($student['email']); ?></p>

</div>

<div class="col-md-4">

<strong>Phone</strong>

<p><?= htmlspecialchars($student['phone']); ?></p>

</div>

</div>

</div>

</div>

<br>

<div class="card shadow">

<div class="card-header bg-primary text-white">

My Courses

</div>

<div class="card-body">

<table class="table table-bordered">

<thead>

<tr>

<th>Course</th>

<th>Duration</th>

<th>Mode</th>

<th>Payment</th>

<th>Approval</th>

</tr>

</thead>

<tbody>

<?php foreach($courses as $course){ ?>

<tr>

<td><?= htmlspecialchars($course['course_title']); ?></td>

<td><?= htmlspecialchars($course['duration']); ?></td>

<td><?= htmlspecialchars($course['training_mode']); ?></td>

<td><?= htmlspecialchars($course['payment_status']); ?></td>

<td><?= htmlspecialchars($course['approval_status']); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>