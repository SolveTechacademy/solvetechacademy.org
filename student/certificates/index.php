<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_SESSION['student_db_id'])) {
    header("Location: ../login.php");
    exit;
}

$student_id = $_SESSION['student_db_id'];

$page_title = "My Certificates";

/*
|--------------------------------------------------------------------------
| GET STUDENT CERTIFICATES
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
    c.*,
    co.course_title,
    r.registration_number
FROM certificates c

INNER JOIN courses co
    ON co.id = c.course_id

INNER JOIN registrations r
    ON r.id = c.registration_id

WHERE c.student_id = ?

ORDER BY c.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);

$certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);



$pageTitle = "My Certificates";

require_once '../../includes/header.php';


?>

<div class="container-fluid mt-4">

    <div class="row">

        <div class="col-md-12">

           <div class="card mb-4">

                <div class="card-header bg-primary text-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <h3 class="fw-bold text-primary mb-1">

🏆 My Certificates

</h3>

<p class="text-muted mb-0">

All certificates you have earned at SolveTech Academy.

</p>

                    </div>

                </div>

                <div class="card-body">

<?php if(count($certificates)==0): ?>

<div class="text-center py-5">

<i class="fas fa-award"
style="font-size:70px;color:#FF8A3D;"></i>

<h4 class="mt-4">

No Certificates Yet

</h4>

<p class="text-muted">

Complete all lessons and successfully pass your quizzes to receive your certificate.

</p>

<a
href="../learning/index.php"
class="btn btn-primary rounded-pill">

Continue Learning

</a>

</div>

    <strong>No certificates found.</strong>

    Once your course has been completed and approved,
    your certificate will appear here.

</div>

<?php else: ?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-primary">

<tr>

<th>#</th>

<th>Certificate No</th>

<th>Course</th>

<th>Issue Date</th>

<th>Status</th>

<th width="240">Action</th>

</tr>

</thead>

<tbody>

<?php

$count = 1;

foreach ($certificates as $row):

?>

<tr>

    <td><?= $count++; ?></td>

    <td>

        <span class="fw-bold text-primary">
            <?= htmlspecialchars($row['certificate_number']); ?>
        </span>

    </td>

    <td>

        <?= htmlspecialchars($row['course_title']); ?>

    </td>

    <td>

        <?= date('d M, Y', strtotime($row['issue_date'])); ?>

    </td>

    
    <td>

        <?php if($row['status']=="Issued"): ?>

            <span class="badge bg-success">

                <i class="fas fa-check-circle"></i>

                Issued

            </span>

        <?php elseif($row['status']=="Revoked"): ?>

            <span class="badge bg-danger">

                Revoked

            </span>

        <?php else: ?>

            <span class="badge bg-secondary">

                <?= htmlspecialchars($row['status']); ?>

            </span>

        <?php endif; ?>

    </td>

    <td>

        <div class="btn-group btn-group-sm">

            <a href="download.php?id=<?= $row['id']; ?>"
               class="btn btn-outline-success rounded-pill">

                <i class="fas fa-download"></i>

            </a>

            <a href="../../verify.php?code=<?= urlencode($row['verification_code']); ?>"
               class="btn btn-primary">

                <i class="fas fa-shield-alt"></i>

            </a>

            

        </div>

    </td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

 <?php endif; ?>
                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>