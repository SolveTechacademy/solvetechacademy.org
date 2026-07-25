<?php
require_once '../includes/auth.php';

$pageTitle = "Edit Quiz";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error'] = "Invalid quiz.";
    header("Location:index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM quizzes
    WHERE id = ?
");
$stmt->execute([$id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    $_SESSION['error'] = "Quiz not found.";
    header("Location:index.php");
    exit;
}

// Load all courses
$courses = $pdo->query("
    SELECT id, course_title
    FROM courses
    ORDER BY course_title ASC
")->fetchAll(PDO::FETCH_ASSOC);

// Find the course that owns this quiz's module
$stmt = $pdo->prepare("
    SELECT
        cm.id,
        cm.module_title,
        cm.course_id
    FROM course_modules cm
    WHERE cm.id = ?
");
$stmt->execute([$quiz['module_id']]);

$currentModule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentModule) {
    $_SESSION['error'] = "Module not found.";
    header("Location:index.php");
    exit;
}

$currentCourseId = $currentModule['course_id'];

// Load modules belonging to that course
$stmt = $pdo->prepare("
    SELECT id, module_title
    FROM course_modules
    WHERE course_id = ?
    ORDER BY module_order ASC
");
$stmt->execute([$currentCourseId]);

$modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Edit Quiz</h3>
        <a href="index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <form action="update.php" method="POST">

                <input type="hidden" name="id" value="<?= $quiz['id']; ?>">

                <div class="mb-3">
                    <label>Course</label>
                    <select name="course_id" class="form-control" required>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id']; ?>"
                                <?= ($course['id'] == $currentCourseId) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($course['course_title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Module</label>
                    <select name="module_id" class="form-control" required>
                        <?php foreach ($modules as $module): ?>
                            <option value="<?= $module['id']; ?>"
                                <?= ($module['id'] == $quiz['module_id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($module['module_title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Quiz Title</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="<?= htmlspecialchars($quiz['title']); ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea
                        name="description"
                        rows="4"
                        class="form-control"><?= htmlspecialchars($quiz['description']); ?></textarea>
                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Pass Mark (%)</label>
                        <input
                            type="number"
                            name="pass_mark"
                            class="form-control"
                            value="<?= $quiz['pass_mark']; ?>"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Duration (Minutes)</label>
                        <input
                            type="number"
                            name="duration"
                            class="form-control"
                            value="<?= $quiz['duration']; ?>"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Attempts</label>
                        <input
                            type="number"
                            name="attempts"
                            class="form-control"
                            value="<?= $quiz['attempts']; ?>"
                            required>
                    </div>

                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= $quiz['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= $quiz['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Quiz
                </button>

            </form>

        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>