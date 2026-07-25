<?php
require_once '../includes/auth.php';

$pageTitle = "Edit Question";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    $_SESSION['error'] = "Invalid Question.";
    header("Location:index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM quiz_questions WHERE id=?");
$stmt->execute([$id]);

$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    $_SESSION['error'] = "Question not found.";
    header("Location:index.php");
    exit;
}
?>

<div class="container-fluid">

<div class="row">

<div class="col-lg-8 mx-auto">

<div class="card shadow">

<div class="card-header bg-warning">

<h5 class="mb-0">Edit Question</h5>

</div>

<div class="card-body">

<form action="update_question.php" method="POST">

<input type="hidden"
       name="id"
       value="<?= $question['id'] ?>">

<input type="hidden"
       name="quiz_id"
       value="<?= $question['quiz_id'] ?>">

<div class="mb-3">

<label>Question</label>

<textarea
class="form-control"
name="question"
rows="4"
required><?= htmlspecialchars($question['question']) ?></textarea>

</div>

<div class="mb-3">

<label>Option A</label>

<input
type="text"
class="form-control"
name="option_a"
value="<?= htmlspecialchars($question['option_a']) ?>"
required>

</div>

<div class="mb-3">

<label>Option B</label>

<input
type="text"
class="form-control"
name="option_b"
value="<?= htmlspecialchars($question['option_b']) ?>"
required>

</div>

<div class="mb-3">

<label>Option C</label>

<input
type="text"
class="form-control"
name="option_c"
value="<?= htmlspecialchars($question['option_c']) ?>"
required>

</div>

<div class="mb-3">

<label>Option D</label>

<input
type="text"
class="form-control"
name="option_d"
value="<?= htmlspecialchars($question['option_d']) ?>"
required>

</div>

<div class="row">

<div class="col-md-6">

<label>Correct Answer</label>

<select
class="form-control"
name="correct_option"
required>

<?php
foreach(['A','B','C','D'] as $opt){
?>

<option
value="<?= $opt ?>"
<?= $question['correct_option']==$opt?'selected':'' ?>>

<?= $opt ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6">

<label>Marks</label>

<input
type="number"
class="form-control"
name="marks"
value="<?= $question['marks'] ?>"
required>

</div>

</div>

<hr>

<button class="btn btn-success">

Update Question

</button>

<a
href="questions.php?quiz_id=<?= $question['quiz_id'] ?>"
class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>