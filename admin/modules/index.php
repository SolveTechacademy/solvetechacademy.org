<?php

require_once '../includes/auth.php';

$pageTitle = "Course Modules";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

if (!isset($_GET['course_id'])) {
    header("Location: ../courses/index.php");
    exit();
}

$course_id = (int) $_GET['course_id'];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);

$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    $_SESSION['error'] = "Course not found.";
    header("Location: ../courses/index.php");
    exit();
}

$stmt = $pdo->prepare("
    SELECT *
    FROM course_modules
    WHERE course_id = ?
    ORDER BY module_order ASC
");

$stmt->execute([$course_id]);

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3><?= htmlspecialchars($course['course_title']); ?></h3>

        <small class="text-muted">
            Manage Course Modules
        </small>

    </div>

    <a href="add.php?course_id=<?= $course_id; ?>" class="btn btn-primary">

        <i class="fas fa-plus"></i>

        Add Module

    </a>

</div>
<table class="table table-bordered table-hover" id="modulesTable">

    <thead>

        <tr>

            <th width="80">Order</th>
            <th>Module Title</th>
            <th>Description</th>
            <th>Status</th>
            <th width="180">Actions</th>

        </tr>

    </thead>

    <tbody>

    <?php if(count($modules) > 0): ?>

        <?php foreach($modules as $module): ?>

        <tr>

            <td><?= $module['module_order']; ?></td>

            <td><?= htmlspecialchars($module['module_title']); ?></td>

            <td><?= htmlspecialchars($module['description']); ?></td>

            <td>

                <span class="badge bg-success">

                    <?= htmlspecialchars($module['status']); ?>

                </span>

            </td>

            <td>

                <a href="edit.php?id=<?= $module['id']; ?>" class="btn btn-warning btn-sm">

                    <i class="fas fa-edit"></i>

                </a>

                <a href="delete.php?id=<?= $module['id']; ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this module?');">

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