<?php
require_once '../includes/auth.php';

$pageTitle = "Add Quiz Question";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 0;

if ($quiz_id <= 0) {
    $_SESSION['error'] = "Invalid Quiz.";
    header("Location:index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT q.*, cm.module_title
    FROM quizzes q
    LEFT JOIN course_modules cm
        ON q.module_id = cm.id
    WHERE q.id=?
");
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    $_SESSION['error'] = "Quiz not found.";
    header("Location:index.php");
    exit;
}
?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-8 mx-auto">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">
                        Add Question
                    </h5>

                </div>

                <div class="card-body">

                    <?php if(isset($_SESSION['error'])): ?>

                        <div class="alert alert-danger">

                            <?= $_SESSION['error']; ?>

                        </div>

                        <?php unset($_SESSION['error']); ?>

                    <?php endif; ?>

                    <form action="store_question.php" method="POST">

                        <input type="hidden"
                               name="quiz_id"
                               value="<?= $quiz_id ?>">

                        <div class="mb-3">

                            <label class="form-label">
                                Quiz
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($quiz['title']) ?>"
                                   readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Question
                            </label>

                            <textarea
                                name="question"
                                rows="4"
                                class="form-control"
                                required></textarea>

                        </div>

                        <div class="mb-3">

                            <label>Option A</label>

                            <input
                                type="text"
                                name="option_a"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Option B</label>

                            <input
                                type="text"
                                name="option_b"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Option C</label>

                            <input
                                type="text"
                                name="option_c"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Option D</label>

                            <input
                                type="text"
                                name="option_d"
                                class="form-control"
                                required>

                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <label>
                                    Correct Answer
                                </label>

                                <select
                                    name="correct_option"
                                    class="form-control"
                                    required>

                                    <option value="">
                                        Select
                                    </option>

                                    <option value="A">A</option>

                                    <option value="B">B</option>

                                    <option value="C">C</option>

                                    <option value="D">D</option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label>
                                    Marks
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="marks"
                                    value="1"
                                    min="1"
                                    required>

                            </div>

                        </div>

                        <hr>

                        <button
                            class="btn btn-success">

                            Save Question

                        </button>

                        <a
                            href="questions.php?quiz_id=<?= $quiz_id ?>"
                            class="btn btn-secondary">

                            Back

                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>