<?php

require_once '../includes/auth.php';

$pageTitle = "Add Lesson";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

if (!isset($_GET['module_id'])) {

    header("Location: ../courses/index.php");
    exit();

}

$module_id = (int) $_GET['module_id'];

$stmt = $pdo->prepare("SELECT * FROM course_modules WHERE id = ?");

$stmt->execute([$module_id]);

$module = $stmt->fetch(PDO::FETCH_ASSOC);
// Get next lesson order
$orderStmt = $pdo->prepare("
    SELECT COALESCE(MAX(lesson_order),0)+1 AS next_order
    FROM lessons
    WHERE module_id = ?
");

$orderStmt->execute([$module_id]);

$nextOrder = $orderStmt->fetch(PDO::FETCH_ASSOC)['next_order'];

if (!$module) {

    $_SESSION['error'] = "Module not found.";

    header("Location: ../courses/index.php");

    exit();

}

?>
<div class="card shadow">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">

            Add New Lesson

        </h4>

    </div>

    <div class="card-body">

        <form action="save.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="module_id" value="<?= $module_id; ?>">
            <div class="mb-3">

<label>Lesson Title</label>

<input
type="text"
name="lesson_title"
class="form-control"
required>

</div>
<div class="mb-3">

<label>Lesson Type</label>

<select
name="lesson_type"
id="lesson_type"
class="form-select"
required>

<option value="Video">Video</option>

<option value="PDF">PDF</option>

<option value="Document">Document</option>

<option value="External Link">External Link</option>

</select>

</div>
<div class="mb-3">

<label>Lesson Description</label>

<textarea
name="description"
class="form-control"
rows="5"></textarea>

</div>
<div class="mb-3">

<label>Video / External URL</label>
<div class="mb-3">

    <label>Lesson Duration</label>

    <input
        type="text"
        name="duration"
        class="form-control"
        placeholder="e.g. 12 min or 01:25:30">

</div>

<input
type="url"
name="video_url"
class="form-control"
placeholder="https://youtube.com/...">

</div>
<div class="mb-3">

<label>Upload File</label>

<input
type="file"
name="lesson_file"
class="form-control">

<small class="text-muted">

Accepted:
MP4, PDF, DOCX, ZIP

</small>

</div>
<div class="row">

<div class="col-md-4">

<label>Display Order</label>

<input
type="number"
name="lesson_order"
value="<?= $nextOrder; ?>"
class="form-control">

</div>

<div class="col-md-4">

<label>Status</label>

<select
name="status"
class="form-select">

<option value="Active">Active</option>

<option value="Inactive">Inactive</option>

</select>

</div>

<div class="col-md-4">

<label>Free Preview</label>

<select
name="is_preview"
class="form-select">

<option value="0">No</option>

<option value="1">Yes</option>

</select>

</div>

</div>
<br>

<button class="btn btn-primary">

<i class="fas fa-save"></i>

Save Lesson

</button>

<a href="index.php?module_id=<?= $module_id; ?>" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

<?php

require_once '../includes/footer.php';

?>