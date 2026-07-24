<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Create Assignment";

try {

    $stmt = $pdo->query("
        SELECT
            id,
            lesson_title
        FROM lessons
        ORDER BY lesson_title ASC
    ");

    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    die($e->getMessage());
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h2 class="fw-bold">Create Assignment</h2>
<p class="text-muted mb-0">
Add a new assignment for a lesson.
</p>
</div>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i>
Back
</a>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">Assignment Details</h5>
</div>

<div class="card-body">

<form action="save.php" method="POST">

<div class="mb-3">
<label class="form-label">
Lesson <span class="text-danger">*</span>
</label>

<select name="lesson_id" class="form-select" required>

<option value="">-- Select Lesson --</option>

<?php foreach($lessons as $lesson): ?>

<option value="<?= $lesson['id']; ?>">

<?= htmlspecialchars($lesson['lesson_title']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">
Assignment Title
</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">
Instructions
</label>

<textarea
name="instructions"
rows="8"
class="form-control"
required></textarea>

</div>

<div class="mb-4">

<label class="form-label">
Deadline
</label>

<input
type="date"
name="deadline"
class="form-control"
required>

</div>

<div class="text-end">

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Save Assignment

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>