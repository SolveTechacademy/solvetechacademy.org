<?php
require_once '../includes/auth.php';

$pageTitle = "Quiz Questions";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;

if ($quiz_id <= 0) {
    $_SESSION['error'] = "Invalid quiz selected.";
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
SELECT
    q.*,
    cm.module_title
FROM quizzes q
LEFT JOIN course_modules cm
    ON q.module_id = cm.id
WHERE q.id = ?
");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    $_SESSION['error'] = "Quiz not found.";
    header("Location:index.php");
    exit;
}


if (!$quiz) {
    $_SESSION['error'] = "Quiz not found.";
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM quiz_questions
    WHERE quiz_id = ?
    ORDER BY id ASC
");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><?= htmlspecialchars($quiz['title']) ?></h3>
            <small class="text-muted">
                <?= htmlspecialchars($quiz['module_title'] ?? '') ?>
            </small>
        </div>

        <div>
            <a href="index.php" class="btn btn-secondary">
                Back
            </a>

            <a href="add_question.php?quiz_id=<?= $quiz_id ?>" class="btn btn-primary">
                Add Question
            </a>
        </div>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <strong>Questions</strong>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Question</th>
                        <th width="90">Marks</th>
                        <th width="120">Answer</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if(count($questions)): ?>

                    <?php foreach($questions as $i => $row): ?>

                    <tr>

                        <td><?= $i + 1 ?></td>

                        <td>
                            <?= nl2br(htmlspecialchars($row['question'])) ?>
                        </td>

                        <td><?= $row['marks'] ?></td>

                        <td>
                            <span class="badge bg-success">
                                <?= htmlspecialchars($row['correct_option']) ?>
                            </span>
                        </td>

                        <td>

                            <a href="edit_question.php?id=<?= $row['id'] ?>"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <a href="delete_question.php?id=<?= $row['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this question?')">
                                Delete
                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="text-center">
                            No questions added yet.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>