<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../../login.php");
    exit();
}

$student_db_id = $_SESSION['student_db_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid quiz.");
}

$quiz_id = (int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| Load Student
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("SELECT * FROM students WHERE id=? LIMIT 1");
$stmt->execute([$student_db_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

/*
|--------------------------------------------------------------------------
| Load Quiz
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT
q.*,
cm.module_title,
c.course_title

FROM quizzes q

INNER JOIN course_modules cm
ON q.module_id=cm.id

INNER JOIN courses c
ON cm.course_id=c.id

WHERE q.id=?
AND q.status='Active'

LIMIT 1
");

$stmt->execute([$quiz_id]);

$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found.");
}

/*
|--------------------------------------------------------------------------
| Check Attempts
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT COUNT(*)
FROM quiz_attempts
WHERE quiz_id=?
AND student_id=?
");

$stmt->execute([
    $quiz_id,
    $student_db_id
]);

$attempts_used = $stmt->fetchColumn();

if ($attempts_used >= $quiz['attempts']) {
    die("You have exhausted all quiz attempts.");
}

/*
|--------------------------------------------------------------------------
| Load Questions
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM quiz_questions
WHERE quiz_id=?
ORDER BY id ASC
");

$stmt->execute([$quiz_id]);

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) {
    die("No questions available.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= htmlspecialchars($quiz['title']) ?></title>

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

.timer{
    font-size:22px;
    font-weight:bold;
    color:red;
}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand">
SolveTech Academy Student Portal
</a>

<div>

<span class="timer" id="timer"></span>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<?= htmlspecialchars($quiz['title']) ?>

</h4>

</div>

<div class="card-body">

<div class="mb-3">

<strong>Course:</strong>

<?= htmlspecialchars($quiz['course_title']) ?>

<br>

<strong>Module:</strong>

<?= htmlspecialchars($quiz['module_title']) ?>

<br>

<strong>Duration:</strong>

<?= $quiz['duration'] ?> Minutes

<br>

<strong>Pass Mark:</strong>

<?= $quiz['pass_mark'] ?>%

</div>

<form
id="quizForm"
method="POST"
action="submit.php">

<input
type="hidden"
name="quiz_id"
value="<?= $quiz['id'] ?>">

<?php foreach($questions as $index=>$question): ?>

<div class="card mb-4">

<div class="card-body">

<h5>

Question <?= $index+1 ?>

</h5>

<p>

<?= htmlspecialchars($question['question']) ?>

</p>

<?php

$options = [
'A'=>$question['option_a'],
'B'=>$question['option_b'],
'C'=>$question['option_c'],
'D'=>$question['option_d']
];

foreach($options as $key=>$value):

?>

<div class="form-check">

<input
class="form-check-input"
type="radio"
name="answers[<?= $question['id'] ?>]"
value="<?= $key ?>"
required>

<label class="form-check-label">

<?= $key ?>.
<?= htmlspecialchars($value) ?>

</label>

</div>

<?php endforeach; ?>

</div>

</div>

<?php endforeach; ?>

<button
class="btn btn-success btn-lg">

Submit Quiz

</button>

</form>

</div>

</div>

</div>

<script>

let time = <?= (int)$quiz['duration'] ?> * 60;

const timer=document.getElementById("timer");

function updateTimer(){

let minutes=Math.floor(time/60);

let seconds=time%60;

timer.innerHTML=
minutes.toString().padStart(2,'0')
+":"
+seconds.toString().padStart(2,'0');

if(time<=0){

document.getElementById("quizForm").submit();

}

time--;

}

updateTimer();

setInterval(updateTimer,1000);

</script>

</body>

</html>