<?php

require_once '../includes/auth.php';

$pageTitle = "Quizzes";

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

$sql = "
SELECT
    q.*,
    cm.module_title,
    c.course_title,
    (
        SELECT COUNT(*)
        FROM quiz_questions qq
        WHERE qq.quiz_id = q.id
    ) AS total_questions
FROM quizzes q
INNER JOIN course_modules cm
    ON q.module_id = cm.id
INNER JOIN courses c
    ON cm.course_id = c.id
ORDER BY q.id DESC
";

$stmt = $pdo->query($sql);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Quiz Management</h3>

        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Quiz
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-bordered table-striped" id="dataTable">

                <thead>

                <tr>

                    <th>ID</th>
                    <th>Course</th>
                    <th>Module</th>
                    <th>Quiz</th>
                    <th>Questions</th>
                    <th>Pass Mark</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th width="220">Actions</th>

                </tr>

                </thead>

                <tbody>

                <?php foreach($quizzes as $quiz): ?>

                <tr>

                    <td><?= $quiz['id'] ?></td>

                    <td><?= htmlspecialchars($quiz['course_title']) ?></td>

                    <td><?= htmlspecialchars($quiz['module_title']) ?></td>

                    <td><?= htmlspecialchars($quiz['title']) ?></td>

                    <td><?= $quiz['total_questions'] ?></td>

                    <td><?= $quiz['pass_mark'] ?>%</td>

                    <td><?= $quiz['duration'] ?> mins</td>

                    <td>
                        <?php if($quiz['status']=="Active"): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </td>

                    <td>

                        <a href="questions.php?quiz_id=<?= $quiz['id'] ?>" class="btn btn-success btn-sm">
                          Questions
                        </a>

                        <a href="edit.php?id=<?= $quiz['id'] ?>" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="delete.php?id=<?= $quiz['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this quiz?');">
                            Delete
                        </a>

                    </td>

                </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>