<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Create Announcement";

$courses = $pdo->query("
SELECT id, course_title
FROM courses
ORDER BY course_title
")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">Create Announcement</h2>

<p class="text-muted mb-0">
Publish an announcement for students or instructors.
</p>

</div>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i>
Back
</a>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">Announcement Details</h5>

</div>

<div class="card-body">

<form action="save.php" method="POST">

<div class="mb-3">

<label class="form-label">
Title
</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">
Message
</label>

<textarea
name="message"
rows="8"
class="form-control"
required></textarea>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">
Audience
</label>

<select
name="audience"
class="form-select"
required>

<option value="All Students">
All Students
</option>

<option value="All Instructors">
All Instructors
</option>

<option value="Specific Course">
Specific Course
</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">
Course (Optional)
</label>

<select
name="course_id"
class="form-select">

<option value="">
-- Select Course --
</option>

<?php foreach($courses as $course): ?>

<option value="<?= $course['id']; ?>">

<?= htmlspecialchars($course['course_title']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">
Status
</label>

<select
name="status"
class="form-select">

<option value="Published">
Published
</option>

<option value="Draft">
Draft
</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">
Publish Date
</label>

<input
type="date"
name="publish_date"
value="<?= date('Y-m-d'); ?>"
class="form-control"
required>

</div>

<div class="col-md-4 mb-3">

<label class="form-label">
Expiry Date
</label>

<input
type="date"
name="expiry_date"
class="form-control">

</div>

</div>

<div class="text-end">

<button
type="submit"
class="btn btn-primary">

<i class="fas fa-save"></i>

Save Announcement

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>