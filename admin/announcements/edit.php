<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if(!isset($_GET['id'])){
    header("Location:index.php");
    exit;
}

$id=(int)$_GET['id'];

$stmt=$pdo->prepare("SELECT * FROM announcements WHERE id=?");
$stmt->execute([$id]);
$announcement=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$announcement){
    die("Announcement not found.");
}

$courses=$pdo->query("
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

<h2>Edit Announcement</h2>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i> Back
</a>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">Edit Announcement</h5>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $announcement['id']; ?>">

<div class="mb-3">

<label class="form-label">Title</label>

<input
type="text"
name="title"
class="form-control"
value="<?= htmlspecialchars($announcement['title']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">Message</label>

<textarea
name="message"
rows="8"
class="form-control"
required><?= htmlspecialchars($announcement['message']); ?></textarea>

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Audience</label>

<select
name="audience"
class="form-select">

<option value="All Students" <?= $announcement['audience']=='All Students'?'selected':''; ?>>All Students</option>

<option value="All Instructors" <?= $announcement['audience']=='All Instructors'?'selected':''; ?>>All Instructors</option>

<option value="Specific Course" <?= $announcement['audience']=='Specific Course'?'selected':''; ?>>Specific Course</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Course</label>

<select
name="course_id"
class="form-select">

<option value="">-- Select Course --</option>

<?php foreach($courses as $course): ?>

<option
value="<?= $course['id']; ?>"
<?= $course['id']==$announcement['course_id']?'selected':''; ?>>

<?= htmlspecialchars($course['course_title']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">

<label>Status</label>

<select
name="status"
class="form-select">

<option value="Published" <?= $announcement['status']=='Published'?'selected':''; ?>>Published</option>

<option value="Draft" <?= $announcement['status']=='Draft'?'selected':''; ?>>Draft</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Publish Date</label>

<input
type="date"
name="publish_date"
value="<?= $announcement['publish_date']; ?>"
class="form-control">

</div>

<div class="col-md-4 mb-3">

<label>Expiry Date</label>

<input
type="date"
name="expiry_date"
value="<?= $announcement['expiry_date']; ?>"
class="form-control">

</div>

</div>

<button class="btn btn-success">

<i class="fas fa-save"></i>

Update Announcement

</button>

</form>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>