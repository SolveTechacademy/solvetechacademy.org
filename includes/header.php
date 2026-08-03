<?php

if (!isset($pageTitle)) {
    $pageTitle = "SolveTech Academy LMS";
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$studentName = $_SESSION['student_name'] ?? "Student";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title><?= htmlspecialchars($pageTitle); ?></title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
rel="stylesheet">

<style>

:root{

--primary:#1E2143;
--orange:#FF8A3D;
--green:#7AC143;
--bg:#F6F8FC;

}

body{

background:var(--bg);
font-family:Arial,Helvetica,sans-serif;

}

.navbar{

background:#fff;
padding:8px 0;
box-shadow:0 3px 15px rgba(0,0,0,.08);

}

.navbar-brand img{

height:55px;

}

.nav-link{

font-weight:600;
color:var(--primary)!important;
margin-left:12px;

}

.nav-link:hover{

color:var(--orange)!important;

}

.student-name{

font-weight:bold;
color:var(--primary);

}

.card{

border:none;
border-radius:18px;
box-shadow:0 10px 30px rgba(0,0,0,.08);

}

.btn-primary{

background:var(--orange);
border:none;

}

.btn-primary:hover{

background:#ef7d30;

}
/* Navigation */

.offcanvas{

width:280px;

}

.offcanvas .list-group-item{

border:none;
padding:15px 18px;
font-weight:600;
color:var(--primary);

}

.offcanvas .list-group-item:hover{

background:#f5f5f5;
color:var(--orange);

}

.navbar-toggler{

border:none;

}

.navbar-toggler:focus{

box-shadow:none;

}

/* Main Content */

.main-content{

padding-top:35px;
padding-bottom:40px;

}

/* Footer */

.footer{

background:var(--primary);
color:#fff;
margin-top:70px;
padding:45px 0;

}

.footer a{

color:#fff;
text-decoration:none;

}

.footer a:hover{

color:var(--orange);

}

.footer-title{

font-weight:bold;
margin-bottom:15px;

}

/* Mobile */

@media(max-width:991px){

.navbar-brand img{

height:45px;

}

.student-name{

display:none;

}

}

</style>

</head>

<body><nav class="navbar navbar-expand-lg sticky-top">

<div class="container">

<a class="navbar-brand" href="/solvetechacademy.org/student/dashboard.php">

<img
src="/solvetechacademy.org/assets/images/logo.png"
alt="SolveTech Academy">

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="offcanvas"
data-bs-target="#studentMenu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse">

<ul class="navbar-nav ms-auto align-items-center">

<li class="nav-item">

<a class="nav-link" href="/solvetechacademy.org/student/dashboard.php">

<i class="fas fa-home me-1"></i>

Dashboard

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="/solvetechacademy.org/student/learning/index.php">

<i class="fas fa-book-open me-1"></i>

Learning

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="/solvetechacademy.org/student/quizzes/index.php">

<i class="fas fa-file-circle-question me-1"></i>

Quizzes

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="/solvetechacademy.org/student/certificates/index.php">

<i class="fas fa-award me-1"></i>

Certificates

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="/solvetechacademy.org/student/profile.php">

<i class="fas fa-user me-1"></i>

Profile

</a>

</li>

<li class="nav-item ms-3">

<span class="student-name">

<i class="fas fa-user-circle"></i>

<?= htmlspecialchars($studentName); ?>

</span>

</li>

<li class="nav-item ms-3">

<a
href="/solvetechacademy.org/logout.php"
class="btn btn-danger btn-sm">

Logout

</a>

</li>

</ul>

</div>

</div>

</nav>
<div
class="offcanvas offcanvas-end"
tabindex="-1"
id="studentMenu">

<div class="offcanvas-header">

<img
src="/solvetechacademy.org/assets/images/logo.png"
style="height:45px;">

<button
type="button"
class="btn-close"
data-bs-dismiss="offcanvas">

</button>

</div>

<div class="offcanvas-body">

<div class="list-group">

<a
href="/solvetechacademy.org/student/dashboard.php"
class="list-group-item list-group-item-action">

🏠 Dashboard

</a>

<a
href="/solvetechacademy.org/student/learning/index.php"
class="list-group-item list-group-item-action">

📚 Learning

</a>

<a
href="/solvetechacademy.org/student/quizzes/index.php"
class="list-group-item list-group-item-action">

📝 Quizzes

</a>

<a
href="/solvetechacademy.org/student/certificates/index.php"
class="list-group-item list-group-item-action">

🏆 Certificates

</a>

<a
href="/solvetechacademy.org/student/profile.php"
class="list-group-item list-group-item-action">

👤 Profile

</a>

<a
href="/solvetechacademy.org/logout.php"
class="list-group-item list-group-item-action text-danger">

🚪 Logout

</a>

</div>

</div>

</div>

<div class="container py-4">