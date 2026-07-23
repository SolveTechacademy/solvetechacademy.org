<?php

require_once '../includes/auth.php';

$pageTitle = "Edit Lesson";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

if (!isset($_GET['id'])) {

    header("Location: ../courses/index.php");
    exit();

}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");

$stmt->execute([$id]);

$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {

    $_SESSION['error'] = "Lesson not found.";

    header("Location: ../courses/index.php");
    exit();

}

$module_id = $lesson['module_id'];

?>
<?php

require_once '../includes/auth.php';

$pageTitle = "Edit Lesson";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

if (!isset($_GET['id'])) {

    header("Location: ../courses/index.php");
    exit();

}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");

$stmt->execute([$id]);

$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {

    $_SESSION['error'] = "Lesson not found.";

    header("Location: ../courses/index.php");
    exit();

}

$module_id = $lesson['module_id'];

?>

<div class="card shadow">

    <div class="card-header bg-warning">

        <h4 class="mb-0">Edit Lesson</h4>

    </div>

    <div class="card-body">

        <form action="update.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $lesson['id']; ?>">

            <input type="hidden" name="module_id" value="<?= $module_id; ?>">

<div class="mb-3">

<label>Lesson Title</label>

<input
type="text"
name="lesson_title"
class="form-control"
value="<?= htmlspecialchars($lesson['lesson_title']); ?>"
required>

</div>
<div class="mb-3">

<label>Lesson Type</label>

<select
name="lesson_type"
class="form-select">

<option value="Video" <?= $lesson['lesson_type']=="Video"?"selected":""; ?>>Video</option>

<option value="PDF" <?= $lesson['lesson_type']=="PDF"?"selected":""; ?>>PDF</option>

<option value="Document" <?= $lesson['lesson_type']=="Document"?"selected":""; ?>>Document</option>

<option value="External Link" <?= $lesson['lesson_type']=="External Link"?"selected":""; ?>>External Link</option>

</select>

</div>
<div class="mb-3">

    <label>Lesson Description</label>

    <textarea
        name="description"
        class="form-control"
        rows="5"><?= htmlspecialchars($lesson['description']); ?></textarea>

</div>
<div class="mb-3">

    <label>Video / External URL</label>

    <input
        type="url"
        name="video_url"
        class="form-control"
        value="<?= htmlspecialchars($lesson['video_url']); ?>">

</div>
<div class="mb-3">

    <label>Replace Lesson File (Optional)</label>

    <input
        type="file"
        name="lesson_file"
        class="form-control">

    <?php if (!empty($lesson['file_path'])): ?>

        <small class="text-success">

            Current File:
            <?= htmlspecialchars($lesson['file_path']); ?>

        </small>

    <?php endif; ?>

</div>
<div class="row">

    <div class="col-md-4">

        <label>Display Order</label>

        <input
            type="number"
            name="lesson_order"
            class="form-control"
            value="<?= $lesson['lesson_order']; ?>">

    </div>

    <div class="col-md-4">

        <label>Status</label>

        <select
            name="status"
            class="form-select">

            <option value="Active" <?= $lesson['status'] == "Active" ? "selected" : ""; ?>>
                Active
            </option>

            <option value="Inactive" <?= $lesson['status'] == "Inactive" ? "selected" : ""; ?>>
                Inactive
            </option>

        </select>

    </div>

    <div class="col-md-4">

        <label>Free Preview</label>

        <select
            name="is_preview"
            class="form-select">

            <option value="1" <?= $lesson['is_preview'] ? "selected" : ""; ?>>
                Yes
            </option>

            <option value="0" <?= !$lesson['is_preview'] ? "selected" : ""; ?>>
                No
            </option>

        </select>

    </div>

</div>
<br>

<button type="submit" class="btn btn-primary">
    <i class="fas fa-save"></i> Update Lesson
</button>

<a href="index.php?module_id=<?= $module_id; ?>" class="btn btn-secondary">
    Cancel
</a>

</form>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>