<div class="dropdown">
    <button class="btn btn-primary btn-sm dropdown-toggle w-100"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">
        <i class="bi bi-lightning-charge-fill"></i> Quick Actions
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow">

        <li>
            <a class="dropdown-item"
               href="view.php?id=<?= $student['id'] ?>">
                <i class="bi bi-eye me-2 text-primary"></i>
                View Student
            </a>
        </li>

        <li>
            <a class="dropdown-item"
               href="edit.php?id=<?= $student['id'] ?>">
                <i class="bi bi-pencil-square me-2 text-warning"></i>
                Edit Student
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <?php if (($student['status'] ?? '') != 'approved') : ?>

        <li>
            <a class="dropdown-item text-success"
               href="approve.php?id=<?= $student['id'] ?>"
               onclick="return confirm('Approve this student?');">
                <i class="bi bi-check-circle me-2"></i>
                Approve Student
            </a>
        </li>

        <?php endif; ?>

        <?php if (($student['status'] ?? '') == 'approved') : ?>

        <li>
            <a class="dropdown-item text-warning"
               href="suspend.php?id=<?= $student['id'] ?>"
               onclick="return confirm('Suspend this student?');">
                <i class="bi bi-pause-circle me-2"></i>
                Suspend Student
            </a>
        </li>

        <?php endif; ?>

        <li><hr class="dropdown-divider"></li>

        <li>
            <a class="dropdown-item text-danger"
               href="delete.php?id=<?= $student['id'] ?>"
               onclick="return confirm('Delete this student permanently?');">
                <i class="bi bi-trash me-2"></i>
                Delete Student
            </a>
        </li>

    </ul>
</div>