<?php
// Student Statistics

$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

$activeStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Active'")->fetchColumn();

$pendingStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Pending'")->fetchColumn();

$suspendedStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status='Suspended'")->fetchColumn();
?>

<div class="row g-4 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>

                <div class="ms-3">
                    <small class="text-muted">Total Students</small>
                    <h3 class="fw-bold mb-0"><?= number_format($totalStudents) ?></h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>

                <div class="ms-3">
                    <small class="text-muted">Active</small>
                    <h3 class="fw-bold mb-0"><?= number_format($activeStudents) ?></h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>

                <div class="ms-3">
                    <small class="text-muted">Pending</small>
                    <h3 class="fw-bold mb-0"><?= number_format($pendingStudents) ?></h3>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center">

                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:60px;height:60px;">
                    <i class="bi bi-slash-circle-fill fs-3"></i>
                </div>

                <div class="ms-3">
                    <small class="text-muted">Suspended</small>
                    <h3 class="fw-bold mb-0"><?= number_format($suspendedStudents) ?></h3>
                </div>

            </div>
        </div>
    </div>

</div>