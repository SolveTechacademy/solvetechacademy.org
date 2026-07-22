<?php

require_once '../includes/auth.php';
$students = $pdo->query("
SELECT
    s.id,
    s.student_id,
    s.fullname,
    s.email,
    s.phone,
    s.status,
    r.payment_status,
    c.course_title
FROM students s
LEFT JOIN registrations r ON s.id = r.student_id
LEFT JOIN courses c ON r.course_id = c.id
ORDER BY s.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
$pageTitle = "Student Management";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';
?>

<h2 class="mb-4">
    Student Management
</h2>
<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h6>Total Students</h6>

<h2><?= count($students); ?></h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h6>Active</h6>

<h2>

<?= count(array_filter($students,function($s){

return $s['status']=="Active";

})); ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h6>Pending</h6>

<h2>

<?= count(array_filter($students,function($s){

return $s['status']=="Pending";

})); ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow">

<div class="card-body">

<h6>Suspended</h6>

<h2>

<?= count(array_filter($students,function($s){

return $s['status']=="Suspended";

})); ?>

</h2>

</div>

</div>

</div>

</div>
<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

Registered Students

</h5>

</div>

<div class="card-body">

<table id="studentsTable" class="table table-striped table-hover">

<thead>

<tr>

<th>#</th>

<th>Student ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Course</th>

<th>Status</th>

<th>Payment</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

<?php foreach($students as $student){ ?>

<tr>

<td><?= $student['id']; ?></td>

<td><?= htmlspecialchars($student['student_id']); ?></td>

<td><?= htmlspecialchars($student['fullname']); ?></td>

<td><?= htmlspecialchars($student['email']); ?></td>

<td><?= htmlspecialchars($student['phone']); ?></td>

<td><?= htmlspecialchars($student['course_title'] ?? 'Not Assigned'); ?></td>

<td>

<?php

if($student['status']=="Active"){

    echo '<span class="badge bg-success">Active</span>';

}elseif($student['status']=="Pending"){

    echo '<span class="badge bg-warning text-dark">Pending</span>';

}else{

    echo '<span class="badge bg-danger">Suspended</span>';

}

?>

</td>

<td>

<?php

$payment = $student['payment_status'] ?? 'Pending';

if($payment=="Paid"){

    echo '<span class="badge bg-success">Paid</span>';

}elseif($payment=="Pending"){

    echo '<span class="badge bg-warning text-dark">Pending</span>';

}else{

    echo '<span class="badge bg-danger">'.$payment.'</span>';

}

?>

</td>

<td>

<div class="btn-group">

<a
href="view.php?id=<?= $student['id']; ?>"
class="btn btn-primary btn-sm">

<i class="fas fa-eye"></i>

</a>

<a
href="edit.php?id=<?= $student['id']; ?>"
class="btn btn-warning btn-sm">

<i class="fas fa-edit"></i>

</a>

<a
href="delete.php?id=<?= $student['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?');">

<i class="fas fa-trash"></i>

</a>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php

require_once '../includes/footer.php';