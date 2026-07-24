<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Assignments";

try {

    $stmt = $pdo->query("
            SELECT
        a.*,
        l.lesson_title,
        cm.module_title,
        c.course_title
        FROM assignments a
        LEFT JOIN lessons l ON a.lesson_id = l.id
        LEFT JOIN course_modules cm ON l.module_id = cm.id
        LEFT JOIN courses c ON cm.course_id = c.id
        ORDER BY a.id DESC
    ");

    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    die($e->getMessage());
}

$totalAssignments = count($assignments);

$activeAssignments = $pdo->query("
SELECT COUNT(*)
FROM assignments
WHERE deadline >= CURDATE()
")->fetchColumn();

$expiredAssignments = $pdo->query("
SELECT COUNT(*)
FROM assignments
WHERE deadline < CURDATE()
")->fetchColumn();

$todayAssignments = $pdo->query("
SELECT COUNT(*)
FROM assignments
WHERE deadline = CURDATE()
")->fetchColumn();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>
<h2 class="fw-bold">Assignment Management</h2>
<p class="text-muted mb-0">
Manage all lesson assignments
</p>
</div>

<a href="create.php" class="btn btn-primary">
<i class="fas fa-plus-circle"></i>
New Assignment
</a>

</div>

<div class="row">

<div class="col-md-3 mb-3">
<div class="card shadow-sm">
<div class="card-body">
<h6 class="text-muted">Total Assignments</h6>
<h2><?= $totalAssignments ?></h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card shadow-sm">
<div class="card-body">
<h6 class="text-muted">Active</h6>
<h2 class="text-success"><?= $activeAssignments ?></h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card shadow-sm">
<div class="card-body">
<h6 class="text-muted">Expired</h6>
<h2 class="text-danger"><?= $expiredAssignments ?></h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card shadow-sm">
<div class="card-body">
<h6 class="text-muted">Due Today</h6>
<h2 class="text-warning"><?= $todayAssignments ?></h2>
</div>
</div>
</div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h5 class="mb-0">Assignment List</h5>
</div>

<div class="card-body">

<table id="dataTable" class="table table-bordered table-hover">

<thead>

<tr>

<th>#</th>
<th>Course</th>
<th>Module</th>
<th>Lesson</th>
<th>Assignment</th>
<th>Deadline</th>
<th>Created</th>
<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php $sn=1; ?>

<?php foreach($assignments as $assignment): ?>

<tr>

<td><?= $sn++ ?></td>

<td><?= htmlspecialchars($assignment['course_title']) ?></td>

<td><?= htmlspecialchars($assignment['module_title']) ?></td>

<td><?= htmlspecialchars($assignment['lesson_title']) ?></td>

<td><?= htmlspecialchars($assignment['title']) ?></td>

<td>

<?php

if(strtotime($assignment['deadline']) < strtotime(date('Y-m-d'))){

echo '<span class="badge bg-danger">'
.date('d M Y',strtotime($assignment['deadline']))
.'</span>';

}else{

echo '<span class="badge bg-success">'
.date('d M Y',strtotime($assignment['deadline']))
.'</span>';

}

?>

</td>

<td><?= date('d M Y',strtotime($assignment['created_at'])) ?></td>

<td>

<div class="btn-group btn-group-sm">

<a href="view.php?id=<?= $assignment['id'] ?>" class="btn btn-primary">
<i class="fas fa-eye"></i>
</a>

<a href="edit.php?id=<?= $assignment['id'] ?>" class="btn btn-warning">
<i class="fas fa-edit"></i>
</a>

<a href="delete.php?id=<?= $assignment['id'] ?>"
class="btn btn-danger"
onclick="return confirm('Delete this assignment?')">

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