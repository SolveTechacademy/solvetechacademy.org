<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$page_title = "Certificates";

try {

    $stmt = $pdo->query("
        SELECT
            c.*,
            s.student_id,
            s.fullname,
            co.course_title
        FROM certificates c
        LEFT JOIN students s
            ON c.student_id = s.id
        LEFT JOIN courses co
            ON c.course_id = co.id
        ORDER BY c.id DESC
    ");

    $certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e){
    die("Error: ".$e->getMessage());
}


/*-----------------------------------------
| Dashboard Statistics
------------------------------------------*/

$totalCertificates = $pdo->query("
SELECT COUNT(*) FROM certificates
")->fetchColumn();


$issuedToday = $pdo->query("
SELECT COUNT(*)
FROM certificates
WHERE issue_date = CURDATE()
")->fetchColumn();


$issuedMonth = $pdo->query("
SELECT COUNT(*)
FROM certificates
WHERE MONTH(issue_date)=MONTH(CURDATE())
AND YEAR(issue_date)=YEAR(CURDATE())
")->fetchColumn();


$revoked = 0;

try{

$revoked = $pdo->query("
SELECT COUNT(*)
FROM certificates
WHERE status='Revoked'
")->fetchColumn();

}catch(Exception $e){
$revoked = 0;
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="content">

    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

               

                <p class="text-muted mb-0">
                    SolveTech Academy Learning Management System
                </p>

            </div>

            <div>

                <a href="create.php" class="btn btn-success">
                    <i class="fas fa-plus-circle"></i>
                    Generate Certificate
                </a>

            </div>

        </div>

        <!-- Statistics -->

        <h2 class="mb-4">Certificate Management</h2>

        <div class="row">

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-body">

                        <h5 class="text-muted">
                            Total Certificates
                        </h5>

                        <h1 class="fw-bold">
                            <?= $totalCertificates ?>
                        </h1>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-body">

                        <h5 class="text-muted">
                            Issued Today
                        </h5>

                        <h1 class="fw-bold text-success">
                            <?= $issuedToday ?>
                        </h1>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-body">

                        <h5 class="text-muted">
                            This Month
                        </h5>

                        <h1 class="fw-bold text-primary">
                            <?= $issuedMonth ?>
                        </h1>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6 mb-4">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-body">

                        <h5 class="text-muted">
                            Revoked
                        </h5>

                        <h1 class="fw-bold text-danger">
                            <?= $revoked ?>
                        </h1>

                    </div>

                </div>

            </div>

        </div>

        <!-- Table -->

        <div class="card shadow">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">

                    Issued Certificates

                </h4>

            </div>

            <div class="card-body">

                <table
                    id="dataTable"
                    class="table table-striped table-hover align-middle">

                    <thead>

                    <tr>

                        <th>#</th>
                        <th>Certificate No.</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th width="220">Actions</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    $sn = 1;

                    foreach($certificates as $certificate):

                    ?>

                    <tr>

                        <td><?= $sn++; ?></td>

                        <td>

                            <strong class="text-primary">

                                <?= htmlspecialchars($certificate['certificate_number']) ?>

                            </strong>

                        </td>

                        <td><?= htmlspecialchars($certificate['student_id']) ?></td>

                        <td><?= htmlspecialchars($certificate['fullname']) ?></td>

                        <td><?= htmlspecialchars($certificate['course_title']) ?></td>

                        <td><?= date('d M Y',strtotime($certificate['issue_date'])) ?></td>

                        <td>

                            <?php if($certificate['status']=="Issued"): ?>

                                <span class="badge bg-success">

                                    Issued

                                </span>

                            <?php elseif($certificate['status']=="Revoked"): ?>

                                <span class="badge bg-danger">

                                    Revoked

                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">

                                    <?= htmlspecialchars($certificate['status']) ?>

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="btn-group btn-group-sm">

                                <a href="view.php?id=<?= $certificate['id'] ?>" class="btn btn-primary">

                                    <i class="fas fa-eye"></i>

                                </a>

                                <a href="print.php?id=<?= $certificate['id'] ?>" target="_blank" class="btn btn-secondary">

                                    <i class="fas fa-print"></i>

                                </a>

                                <a href="pdf.php?id=<?= $certificate['id'] ?>" target="_blank" class="btn btn-danger">

                                    <i class="fas fa-file-pdf"></i>

                                </a>

                                <a href="edit.php?id=<?= $certificate['id'] ?>" class="btn btn-warning">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a href="delete.php?id=<?= $certificate['id'] ?>"
                                   class="btn btn-dark"
                                   onclick="return confirm('Delete this certificate?')">

                                    <i class="fas fa-trash"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

<script>

$(document).ready(function(){

    $('#dataTable').DataTable({

        responsive: true,

        pageLength: 10,

        ordering: true,

        autoWidth: false,

        language: {

            search: "Search Certificates:",

            lengthMenu: "Show _MENU_ records",

            info: "Showing _START_ to _END_ of _TOTAL_ certificates",

            paginate: {

                previous: "Previous",

                next: "Next"

            }

        }

    });

});

</script>