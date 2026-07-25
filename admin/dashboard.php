<?php

require_once 'includes/auth.php';

// Total Students
$totalStudents = $pdo->query("
SELECT COUNT(*)
FROM students
")->fetchColumn();

// Total Courses
$totalCourses = $pdo->query("
SELECT COUNT(*)
FROM courses
")->fetchColumn();

// Pending Payments
$pendingPayments = $pdo->query("
SELECT COUNT(*)
FROM payments
WHERE status='Pending'
")->fetchColumn();

// Active Students
$activeStudents = $pdo->query("
SELECT COUNT(*)
FROM students
WHERE status='Active'
")->fetchColumn();
$pageTitle = "Dashboard";
require_once 'includes/header.php';

require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';

?>
<div class="row">

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="card shadow">

            <div class="card-body">

                <h6>Total Students</h6>

                <h2><?= $totalStudents; ?></h2>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="card shadow">

            <div class="card-body">

                <h6>Total Courses</h6>

                <h2><?= $totalCourses; ?></h2>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="card shadow">

            <div class="card-body">

                <h6>Pending Payments</h6>

                <h2><?= $pendingPayments; ?></h2>

            </div>

        </div>

    </div>
    <?php
$pendingRegistrations = $pdo->query("
SELECT COUNT(*)
FROM registrations
WHERE approval_status='Pending'
")->fetchColumn();
?>

<div class="col-lg-3 col-md-6 mb-4">

    <div class="card shadow border-warning">

        <div class="card-body">

            <h6>Pending Approvals</h6>

            <h2><?= $pendingRegistrations; ?></h2>

        </div>

    </div>

</div>

    <div class="col-lg-3 col-md-6 mb-4">

        <div class="card shadow">

            <div class="card-body">

                <h6>Active Students</h6>

                <h2><?= $activeStudents; ?></h2>

            </div>

        </div>

    </div>

</div>
<?php

require_once 'includes/footer.php';

