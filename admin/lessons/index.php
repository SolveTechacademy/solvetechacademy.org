<?php

require_once '../includes/auth.php';

$pageTitle = "Lesson Management";

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


if (!isset($_GET['module_id'])) {

    header("Location: ../courses/index.php");
    exit();

}

$module_id = (int)$_GET['module_id'];
$stmt = $pdo->prepare("SELECT * FROM course_modules WHERE id = ?");

$stmt->execute([$module_id]);

$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {

    $_SESSION['error'] = "Module not found.";

    header("Location: ../courses/index.php");

    exit();

}
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE module_id = ? ORDER BY lesson_order ASC");

$stmt->execute([$module_id]);

$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3><?= htmlspecialchars($module['module_title']); ?></h3>

        <small class="text-muted">

            Manage Lessons

        </small>

    </div>

    <a href="add.php?module_id=<?= $module_id; ?>" class="btn btn-primary">

        <i class="fas fa-plus"></i>

        Add Lesson

    </a>

</div>
<table class="table table-bordered table-hover" id="lessonsTable">

    <thead>

        <tr>

            <th width="80">Order</th>
            <th>Lesson Title</th>
            <th>Type</th>
            <th>Preview</th>
            <th>Status</th>
            <th width="170">Actions</th>

        </tr>

    </thead>

    <tbody>

    <?php if(count($lessons) > 0): ?>

        <?php foreach($lessons as $lesson): ?>

        <tr>

            <td><?= $lesson['lesson_order']; ?></td>

            <td><?= htmlspecialchars($lesson['lesson_title']); ?></td>

            <td><?= htmlspecialchars($lesson['lesson_type']); ?></td>

            <td>

                <?php if($lesson['is_preview']){ ?>

                    <span class="badge bg-success">

                        Free

                    </span>

                <?php }else{ ?>

                    <span class="badge bg-secondary">

                        Locked

                    </span>

                <?php } ?>

            </td>

            <td>

<?php if($lesson['status'] == 'Active'): ?>

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

                <a href="edit.php?id=<?= $lesson['id']; ?>" class="btn btn-warning btn-sm">

                    <i class="fas fa-edit"></i>

                </a>

                <a href="delete.php?id=<?= $lesson['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this lesson?');">

                    <i class="fas fa-trash"></i>

                </a>

            </td>

        </tr>

        <?php endforeach; ?>
        <?php else: ?>

<tr>
    <td></td>
    <td class="text-center">No lessons have been added to this module.</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<?php endif; ?>

    </tbody>

</table>

<?php

require_once '../includes/footer.php';

?>