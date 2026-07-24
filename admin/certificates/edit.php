<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

if (!isset($_GET['registration_id'])) {
    die("Invalid Request");
}

$registration_id = (int)$_GET['registration_id'];

$stmt = $pdo->prepare("
SELECT
    cert.*,
    s.fullname,
    s.student_id,
    c.course_title

FROM certificates cert

INNER JOIN students s
ON cert.student_id = s.id

INNER JOIN courses c
ON cert.course_id = c.id

WHERE cert.registration_id = ?

LIMIT 1
");

$stmt->execute([$registration_id]);

$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$certificate) {
    die("Certificate not found.");
}
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-8 mx-auto">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4>Edit Certificate</h4>

</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden"
name="id"
value="<?= $certificate['id']; ?>">
<input type="hidden"
name="registration_id"
value="<?= $certificate['registration_id']; ?>">

<div class="mb-3">
<label class="form-label">Student</label>
<input type="text"
class="form-control"
value="<?= htmlspecialchars($certificate['fullname']); ?>"
readonly>
</div>

<div class="mb-3">
<label class="form-label">Student ID</label>
<input type="text"
class="form-control"
value="<?= htmlspecialchars($certificate['student_id']); ?>"
readonly>
</div>

<div class="mb-3">
<label class="form-label">Course</label>
<input type="text"
class="form-control"
value="<?= htmlspecialchars($certificate['course_title']); ?>"
readonly>
</div>

<div class="mb-3">
<label class="form-label">Certificate Number</label>
<input
type="text"
name="certificate_number"
class="form-control"
value="<?= htmlspecialchars($certificate['certificate_number']); ?>"
required>
</div>

<div class="mb-3">
<label class="form-label">Issue Date</label>
<input
type="date"
name="issue_date"
class="form-control"
value="<?= $certificate['issue_date']; ?>"
required>
</div>

<div class="mb-3">
<label class="form-label">Completion Date</label>
<input
type="date"
name="completion_date"
class="form-control"
value="<?= $certificate['completion_date']; ?>"
required>
</div>
<div class="mb-3">
<label class="form-label">Grade</label>
<input
type="text"
name="grade"
class="form-control"
value="<?= htmlspecialchars($certificate['grade']); ?>"
required>
</div>

<div class="mb-3">
<label class="form-label">Status</label>
<select
name="status"
class="form-select"
required>

<option value="Issued"
<?= $certificate['status']=='Issued' ? 'selected' : ''; ?>>
Issued
</option>

<option value="Pending"
<?= $certificate['status']=='Pending' ? 'selected' : ''; ?>>
Pending
</option>

<option value="Cancelled"
<?= $certificate['status']=='Cancelled' ? 'selected' : ''; ?>>
Cancelled
</option>

</select>
</div>

<div class="text-end">

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

<button
type="submit"
class="btn btn-primary">

Update Certificate

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>