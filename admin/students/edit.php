<?php

require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Student not found.";
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    $_SESSION['error'] = "Student not found.";
    header("Location: index.php");
    exit();
}

$pageTitle = "Edit Student";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="card shadow">

    <div class="card-header bg-warning">

        <h4 class="mb-0">
            Edit Student
        </h4>

    </div>

    <div class="card-body">

        <form action="update.php" method="POST">

            <input type="hidden" name="id" value="<?= $student['id']; ?>">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Full Name</label>

                    <input type="text"
                           name="fullname"
                           class="form-control"
                           value="<?= htmlspecialchars($student['fullname']); ?>"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Email</label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= htmlspecialchars($student['email']); ?>"
                           required>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Phone</label>

                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="<?= htmlspecialchars($student['phone']); ?>">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">

                        <option value="Active" <?= $student['status'] == 'Active' ? 'selected' : ''; ?>>
                            Active
                        </option>

                        <option value="Pending" <?= $student['status'] == 'Pending' ? 'selected' : ''; ?>>
                            Pending
                        </option>

                        <option value="Suspended" <?= $student['status'] == 'Suspended' ? 'selected' : ''; ?>>
                            Suspended
                        </option>

                    </select>

                </div>

            </div>

            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Update Student
            </button>

            <a href="index.php" class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>