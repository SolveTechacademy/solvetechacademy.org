<?php

require_once '../includes/auth.php';

$pageTitle = "Instructor Management";

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

$stmt = $pdo->query("
    SELECT *
    FROM instructors
    ORDER BY full_name ASC
");

$instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3>Instructor Management</h3>

        <small class="text-muted">

            Manage all course instructors

        </small>

    </div>

    <a href="add.php" class="btn btn-primary">

        <i class="fas fa-plus"></i>

        Add Instructor

    </a>

</div>

<table class="table table-bordered table-hover" id="instructorsTable">

    <thead>

        <tr>

            <th width="90">Photo</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Specialization</th>
            <th>Experience</th>
            <th>Status</th>
            <th width="170">Actions</th>

        </tr>

    </thead>

    <tbody>

<?php if(count($instructors)>0): ?>

<?php foreach($instructors as $row): ?>

<tr>

<td>

<?php if(!empty($row['photo'])): ?>

<img
src="../../assets/uploads/instructors/<?= htmlspecialchars($row['photo']); ?>"
style="width:60px;height:60px;object-fit:cover;border-radius:50%;">

<?php else: ?>

<div
style="width:60px;height:60px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">

<i class="fas fa-user"></i>

</div>

<?php endif; ?>

</td>

<td><?= htmlspecialchars($row['full_name']); ?></td>

<td><?= htmlspecialchars($row['email']); ?></td>

<td><?= htmlspecialchars($row['phone']); ?></td>

<td><?= htmlspecialchars($row['specialization']); ?></td>

<td><?= (int)$row['years_experience']; ?> Years</td>

<td>

<?php if($row['status']=="Active"): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactive

</span>

<?php endif; ?>

</td>

<td>

<a
href="edit.php?id=<?= $row['id']; ?>"
class="btn btn-warning btn-sm">

<i class="fas fa-edit"></i>

</a>

<a
href="delete.php?id=<?= $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this instructor?')">

<i class="fas fa-trash"></i>

</a>

</td>

</tr>

<?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>

<?php

require_once '../includes/footer.php';

?>