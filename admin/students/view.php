<?php

require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Student not found.";
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT
        s.*,
        c.course_title,
        r.payment_status
    FROM students s
    LEFT JOIN registrations r ON s.id = r.student_id
    LEFT JOIN courses c ON r.course_id = c.id
    WHERE s.id = ?
");

$stmt->execute([$id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    $_SESSION['error'] = "Student not found.";
    header("Location: index.php");
    exit();
}

$pageTitle = "Student Profile";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

?>

<div class="card shadow">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">
            Student Profile
        </h4>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-3 text-center">

                <img src="../../assets/images/default-user.png"
                     class="img-fluid rounded-circle mb-3"
                     width="180"
                     alt="Student">

            </div>

            <div class="col-md-9">

                <table class="table table-bordered">

                    <tr>
                        <th width="220">Student ID</th>
                        <td><?= htmlspecialchars($student['student_id']); ?></td>
                    </tr>

                    <tr>
                        <th>Full Name</th>
                        <td><?= htmlspecialchars($student['fullname']); ?></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($student['email']); ?></td>
                    </tr>

                    <tr>
                        <th>Phone</th>
                        <td><?= htmlspecialchars($student['phone']); ?></td>
                    </tr>

                    <tr>
                        <th>Course</th>
                        <td><?= htmlspecialchars($student['course_title'] ?? 'Not Assigned'); ?></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td><?= htmlspecialchars($student['status']); ?></td>
                    </tr>

                    <tr>
                        <th>Payment Status</th>
                        <td><?= htmlspecialchars($student['payment_status'] ?? 'Pending'); ?></td>
                    </tr>

                    <tr>
                        <th>Registered On</th>
                        <td><?= date('d M Y', strtotime($student['created_at'])); ?></td>
                    </tr>

                </table>

            </div>

        </div>

        <div class="mt-3">

            <a href="edit.php?id=<?= $student['id']; ?>" class="btn btn-warning">

                <i class="fas fa-edit"></i> Edit Student

            </a>

            <a href="index.php" class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i> Back

            </a>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>