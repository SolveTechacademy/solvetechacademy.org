<?php

require_once '../includes/auth.php';

$pageTitle = "Edit Module";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

if (!isset($_GET['id'])) {

    header("Location: ../courses/index.php");
    exit();

}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM course_modules WHERE id=?");
$stmt->execute([$id]);

$module = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$module) {

    $_SESSION['error'] = "Module not found.";

    header("Location: ../courses/index.php");
    exit();

}

?>

<div class="card shadow">

    <div class="card-header bg-warning">

        <h4 class="mb-0">

            Edit Module

        </h4>

    </div>

    <div class="card-body">

        <form action="update.php" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?= $module['id']; ?>">

            <input
                type="hidden"
                name="course_id"
                value="<?= $module['course_id']; ?>">

            <div class="mb-3">

                <label class="form-label">

                    Module Title

                </label>

                <input
                    type="text"
                    name="module_title"
                    class="form-control"
                    value="<?= htmlspecialchars($module['module_title']); ?>"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">

                    Description

                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"><?= htmlspecialchars($module['description']); ?></textarea>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <label class="form-label">

                        Display Order

                    </label>

                    <input
                        type="number"
                        name="module_order"
                        class="form-control"
                        value="<?= $module['module_order']; ?>"
                        required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="Active" <?= $module['status']=='Active' ? 'selected' : ''; ?>>

                            Active

                        </option>

                        <option value="Inactive" <?= $module['status']=='Inactive' ? 'selected' : ''; ?>>

                            Inactive

                        </option>

                    </select>

                </div>

            </div>

            <br>

            <button class="btn btn-primary">

                <i class="fas fa-save"></i>

                Update Module

            </button>

            <a
                href="index.php?course_id=<?= $module['course_id']; ?>"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>