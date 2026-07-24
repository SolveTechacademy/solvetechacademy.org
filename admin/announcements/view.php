<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("
SELECT
a.*,
c.course_title
FROM announcements a
LEFT JOIN courses c
ON a.course_id = c.id
WHERE a.id = ?
");

$stmt->execute([$id]);

$announcement = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$announcement){
    die("Announcement not found.");
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">Announcement Details</h2>

<div>

<a href="edit.php?id=<?= $announcement['id']; ?>" class="btn btn-warning">
<i class="fas fa-edit"></i> Edit
</a>

<a href="index.php" class="btn btn-secondary">
<i class="fas fa-arrow-left"></i> Back
</a>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">Announcement Information</h5>
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th width="220">Title</th>
<td><?= htmlspecialchars($announcement['title']); ?></td>
</tr>

<tr>
<th>Audience</th>
<td><?= htmlspecialchars($announcement['audience']); ?></td>
</tr>

<tr>
<th>Course</th>
<td>
<?= $announcement['course_title'] ? htmlspecialchars($announcement['course_title']) : 'N/A'; ?>
</td>
</tr>

<tr>
<th>Status</th>
<td>
<span class="badge bg-<?= $announcement['status']=='Published' ? 'success':'warning'; ?>">
<?= htmlspecialchars($announcement['status']); ?>
</span>
</td>
</tr>

<tr>
<th>Publish Date</th>
<td><?= date('d F Y', strtotime($announcement['publish_date'])); ?></td>
</tr>

<tr>
<th>Expiry Date</th>
<td>
<?= !empty($announcement['expiry_date']) ? date('d F Y', strtotime($announcement['expiry_date'])) : 'No Expiry'; ?>
</td>
</tr>

<tr>
<th>Message</th>
<td><?= nl2br(htmlspecialchars($announcement['message'])); ?></td>
</tr>

<tr>
<th>Created At</th>
<td><?= date('d F Y H:i', strtotime($announcement['created_at'])); ?></td>
</tr>

</table>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>