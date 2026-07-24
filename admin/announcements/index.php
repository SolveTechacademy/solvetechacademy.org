<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Announcements";

try{

$stmt = $pdo->query("
SELECT
a.*,
c.course_title
FROM announcements a
LEFT JOIN courses c
ON a.course_id = c.id
ORDER BY a.id DESC
");

$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
die($e->getMessage());
}

$totalAnnouncements = $pdo->query("
SELECT COUNT(*)
FROM announcements
")->fetchColumn();

$published = $pdo->query("
SELECT COUNT(*)
FROM announcements
WHERE status='Published'
")->fetchColumn();

$drafts = $pdo->query("
SELECT COUNT(*)
FROM announcements
WHERE status='Draft'
")->fetchColumn();

$today = $pdo->query("
SELECT COUNT(*)
FROM announcements
WHERE publish_date = CURDATE()
")->fetchColumn();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">Announcement Management</h2>

<p class="text-muted mb-0">
Create and manage LMS announcements.
</p>

</div>

<a href="create.php" class="btn btn-primary">

<i class="fas fa-plus-circle"></i>

New Announcement

</a>

</div>

<div class="row">

<div class="col-md-3 mb-3">

<div class="card shadow-sm">

<div class="card-body">

<h6 class="text-muted">Total</h6>

<h2><?= $totalAnnouncements ?></h2>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card shadow-sm">

<div class="card-body">

<h6 class="text-muted">Published</h6>

<h2 class="text-success"><?= $published ?></h2>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card shadow-sm">

<div class="card-body">

<h6 class="text-muted">Drafts</h6>

<h2 class="text-warning"><?= $drafts ?></h2>

</div>

</div>

</div>

<div class="col-md-3 mb-3">

<div class="card shadow-sm">

<div class="card-body">

<h6 class="text-muted">Today</h6>

<h2 class="text-primary"><?= $today ?></h2>

</div>

</div>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">
Announcements
</h5>

</div>

<div class="card-body">

<table id="dataTable" class="table table-bordered table-hover">

<thead>

<tr>

<th>#</th>
<th>Title</th>
<th>Audience</th>
<th>Course</th>
<th>Status</th>
<th>Publish Date</th>
<th width="180">Actions</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($announcements as $announcement): ?>

<tr>

<td><?= $sn++; ?></td>

<td><?= htmlspecialchars($announcement['title']); ?></td>

<td><?= htmlspecialchars($announcement['audience']); ?></td>

<td>

<?= $announcement['course_title'] ? htmlspecialchars($announcement['course_title']) : "-"; ?>

</td>

<td>

<?php if($announcement['status']=="Published"): ?>

<span class="badge bg-success">
Published
</span>

<?php else: ?>

<span class="badge bg-warning">
Draft
</span>

<?php endif; ?>

</td>

<td>

<?= date('d M Y',strtotime($announcement['publish_date'])); ?>

</td>

<td>

<div class="btn-group btn-group-sm">

<a href="view.php?id=<?= $announcement['id']; ?>" class="btn btn-primary">

<i class="fas fa-eye"></i>

</a>

<a href="edit.php?id=<?= $announcement['id']; ?>" class="btn btn-warning">

<i class="fas fa-edit"></i>

</a>

<a href="delete.php?id=<?= $announcement['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Delete this announcement?')">

<i class="fas fa-trash"></i>

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include '../includes/footer.php'; ?>

<script>

$(function(){

$('#dataTable').DataTable({
responsive:true,
pageLength:10
});

});

</script>