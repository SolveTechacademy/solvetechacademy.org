<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Generate Certificate";

include '../includes/header.php';
include '../includes/sidebar.php';

/*
|--------------------------------------------------------------------------
| Students Eligible for Certificates
|--------------------------------------------------------------------------
| Requirements:
| 1. Registration Approved
| 2. Payment Completed
| 3. No certificate already generated
*/

$sql = "
SELECT
    r.id AS registration_id,
    s.id AS student_id,
    s.student_id AS student_code,
    s.fullname,
    c.id AS course_id,
    c.course_title
FROM registrations r

INNER JOIN students s
    ON r.student_id = s.id

INNER JOIN courses c
    ON r.course_id = c.id

LEFT JOIN certificates cert
    ON cert.registration_id = r.id

WHERE
r.approval_status='Approved'
AND r.payment_status='Paid'
    AND cert.id IS NULL

ORDER BY s.fullname ASC
";

$stmt = $pdo->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="content">
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Generate Certificate</h2>

        <a href="index.php" class="btn btn-secondary">
            Back
        </a>

    </div>

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            Eligible Students
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (!empty($students)): ?>

                    <?php $i = 1; ?>

                    <?php foreach ($students as $student): ?>

                        <tr>

                            <td><?= $i++; ?></td>

                            <td><?= htmlspecialchars($student['student_code']); ?></td>

                            <td><?= htmlspecialchars($student['fullname']); ?></td>

                            <td><?= htmlspecialchars($student['course_title']); ?></td>

                            <td>

                                <a href="save.php?registration_id=<?= $student['registration_id']; ?>"
                                   class="btn btn-success btn-sm">

                                    <i class="fas fa-certificate"></i>

                                    Generate

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5" class="text-center text-danger">

                            No eligible students found.

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>