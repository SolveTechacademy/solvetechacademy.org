<?php
require_once '../includes/auth.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$courses = $pdo->query("
SELECT id, course_title
FROM courses
ORDER BY course_title
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">

<div class="card">

<div class="card-header">
<h3>Create Quiz</h3>
</div>

<div class="card-body">

<form action="store.php" method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Course</label>

<select
name="course_id"
id="course"
class="form-control"
required>

<option value="">Select Course</option>

<?php foreach($courses as $course): ?>

<option value="<?= $course['id'] ?>">

<?= htmlspecialchars($course['course_title']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Module</label>

<select
name="module_id"
id="module"
class="form-control"
required>

<option value="">Select Module</option>

</select>

</div>

</div>

<div class="mb-3">

<label>Quiz Title</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="4"></textarea>

</div>

<div class="row">

<div class="col-md-4">

<label>Pass Mark (%)</label>

<input
type="number"
name="pass_mark"
class="form-control"
value="70"
min="1"
max="100">

</div>

<div class="col-md-4">

<label>Duration (Minutes)</label>

<input
type="number"
name="duration"
class="form-control"
value="30">

</div>

<div class="col-md-4">

<label>Attempts Allowed</label>

<input
type="number"
name="attempts"
class="form-control"
value="3">

</div>

</div>

<div class="mt-3">

<label>Status</label>

<select
name="status"
class="form-control">

<option value="Active">

Active

</option>

<option value="Inactive">

Inactive

</option>

</select>

</div>

<div class="mt-4">

<button
class="btn btn-primary">

Save Quiz

</button>

<a
href="index.php"
class="btn btn-secondary">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

<script>

document
.getElementById("course")
.addEventListener("change", function(){

let course = this.value;

fetch("load_modules.php?course_id="+course)

.then(response=>response.text())

.then(data=>{

document
.getElementById("module")
.innerHTML=data;

});

});

</script>

<?php require_once '../includes/footer.php'; ?>