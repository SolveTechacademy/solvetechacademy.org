<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

try {

    $stmt = $pdo->prepare("
        SELECT
            a.*,
            l.lesson_title
        FROM assignments a
        LEFT JOIN lessons l
            ON a.lesson_id = l.id
        WHERE a.id = ?
    ");

    $stmt->execute([$id]);

    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$assignment) {
        die("Assignment not found.");
    }

} catch (PDOException $e) {
    die($e->getMessage());
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">Assignment Details</h2>

<div>

<a href="edit.php?id=<?= $assignment['id']; ?>" class="btn btn-warning">
    <i class="fas fa-edit"></i> Edit
</a>

<a href="index.php" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back
</a>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">Assignment Information</h5>
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="220">Lesson</th>
<td><?= htmlspecialchars($assignment['lesson_title']); ?></td>
</tr>

<tr>
<th>Assignment Title</th>
<td><?= htmlspecialchars($assignment['title']); ?></td>
</tr>

<tr>
<th>Instructions</th>
<td><?= nl2br(htmlspecialchars($assignment['instructions'])); ?></td>
</tr>

<tr>
<th>Deadline</th>
<td><?= date('d F Y', strtotime($assignment['deadline'])); ?></td>
</tr>

<tr>
<th>Created At</th>
<td><?= date('d F Y H:i', strtotime($assignment['created_at'])); ?></td>
</tr>

</table>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>