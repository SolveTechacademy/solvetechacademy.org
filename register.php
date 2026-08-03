<?php
session_start();

require_once 'config/database.php';

$course = null;

if (isset($_GET['course'])) {

    $course_id = (int) $_GET['course'];

    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);

    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        die("Course not found.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Student Registration | SolveTech Academy</title>

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
}

.auth-wrapper{
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
padding:40px;
}

.auth-card{
width:1200px;
max-width:100%;
background:#fff;
border-radius:24px;
overflow:hidden;
box-shadow:0 20px 60px rgba(0,0,0,.10);
}

.left-side{
background:linear-gradient(135deg,#1E2143,#FF8A3D);
color:#fff;
padding:70px 60px;
height:100%;
}

.left-side h1{
font-size:46px;
font-weight:700;
margin-top:40px;
line-height:1.2;
}

.left-side p{
font-size:18px;
line-height:1.8;
margin-top:20px;
opacity:.95;
}

.feature{
display:flex;
align-items:center;
margin-top:28px;
font-size:17px;
}

.feature i{
margin-right:15px;
font-size:22px;
}

.right-side{
padding:60px;
background:#fff;
}

.logo{
height:70px;
}

.form-control,
.form-select{
height:55px;
border-radius:12px;
}

.form-control:focus,
.form-select:focus{
border-color:#FF8A3D;
box-shadow:0 0 0 .2rem rgba(255,138,61,.15);
}

.btn-register{
height:55px;
background:#FF8A3D;
border:none;
border-radius:12px;
font-weight:bold;
color:#fff;
}

.btn-register:hover{
background:#ef7d30;
color:#fff;
}

.course-box{
background:#FFF8F2;
border-left:5px solid #FF8A3D;
padding:20px;
border-radius:12px;
margin-bottom:30px;
}

@media(max-width:992px){

.left-side{
display:none;
}

.right-side{
padding:35px;
}

.auth-wrapper{
padding:15px;
}

}

</style>

</head>

<body>

<div class="auth-wrapper">

<div class="auth-card">

<div class="row g-0">

<div class="col-lg-5">

<div class="left-side">

<img
src="assets/images/logo.png"
style="height:85px;background:#fff;padding:12px;border-radius:15px;">

<h1>

Start Your Tech Journey

</h1>

<p>

Become part of Africa's next generation of Software Engineers,
Cloud Engineers, DevOps Professionals and AI Specialists.

</p>

<div class="feature">

<i class="fas fa-check-circle"></i>

<span>Hands-on Practical Training</span>

</div>

<div class="feature">

<i class="fas fa-check-circle"></i>

<span>International Standard Curriculum</span>

</div>

<div class="feature">

<i class="fas fa-check-circle"></i>

<span>Professional Certification</span>

</div>

<div class="feature">

<i class="fas fa-check-circle"></i>

<span>Career & Internship Support</span>

</div>

</div>

</div>

<div class="col-lg-7">

<div class="right-side">

<div class="text-center mb-5">

<img
src="assets/images/logo.png"
class="logo">

<h2 class="fw-bold mt-4">

Create Student Account

</h2>

<p class="text-muted">

Complete the form below to begin your learning journey.

</p>

</div>

<?php if($course): ?>

<div class="course-box">

<h5 class="mb-3">

Selected Course

</h5>

<strong><?= htmlspecialchars($course['course_title']); ?></strong>

<br>

Duration:
<?= htmlspecialchars($course['duration']); ?>

<br>

Fee:
<?= number_format($course['course_fee']); ?> FCFA

</div>

<?php endif; ?>

<form action="register_process.php" method="POST">

<input
type="hidden"
name="course"
value="<?= $course ? $course['id'] : ''; ?>">

<div class="row g-4">
<div class="col-md-6">

<label class="form-label fw-semibold">

Full Name <span class="text-danger">*</span>

</label>

<input
type="text"
name="fullname"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Email Address <span class="text-danger">*</span>

</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Phone Number <span class="text-danger">*</span>

</label>

<input
type="text"
name="phone"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Country <span class="text-danger">*</span>

</label>

<input
type="text"
name="country"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

City <span class="text-danger">*</span>

</label>

<input
type="text"
name="city"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Highest Qualification

</label>

<select
name="qualification"
class="form-select"
required>

<option value="">Select Qualification</option>

<option>O Level</option>

<option>A Level</option>

<option>HND</option>

<option>BSc</option>

<option>BEng</option>

<option>MSc</option>

<option>PhD</option>

</select>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Occupation

</label>

<input
type="text"
name="occupation"
class="form-control"
required>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Training Mode

</label>

<select
name="mode"
class="form-select"
required>

<option value="">Select Training Mode</option>

<option value="Online">

Online

</option>

<option value="Onsite">

Onsite

</option>

</select>

</div>
<div class="col-md-6">

<label class="form-label fw-semibold">

Password <span class="text-danger">*</span>

</label>

<div class="input-group">

<input
type="password"
name="password"
id="password"
class="form-control"
minlength="8"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('password','eye1')">

<i id="eye1" class="fas fa-eye"></i>

</button>

</div>

<small class="text-muted">

Minimum 8 characters.

</small>

</div>

<div class="col-md-6">

<label class="form-label fw-semibold">

Confirm Password <span class="text-danger">*</span>

</label>

<div class="input-group">

<input
type="password"
name="confirm_password"
id="confirm_password"
class="form-control"
minlength="8"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword('confirm_password','eye2')">

<i id="eye2" class="fas fa-eye"></i>

</button>

</div>

</div>

</div>

<div class="form-check mt-4">

<input
class="form-check-input"
type="checkbox"
required
id="agree">

<label
class="form-check-label"
for="agree">

I agree to the
<strong>SolveTech Academy Terms & Conditions</strong>.

</label>

</div>

<div class="d-grid mt-4">

<button
type="submit"
class="btn btn-register">

<i class="fas fa-user-plus me-2"></i>

Create Student Account

</button>

</div>

<div class="text-center mt-4">

<p class="text-muted">

Already have an account?

</p>

<a
href="login.php"
class="btn btn-outline-primary w-100">

Sign In

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function togglePassword(inputId, iconId){

const input=document.getElementById(inputId);
const icon=document.getElementById(iconId);

if(input.type==="password"){

input.type="text";
icon.classList.replace("fa-eye","fa-eye-slash");

}else{

input.type="password";
icon.classList.replace("fa-eye-slash","fa-eye");

}

}

</script>

</body>

</html>

</html>