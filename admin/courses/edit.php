<?php

require_once '../includes/auth.php';

$pageTitle = "Edit Course";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);

$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    $_SESSION['error'] = "Course not found.";
    header("Location: index.php");
    exit();
}

?>
<div class="card shadow">

    <div class="card-header bg-warning">
        <h4 class="mb-0">
            <i class="fas fa-edit"></i>
            Edit Course
        </h4>
    </div>

    <div class="card-body">

        <form action="update.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $course['id']; ?>">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>Course Code</label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($course['course_code']); ?>"
                        readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Course Title</label>

                    <input
                        type="text"
                        class="form-control"
                        name="course_title"
                        value="<?= htmlspecialchars($course['course_title']); ?>"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label>Category</label>

                    <input
                        type="text"
                        class="form-control"
                        name="category"
                        value="<?= htmlspecialchars($course['category']); ?>">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Instructor</label>

                    <input
                        type="text"
                        class="form-control"
                        name="instructor"
                        value="<?= htmlspecialchars($course['instructor']); ?>">

                </div>

                <div class="col-md-4 mb-3">

                    <label>Duration</label>

                    <input
                        type="text"
                        class="form-control"
                        name="duration"
                        value="<?= htmlspecialchars($course['duration']); ?>">

                </div>

                <div class="col-md-4 mb-3">

                    <label>Level</label>

                    <select class="form-select" name="level">

                        <option <?= $course['level']=="Beginner" ? "selected" : ""; ?>>Beginner</option>

                        <option <?= $course['level']=="Intermediate" ? "selected" : ""; ?>>Intermediate</option>

                        <option <?= $course['level']=="Advanced" ? "selected" : ""; ?>>Advanced</option>

                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label>Price</label>

                    <input
                        type="number"
                        class="form-control"
                        name="price"
                        value="<?= $course['price']; ?>">

                </div>

                <div class="col-md-12">

                    <button class="btn btn-warning">
                        <i class="fas fa-save"></i>
                        Update Course
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>