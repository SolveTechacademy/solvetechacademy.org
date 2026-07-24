<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM assignments WHERE id=?");
$stmt->execute([$id]);
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$assignment) {
    die("Assignment not found.");
}

$lessons = $pdo->query("
SELECT id, lesson_title
FROM lessons
ORDER BY lesson_title
")->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Edit Assignment</h2>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i> Back
</a>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">Edit Assignment</h5>
</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?= $assignment['id']; ?>">

<div class="mb-3">

<label class="form-label">Lesson</label>

<select name="lesson_id" class="form-select" required>

<?php foreach($lessons as $lesson): ?>

<option
value="<?= $lesson['id']; ?>"
<?= $lesson['id']==$assignment['lesson_id']?'selected':''; ?>>

<?= htmlspecialchars($lesson['lesson_title']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">Assignment Title</label>

<input
type="text"
name="title"
class="form-control"
value="<?= htmlspecialchars($assignment['title']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">Instructions</label>

<textarea
name="instructions"
rows="8"
class="form-control"
required><?= htmlspecialchars($assignment['instructions']); ?></textarea>

</div>

<div class="mb-4">

<label class="form-label">Deadline</label>

<input
type="date"
name="deadline"
class="form-control"
value="<?= $assignment['deadline']; ?>"
required>

</div>

<button class="btn btn-success">
<i class="fas fa-save"></i>
Update Assignment
</button>

</form>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>