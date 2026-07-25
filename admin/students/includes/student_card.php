<?php
$image = !empty($student['profile_photo'])
    ? "../../uploads/students/" . $student['profile_photo']
    : "../../assets/images/default-avatar.png";
?>

<div class="col-xl-4 col-lg-6 col-md-6 mb-4">

    <div class="card border-0 shadow-sm rounded-4 h-100 student-card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start">

                <img src="<?= $image ?>"
                     class="rounded-circle border"
                     width="80"
                     height="80"
                     style="object-fit:cover;">

                <span class="badge bg-<?=
                    $student['status']=='Active' ? 'success' :
                    ($student['status']=='Pending' ? 'warning text-dark' : 'danger')
                ?>">
                    <?= htmlspecialchars($student['status']) ?>
                </span>

            </div>

            <div class="mt-3">

                <h5 class="fw-bold mb-1">
                    <?= htmlspecialchars($student['fullname']) ?>
                </h5>

                <small class="text-muted">
                    <?= htmlspecialchars($student['student_id']) ?>
                </small>

            </div>

            <hr>

            <div class="small">

                <p class="mb-2">
                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                    <?= htmlspecialchars($student['email']) ?>
                </p>

                <p class="mb-2">
                    <i class="bi bi-telephone-fill text-success me-2"></i>
                    <?= htmlspecialchars($student['phone']) ?>
                </p>

                <p class="mb-2">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                    <?= htmlspecialchars($student['country']) ?>
                </p>

                <p class="mb-2">
                    <i class="bi bi-mortarboard-fill text-warning me-2"></i>
                    <?= htmlspecialchars($student['qualification']) ?>
                </p>

                <p class="mb-3">
                    <i class="bi bi-briefcase-fill text-info me-2"></i>
                    <?= htmlspecialchars($student['occupation']) ?>
                </p>

            </div>

            <label class="small fw-semibold mb-1">
                Profile Completion
            </label>

            <div class="progress mb-3" style="height:8px;">

                <?php

                $completed = 0;

                $fields = [
                    'fullname',
                    'email',
                    'phone',
                    'country',
                    'city',
                    'qualification',
                    'occupation',
                    'profile_photo'
                ];

                foreach($fields as $field){
                    if(!empty($student[$field])){
                        $completed++;
                    }
                }

                $percentage = round(($completed / count($fields)) * 100);

                ?>

                <div class="progress-bar bg-success"
                     style="width:<?= $percentage ?>%"></div>

            </div>

            <small class="text-muted">
                <?= $percentage ?>% Complete
            </small>

        </div>

        <div class="card-footer bg-white border-0">

            <div class="d-grid gap-2">

                <a href="view.php?id=<?= $student['id'] ?>"
                   class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye"></i>
                    View
                </a>

                <div class="btn-group">

                    <a href="edit.php?id=<?= $student['id'] ?>"
                       class="btn btn-outline-warning btn-sm">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <a href="profile.php?id=<?= $student['id'] ?>"
                       class="btn btn-outline-success btn-sm">
                        <i class="bi bi-person-circle"></i>
                    </a>

                    <a href="payments.php?id=<?= $student['id'] ?>"
                       class="btn btn-outline-info btn-sm">
                        <i class="bi bi-credit-card"></i>
                    </a>

                    <a href="certificates.php?id=<?= $student['id'] ?>"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-award"></i>
                    </a>

                    <a href="delete.php?id=<?= $student['id'] ?>"
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Delete this student?')">
                        <i class="bi bi-trash"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>