<div class="content">

<div class="topbar d-flex justify-content-between align-items-center">

    <div>

        <button
        class="btn btn-primary mobile-toggle"
        id="menuToggle">

            <i class="fas fa-bars"></i>

        </button>

        <span class="ms-2 fw-bold">

            <?= htmlspecialchars($pageTitle ?? "Student Portal"); ?>

        </span>

    </div>

    <div class="dropdown">

        <button
        class="btn btn-light dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown">

            <i class="fas fa-user-circle"></i>

            <?= htmlspecialchars($student['fullname'] ?? 'Student'); ?>

        </button>

        <ul class="dropdown-menu dropdown-menu-end">

            <li>

                <a
                class="dropdown-item"
                href="/solvetechacademy.org/student/profile.php">

                    <i class="fas fa-user me-2"></i>

                    My Profile

                </a>

            </li>

            <li>

                <a
                class="dropdown-item"
                href="/solvetechacademy.org/student/certificates/index.php">

                    <i class="fas fa-award me-2"></i>

                    My Certificates

                </a>

            </li>

            <li><hr class="dropdown-divider"></li>

            <li>

                <a
                class="dropdown-item text-danger"
                href="/solvetechacademy.org/logout.php">

                    <i class="fas fa-sign-out-alt me-2"></i>

                    Logout

                </a>

            </li>

        </ul>

    </div>

</div>