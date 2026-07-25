<?php

require_once '../includes/auth.php';
require_once 'includes/functions.php';
// require_once '../config/database.php';

$pageTitle = "Student Management";

/*
|--------------------------------------------------------------------------
| Fetch Students (Latest Enrollment)
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
    s.id,
    s.student_id,
    s.fullname,
    s.email,
    s.phone,
    s.country,
    s.city,
    s.qualification,
    s.occupation,
    s.profile_photo,
    s.status,
    s.created_at,

    (
        SELECT c.course_title
        FROM registrations r
        INNER JOIN courses c
            ON c.id = r.course_id
        WHERE r.student_id = s.id
        ORDER BY r.id DESC
        LIMIT 1
    ) AS current_course,

    (
        SELECT payment_status
        FROM registrations r
        WHERE r.student_id = s.id
        ORDER BY r.id DESC
        LIMIT 1
    ) AS payment_status

FROM students s

ORDER BY s.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<link rel="stylesheet" href="<?= ADMIN_ASSETS; ?>/css/solvetech-admin.css">

<?php require_once '../includes/sidebar.php'; ?>

<?php require_once '../includes/topbar.php'; ?>



    <!-- ========================================= -->
    <!-- PAGE HEADER -->
    <!-- ========================================= -->

    <div class="st-card mb-4">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <h2 class="page-title mb-2">

                    <i class="bi bi-people-fill text-primary me-2"></i>

                    Student Management

                </h2>

                <p class="page-subtitle">

                    Manage student registrations, enrollments,
                    payments, certificates and academic activities.

                </p>

            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

                <a href="create.php" class="btn btn-primary">

                    <i class="bi bi-person-plus-fill me-2"></i>

                    Add Student

                </a>

                <a href="import.php" class="btn btn-light">

                    <i class="bi bi-upload me-2"></i>

                    Import

                </a>

                <a href="export.php" class="btn btn-light">

                    <i class="bi bi-download me-2"></i>

                    Export

                </a>

            </div>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- STATISTICS -->
    <!-- ========================================= -->

    <?php include 'includes/stats_cards.php'; ?>

    <!-- ========================================= -->
    <!-- FILTERS -->
    <!-- ========================================= -->

    <?php include 'includes/filters.php'; ?>

    <!-- ========================================= -->
    <!-- BULK ACTIONS -->
    <!-- ========================================= -->

    <?php include 'includes/bulk_toolbar.php'; ?>

    <!-- ========================================= -->
    <!-- VIEW SWITCH -->
    <!-- ========================================= -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h5 class="fw-bold mb-0">

            Registered Students

        </h5>

        <div class="btn-group">

            <button
                id="cardViewBtn"
                class="btn btn-primary">

                <i class="bi bi-grid-3x3-gap-fill me-2"></i>

                Card View

            </button>

            <button
                id="tableViewBtn"
                class="btn btn-outline-primary">

                <i class="bi bi-table me-2"></i>

                Table View

            </button>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- CARD VIEW -->
    <!-- ========================================= -->

    <div id="cardView">

        <div class="row g-4">

            <?php foreach($students as $student): ?>

                <?php include 'includes/student_card.php'; ?>

            <?php endforeach; ?>

        </div>

    </div>

    <!-- ========================================= -->
    <!-- TABLE VIEW -->
    <!-- ========================================= -->

    <div id="tableView" style="display:none;">

        <div class="st-card">

            <div class="table-responsive">

                <table
                    id="studentsTable"
                    class="table table-hover align-middle">

                    <thead>

                    <tr>

                        <th width="40">

                            <input
                                type="checkbox"
                                id="selectAllStudents">

                        </th>

                        <th>Student</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Course</th>

                        <th>Status</th>

                        <th>Payment</th>

                        <th width="160">

                            Actions

                        </th>

                    </tr>

                    </thead>

                    <tbody>
                                            <?php foreach($students as $student): ?>

                        <?php

                        $paymentStatus = $student['payment_status'] ?? 'Pending';

                        switch(strtolower($paymentStatus)){

                            case 'paid':
                                $paymentBadge = 'success';
                                break;

                            case 'partial':
                                $paymentBadge = 'warning';
                                break;

                            case 'failed':
                            case 'unpaid':
                                $paymentBadge = 'danger';
                                break;

                            default:
                                $paymentBadge = 'secondary';
                                break;

                        }

                        ?>

                        <tr>

                            <td>

                                <input
                                    type="checkbox"
                                    class="student-checkbox"
                                    value="<?= $student['id']; ?>">

                            </td>

                            <td>

                                <div class="d-flex align-items-center">

                                    <img
                                        src="<?= studentPhoto($student['profile_photo']); ?>"
                                        class="avatar me-3"
                                        alt="<?= e($student['fullname']); ?>">

                                    <div>

                                        <div class="fw-bold">

                                            <?= e($student['fullname']); ?>

                                        </div>

                                        <small class="text-muted">

                                            <?= e($student['student_id']); ?>

                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <?= e($student['email']); ?>

                            </td>

                            <td>

                                <?= e($student['phone']); ?>

                            </td>

                            <td>

                                <?php if(!empty($student['current_course'])): ?>

                                    <span class="badge badge-soft-primary">

                                        <?= e($student['current_course']); ?>

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">

                                        No Enrollment

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <span class="badge bg-<?= statusBadge($student['status']); ?>">
                                <?= e($student['status']); ?>
                            </span>

                            </td>

                            <td>

                                <span class="badge bg-<?= $paymentBadge; ?>">

                                    <?= ucfirst($paymentStatus); ?>

                                </span>

                            </td>

                            <td>

                                <?php include 'includes/quick_actions.php'; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- END TABLE VIEW -->
     </div>

<?php require_once '../includes/footer.php'; ?>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');

    const cardBtn = document.getElementById('cardViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');

    /*
    |--------------------------------------------------------------------------
    | View Toggle
    |--------------------------------------------------------------------------
    */

    cardBtn.addEventListener('click', function () {

        cardView.style.display = 'block';
        tableView.style.display = 'none';

        cardBtn.classList.remove('btn-outline-primary');
        cardBtn.classList.add('btn-primary');

        tableBtn.classList.remove('btn-primary');
        tableBtn.classList.add('btn-outline-primary');

    });

    tableBtn.addEventListener('click', function () {

        cardView.style.display = 'none';
        tableView.style.display = 'block';

        tableBtn.classList.remove('btn-outline-primary');
        tableBtn.classList.add('btn-primary');

        cardBtn.classList.remove('btn-primary');
        cardBtn.classList.add('btn-outline-primary');

    });

    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    
    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    const selectAll = document.getElementById('selectAllStudents');

    if(selectAll){

        selectAll.addEventListener('change', function(){

            document.querySelectorAll('.student-checkbox').forEach(function(box){

                box.checked = selectAll.checked;

            });

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Live Search (Card View)
    |--------------------------------------------------------------------------
    */

    const search = document.getElementById('studentSearch');

    if(search){

        search.addEventListener('keyup', function(){

            let value = this.value.toLowerCase();

            document.querySelectorAll('.student-card').forEach(function(card){

                card.style.display = card.innerText.toLowerCase().includes(value)
                    ? ''
                    : 'none';

            });

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

    const statusFilter = document.getElementById('statusFilter');

    if(statusFilter){

        statusFilter.addEventListener('change', function(){

            let value = this.value.toLowerCase();

            document.querySelectorAll('.student-card').forEach(function(card){

                if(value === ''){

                    card.style.display = '';

                    return;

                }

                let status = card.dataset.status ?? '';

                card.style.display =
                    status.toLowerCase() === value
                        ? ''
                        : 'none';

            });

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Country Filter
    |--------------------------------------------------------------------------
    */

    const countryFilter = document.getElementById('countryFilter');

    if(countryFilter){

        countryFilter.addEventListener('change', function(){

            let value = this.value.toLowerCase();

            document.querySelectorAll('.student-card').forEach(function(card){

                if(value === ''){

                    card.style.display='';

                    return;

                }

                let country = card.dataset.country ?? '';

                card.style.display =
                    country.toLowerCase() === value
                        ? ''
                        : 'none';

            });

        });

    }

});

</script>

</body>

</html>