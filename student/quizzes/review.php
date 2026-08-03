<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../../login.php");
    exit();
}

$student_db_id=$_SESSION['student_db_id'];

if(!isset($_GET['attempt_id']) || !is_numeric($_GET['attempt_id'])){
die("Invalid attempt.");
}

$attempt_id=(int)$_GET['attempt_id'];

$stmt=$pdo->prepare("
SELECT
qa.*,
q.title

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

$attempt=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$attempt){
die("Attempt not found.");
}

$stmt=$pdo->prepare("
SELECT

qq.question,
qq.option_a,
qq.option_b,
qq.option_c,
qq.option_d,
qq.correct_option,

a.selected_option,
a.is_correct

FROM quiz_answers a

INNER JOIN quiz_questions qq
ON qq.id=a.question_id

WHERE a.attempt_id=?

ORDER BY qq.id
");

$stmt->execute([$attempt_id]);

$answers=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Review Answers</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

background:#f5f7fb;

}

.correct{

border-left:6px solid green;

}

.wrong{

border-left:6px solid red;

}

</style>

</head>

<body>

<div class="container mt-4">

<h2 class="mb-4">

<?= htmlspecialchars($attempt['title']) ?>

</h2>

<?php

$n=1;

foreach($answers as $row):

?>

<div class="card mb-4 <?= $row['is_correct']?'correct':'wrong' ?>">

<div class="card-body">

<h5>

Question <?= $n++ ?>

</h5>

<p>

<?= htmlspecialchars($row['question']) ?>

</p>

<hr>

<p><strong>A.</strong> <?= htmlspecialchars($row['option_a']) ?></p>

<p><strong>B.</strong> <?= htmlspecialchars($row['option_b']) ?></p>

<p><strong>C.</strong> <?= htmlspecialchars($row['option_c']) ?></p>

<p><strong>D.</strong> <?= htmlspecialchars($row['option_d']) ?></p>

<hr>

<p>

<strong>Your Answer:</strong>

<?= htmlspecialchars($row['selected_option']) ?>

</p>

<p>



<strong>Correct Answer:</strong>

<?= htmlspecialchars($row['correct_option']) ?>

</p>

<?php if($row['is_correct']){ ?>

<div class="alert alert-success">

Correct

</div>

<?php }else{ ?>

<div class="alert alert-danger">

Incorrect

</div>

<?php } ?>

</div>

</div>

<?php endforeach; ?>

<a href="index.php" class="btn btn-primary">

Finish

</a>

</div>

</body>

</html>