<?php

require_once '../includes/auth.php';

$pageTitle = "Courses";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';


if(isset($_SESSION['success'])){

    echo '<div class="alert alert-success alert-dismissible fade show">

    '.$_SESSION['success'].'

    <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>';

    unset($_SESSION['success']);

}

if(isset($_SESSION['error'])){

    echo '<div class="alert alert-danger alert-dismissible fade show">

    '.$_SESSION['error'].'

    <button class="btn-close" data-bs-dismiss="alert"></button>

    </div>';

    unset($_SESSION['error']);

}


$stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalCourses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();

$activeCourses = $pdo->query("
    SELECT COUNT(*)
    FROM courses
    WHERE status='Active'
")->fetchColumn();

$inactiveCourses = $pdo->query("
    SELECT COUNT(*)
    FROM courses
    WHERE status='Inactive'
")->fetchColumn();

?>
<div class="row mb-4">

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h2><?= $totalCourses ?></h2>
                <p class="mb-0">Total Courses</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h2><?= $activeCourses ?></h2>
                <p class="mb-0">Active Courses</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h2><?= $inactiveCourses ?></h2>
                <p class="mb-0">Inactive Courses</p>
            </div>
        </div>
    </div>

</div>
<div class="d-flex justify-content-between mb-4">

<h3>Courses</h3>

<a href="add.php" class="btn btn-primary">

<i class="fas fa-plus"></i>

Add Course

</a>

</div>

<table class="table table-bordered table-hover" id="studentsTable">

<thead>

<tr>

<th>Code</th>

<th>Thumbnail</th>

<th>Course</th>

<th>Category</th>

<th>Instructor</th>

<th>course_fee</th>

<th>Status</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php foreach($courses as $course): ?>

<tr>

<td><?= htmlspecialchars($course['course_code']) ?></td>

<td>

<?php if(!empty($course['thumbnail'])): ?>

<img
src="../../assets/uploads/courses/<?= htmlspecialchars($course['thumbnail']) ?>"
width="70"
class="rounded">

<?php endif; ?>

</td>

<td><?= htmlspecialchars($course['course_title']) ?></td>

<td><?= htmlspecialchars($course['category']) ?></td>

<td><?= htmlspecialchars($course['instructor']) ?></td>

<td><?= number_format($course['course_fee']) ?> FCFA</td>

<td>

<span class="badge bg-success">

<?= htmlspecialchars($course['status']) ?>

</span>

</td>

<td>

<a href="edit.php?id=<?= $course['id']; ?>" class="btn btn-warning btn-sm" title="Edit Course">

    <i class="fas fa-edit"></i>

</a>

<a href="../modules/index.php?course_id=<?= $course['id']; ?>"
   class="btn btn-info btn-sm"
   title="Manage Modules">

    <i class="fas fa-layer-group"></i>

</a>

<a href="#" class="btn btn-danger btn-sm" title="Delete Course">

    <i class="fas fa-trash"></i>

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<?php

require_once '../includes/footer.php';

?>