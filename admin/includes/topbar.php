<div class="content">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

<div class="container-fluid">

<div>

<h4 class="mb-0">

<?= isset($pageTitle) ? $pageTitle : "Dashboard"; ?>

</h4>

<small class="text-muted">

SolveTech Academy Learning Management System

</small>

</div>

<div class="d-flex align-items-center">

<span class="me-4">

<i class="fas fa-user-circle"></i>

Welcome,

<strong><?= htmlspecialchars($_SESSION['admin_name']); ?></strong>

</span>

<div class="dropdown">

<button
class="btn btn-outline-primary dropdown-toggle"
data-bs-toggle="dropdown">

Account

</button>

<ul class="dropdown-menu dropdown-menu-end">

<li>

<a class="dropdown-item" href="#">

My Profile

</a>

</li>

<li>

<a class="dropdown-item" href="../logout.php">

Logout

</a>

</li>

</ul>

</div>

</div>

</div>

</nav>

<div class="container-fluid mt-4"></div>