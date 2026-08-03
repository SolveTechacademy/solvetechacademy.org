<?php

$currentPage = basename($_SERVER['PHP_SELF']);

function activeMenu($pages)
{
    global $currentPage;

    return in_array($currentPage, $pages) ? 'active' : '';
}

?>

<div class="sidebar">

    <div class="sidebar-brand">

        <img
        src="../../assets/images/logo.png"
        alt="SolveTech Academy">

        <h5>

            SolveTech Academy

        </h5>

        <small class="text-muted">

            Student Portal

        </small>

    </div>

    <div class="p-3 text-center border-bottom">

        <i class="fas fa-user-circle fa-3x text-primary"></i>

        <div class="mt-2 fw-bold">

            <?= htmlspecialchars($_SESSION['student_name'] ?? 'Student'); ?>

        </div>

    </div>

    <nav class="nav flex-column mt-3">

        <a
        href="/student/dashboard.php"
        class="nav-link <?= activeMenu(['dashboard.php']) ?>">

            <i class="fas fa-home me-2"></i>

            Dashboard

        </a>

        <a
        href="/student/learning/index.php"
        class="nav-link <?= activeMenu(['index.php','course.php','module.php','lesson.php','progress.php']) ?>">

            <i class="fas fa-graduation-cap me-2"></i>

            Learning Center

        </a>

        <a
        href="/student/quizzes/index.php"
        class="nav-link <?= activeMenu(['index.php','take.php','review.php','result.php']) ?>">

            <i class="fas fa-file-alt me-2"></i>

            My Quizzes

        </a>

        <a
        href="/student/certificates/index.php"
        class="nav-link <?= activeMenu(['certificate.php','download.php']) ?>">

            <i class="fas fa-award me-2"></i>

            Certificates

        </a>

        <a
        href="/student/profile.php"
        class="nav-link <?= activeMenu(['profile.php']) ?>">

            <i class="fas fa-user me-2"></i>

            My Profile

        </a>

        <hr>

        <a
        href="/logout.php"
        class="nav-link text-danger">

            <i class="fas fa-sign-out-alt me-2"></i>

            Logout

        </a>

    </nav>

</div>