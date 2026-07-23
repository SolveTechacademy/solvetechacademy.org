<?php

require_once '../includes/auth.php';

$pageTitle = "Add Module";

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
// Get next module order
$orderStmt = $pdo->prepare("
    SELECT COALESCE(MAX(module_order),0)+1 AS next_order
    FROM course_modules
    WHERE course_id=?
");

$orderStmt->execute([$course_id]);

$nextOrder = $orderStmt->fetch(PDO::FETCH_ASSOC)['next_order'];

if (!$course) {

    $_SESSION['error'] = "Course not found.";

    header("Location: ../courses/index.php");

    exit();

}
?>
<div class="card shadow">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">

            Add Module

        </h4>

    </div>

    <div class="card-body">

        <form action="save.php" method="POST">

            <input type="hidden" name="course_id" value="<?= $course_id; ?>">

            <div class="mb-3">

                <label>Module Title</label>

                <input
                    type="text"
                    name="module_title"
                    class="form-control"
                    required>

            </div>

            <div class="mb-3">

                <label>Description</label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"></textarea>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <label>Display Order</label>

                    <input
                        type="number"
                        name="module_order"
                        class="form-control"
                        value="<?= $nextOrder; ?>"

                </div>

                <div class="col-md-6">

                    <label>Status</label>

                    <select
                        name="status"
                        class="form-select">

                        <option>Active</option>
                        <option>Inactive</option>

                    </select>

                </div>

            </div>

            <br>

            <button class="btn btn-primary">

                <i class="fas fa-save"></i>

                Save Module

            </button>

            <a href="index.php?course_id=<?= $course_id; ?>" class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>