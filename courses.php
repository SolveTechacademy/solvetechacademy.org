<?php

require_once 'config/database.php';

$stmt = $pdo->prepare("SELECT * FROM courses WHERE status='Active' ORDER BY id DESC");
$stmt->execute();

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Courses | SolveTech Academy</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,Helvetica,sans-serif;
}

body{
background:#F6F8FC;
color:#222;
}

:root{

--primary:#1E2143;
--orange:#FF8A3D;

}

.navbar{

background:#fff;
box-shadow:0 3px 15px rgba(0,0,0,.06);

}

.navbar-brand img{

height:55px;

}

.hero{

background:linear-gradient(135deg,#1E2143,#FF8A3D);

padding:90px 0;

color:white;

}

.hero h1{

font-size:55px;

font-weight:700;

}

.hero p{

font-size:20px;

opacity:.95;

margin-top:20px;

}

.section-title{

font-size:38px;

font-weight:700;

color:var(--primary);

}

.course-card{

background:white;

border:none;

border-radius:20px;

overflow:hidden;

transition:.35s;

box-shadow:0 10px 30px rgba(0,0,0,.08);

height:100%;

}

.course-card:hover{

transform:translateY(-10px);

}

.course-image{

height:220px;

width:100%;

object-fit:cover;

}

.badge-price{

background:var(--orange);

font-size:15px;

padding:10px 15px;

}

.btn-orange{

background:var(--orange);

color:white;

border:none;

}

.btn-orange:hover{

background:#ef7d30;

color:white;

}

.footer{

background:var(--primary);

color:white;

padding:60px 0;

margin-top:80px;

}

.footer a{

color:white;

text-decoration:none;

}

@media(max-width:992px){

.hero{

padding:60px 20px;

text-align:center;

}

.hero h1{

font-size:38px;

}

}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="index.php">

<img src="assets/images/logo.png">

</a>

<button
class="navbar-toggler"
data-bs-toggle="collapse"
data-bs-target="#nav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="nav">

<ul class="navbar-nav ms-auto">

<li class="nav-item">

<a class="nav-link" href="index.php">

Home

</a>

</li>

<li class="nav-item">

<a class="nav-link active" href="courses.php">

Courses

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="login.php">

Student Login

</a>

</li>

</ul>

</div>

</div>

</nav>

<section class="hero">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-7">

<h1>

Upgrade Your Career With Practical Tech Skills

</h1>

<p>

Choose from our industry-focused professional courses
and start learning today.

</p>

<a
href="#courses"
class="btn btn-light btn-lg mt-4">

Browse Courses

</a>

</div>

<div class="col-lg-5 text-center">

<i
class="fas fa-laptop-code"
style="font-size:180px;opacity:.9;">

</i>

</div>

</div>

</div>

</section>

<section
class="py-5"
id="courses">

<div class="container">

<div class="text-center mb-5">

<h2 class="section-title">

Available Courses

</h2>

<p class="text-muted">

Professional training designed for the modern technology industry.

</p>

</div>

<div class="row g-4">
<?php foreach($courses as $course): ?>

<div class="col-lg-4 col-md-6">

<div class="course-card">

<img
src="<?= !empty($course['thumbnail'])
    ? 'assets/uploads/courses/' . $course['thumbnail']
    : 'assets/images/course-default.jpg'; ?>"
class="course-image"
alt="<?= htmlspecialchars($course['course_title']); ?>">

<div class="p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<span class="badge bg-primary">

<?= htmlspecialchars($course['level']); ?>

</span>

<span class="badge badge-price">

<?= number_format($course['course_fee']); ?> FCFA

</span>

</div>

<h4 class="fw-bold mb-3">

<?= htmlspecialchars($course['course_title']); ?>

</h4>

<p class="text-muted">

<?= strlen(strip_tags($course['description'])) > 120
? substr(strip_tags($course['description']),0,120).'...'
: strip_tags($course['description']); ?>

</p>

<hr>

<div class="row text-center">

<div class="col-6">

<small class="text-muted">

Duration

</small>

<h6 class="fw-bold">

<?= htmlspecialchars($course['duration']); ?>

</h6>

</div>

<div class="col-6">

<small class="text-muted">

Mode

</small>

<h6 class="fw-bold">

<?= htmlspecialchars($course['mode']); ?>

</h6>

</div>

</div>

<div class="d-grid gap-2 mt-4">

<a
href="course-details.php?id=<?= $course['id']; ?>"
class="btn btn-outline-dark">

View Details

</a>

<a
href="register.php?course=<?= $course['id']; ?>"
class="btn btn-orange">

Enroll Now

</a>

</div>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

</section>
<footer class="footer">

<div class="container">

<div class="row">

<div class="col-lg-4 mb-4">

<img
src="assets/images/logo.png"
style="height:65px;
background:white;
padding:10px;
border-radius:12px;">

<p class="mt-4">

SolveTech Academy is committed to empowering future technology professionals through practical, industry-focused IT education.

</p>

</div>

<div class="col-lg-4 mb-4">

<h4 class="mb-4">

Quick Links

</h4>

<ul class="list-unstyled">

<li class="mb-2">

<a href="index.php">

Home

</a>

</li>

<li class="mb-2">

<a href="courses.php">

Courses

</a>

</li>

<li class="mb-2">

<a href="login.php">

Student Login

</a>

</li>

<li class="mb-2">

<a href="contact.php">

Contact

</a>

</li>

</ul>

</div>

<div class="col-lg-4">

<h4 class="mb-4">

Contact

</h4>

<p>

<i class="fas fa-envelope me-2"></i>

info@solvetechacademy.org

</p>

<p>

<i class="fas fa-phone me-2"></i>

+237 XXX XXX XXX

</p>

<p>

<i class="fas fa-location-dot me-2"></i>

Buea, Cameroon

</p>

<div class="mt-4">

<a
href="#"
class="text-white me-3">

<i class="fab fa-facebook fa-lg"></i>

</a>

<a
href="#"
class="text-white me-3">

<i class="fab fa-linkedin fa-lg"></i>

</a>

<a
href="#"
class="text-white me-3">

<i class="fab fa-youtube fa-lg"></i>

</a>

<a
href="#"
class="text-white">

<i class="fab fa-whatsapp fa-lg"></i>

</a>

</div>

</div>

</div>

<hr class="border-light mt-5">

<div class="text-center">

© <?= date('Y'); ?>

<strong>

SolveTech Academy

</strong>

All Rights Reserved.

</div>

</div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>