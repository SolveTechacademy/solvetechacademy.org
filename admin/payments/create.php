<?php

require_once '../includes/auth.php';

$stmt = $pdo->query("
    SELECT
        r.id AS registration_id,
        s.student_id,
        s.fullname,
        c.course_title,
        c.course_fee,

        COALESCE(
            (
                SELECT SUM(amount)
                FROM payments
                WHERE registration_id = r.id
                AND status = 'Approved'
            ),
            0
        ) AS total_paid

    FROM registrations r

    INNER JOIN students s
        ON r.student_id = s.id

    INNER JOIN courses c
        ON r.course_id = c.id

    ORDER BY s.fullname ASC
");

$registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Record Payment";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';

$paymentID = "PAY-" . date("YmdHis");

?>

<div class="card shadow">

    <div class="card-header bg-success text-white">

        <h4 class="mb-0">
            <i class="fas fa-money-bill-wave"></i>
            Record New Payment
        </h4>

    </div>

    <div class="card-body">

        <form action="store.php" method="POST" enctype="multipart/form-data">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Payment ID</label>

                    <input
                        type="text"
                        class="form-control"
                        name="payment_id"
                        value="<?= $paymentID; ?>"
                        readonly>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Student Registration</label>

                    <select
                        id="registrationSelect"
                        name="registration_id"
                        class="form-select"
                        required>

                        <option value="">Select Student</option>

                        <?php foreach($registrations as $reg): ?>

                            <?php
                                $balance = $reg['course_fee'] - $reg['total_paid'];
                            ?>

                            <option
                                value="<?= $reg['registration_id']; ?>"
                                data-fee="<?= $reg['course_fee']; ?>"
                                data-paid="<?= $reg['total_paid']; ?>"
                                data-balance="<?= $balance; ?>">

                                <?= htmlspecialchars($reg['fullname']); ?>
                                (<?= htmlspecialchars($reg['student_id']); ?>)
                                -
                                <?= htmlspecialchars($reg['course_title']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <div class="alert alert-info">

                <h5 class="mb-3">

                    <i class="fas fa-wallet"></i>

                    Financial Summary

                </h5>

                <div class="row">

                    <div class="col-md-4">

                        <strong>Course Fee</strong><br>

                        <span id="courseFee" class="fs-5 text-primary">

                            0 FCFA

                        </span>

                    </div>

                    <div class="col-md-4">

                        <strong>Already Paid</strong><br>

                        <span id="totalPaid" class="fs-5 text-success">

                            0 FCFA

                        </span>

                    </div>

                    <div class="col-md-4">

                        <strong>Outstanding Balance</strong><br>

                        <span id="balance" class="fs-5 text-danger">

                            0 FCFA

                        </span>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Amount Paying

                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Payment Method

                    </label>

                    <select
                        name="payment_method"
                        class="form-select"
                        required>

                        <option value="">Select Method</option>
                        <option value="Cash">Cash</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Card">Card</option>

                    </select>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Transaction ID

                    </label>

                    <input
                        type="text"
                        name="transaction_id"
                        class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Payment Proof

                    </label>

                    <input
                        type="file"
                        name="payment_proof"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.pdf">

                </div>

            </div>

            <button class="btn btn-success">

                <i class="fas fa-save"></i>

                Save Payment

            </button>

            <a href="index.php" class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<script>

const registration = document.getElementById('registrationSelect');

registration.addEventListener('change', function(){

    const option = this.options[this.selectedIndex];

    const fee = parseFloat(option.dataset.fee || 0);

    const paid = parseFloat(option.dataset.paid || 0);

    const balance = parseFloat(option.dataset.balance || 0);

    document.getElementById('courseFee').innerHTML =
        fee.toLocaleString() + ' FCFA';

    document.getElementById('totalPaid').innerHTML =
        paid.toLocaleString() + ' FCFA';

    document.getElementById('balance').innerHTML =
        balance.toLocaleString() + ' FCFA';

});

</script>

<?php require_once '../includes/footer.php'; ?>