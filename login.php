<?php
session_start();

require_once 'config/database.php';
 

if(isset($_SESSION['student_login'])){
    header("Location: student/dashboard.php");
    exit();
}

$message = "";

if(isset($_GET['payment']) && $_GET['payment']=="success"){
    $message = "<div class='alert alert-success'>
                    Your payment has been submitted successfully.
                    Your account will be activated after verification.
                </div>";
}

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){

        $message = "<div class='alert alert-danger'>
                        Please enter your email and password.
                    </div>";

    }else{

        $stmt = $pdo->prepare("
        SELECT *
        FROM students
        WHERE email=?
        LIMIT 1
        ");

        $stmt->execute([$email]);

        if($stmt->rowCount()==1){

            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password,$student['password'])){

                if($student['status']=="Pending"){

                    $message = "<div class='alert alert-warning'>
                    Your account is awaiting admin approval.
                    </div>";

                }elseif($student['status']=="Suspended"){

                    $message = "<div class='alert alert-danger'>
                    Your account has been suspended.
                    </div>";

                }else{
                    session_regenerate_id(true);

                    $_SESSION['login_time'] = time();

                    $_SESSION['last_activity'] = time();

                    $_SESSION['student_login']=true;

                    $_SESSION['student_db_id']=$student['id'];

                    $_SESSION['student_id']=$student['student_id'];

                    $_SESSION['student_name']=$student['fullname'];

                    $_SESSION['student_email']=$student['email'];

                    if(isset($_POST['remember'])){
                        setcookie("remember_email", $student['email'], time()+(86400*30), "/", "", false, true);
                    }else{
                        setcookie("remember_email", "", time()-3600, "/");
                    }

                    header("Location: student/dashboard.php");

                    exit();

                }

            }else{

                $message="<div class='alert alert-danger'>
                Incorrect password.
                </div>";

            }

        }else{

            $message="<div class='alert alert-danger'>
            Account not found.
            </div>";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Student Login | SolveTech Academy</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
background:#F6F8FC;
font-family:Arial,Helvetica,sans-serif;
}

.input-group-text{
    background:#fff;
    border-right:none;
}

.form-control{
    border-left:none;
    height:52px;
}

.form-control:focus{
    box-shadow:none;
    border-color:#ced4da;
}

.input-group:focus-within{
    box-shadow:0 0 0 .2rem rgba(255,138,61,.15);
    border-radius:10px;
}

</style>

</head>

<body>
<div class="container-fluid">

<div class="row min-vh-100">

<div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center text-white"
style="background:linear-gradient(135deg,#1E2143,#FF8A3D);">

<div class="text-center px-5">

<img src="assets/images/logo.png"
style="height:90px;
background:#fff;
padding:12px;
border-radius:15px;">

<h6 class="display-4 fw-bold">

Learn.<br>

Build.<br>

Get Certified.

</h6>

<p class="lead mt-4">

Join SolveTech Academy and gain practical IT skills through
industry-focused training.

</p>

<div class="mt-5">

<div class="d-flex align-items-center mb-4">

<i class="fas fa-check-circle fa-lg me-3"></i>

<span>Hands-on Practical Training</span>

</div>

<div class="d-flex align-items-center mb-4">

<i class="fas fa-check-circle fa-lg me-3"></i>

<span>Professional Certification</span>

</div>

<div class="d-flex align-items-center mb-4">

<i class="fas fa-check-circle fa-lg me-3"></i>

<span>Career & Job Support</span>

</div>

<div class="d-flex align-items-center">

<i class="fas fa-check-circle fa-lg me-3"></i>

<span>Online & Onsite Learning</span>

</div>

</div>
</div>

</div>

<div class="col-lg-6 d-flex align-items-center justify-content-center">

<div style="width:100%;max-width:470px;padding:40px;">

<div class="text-center mb-5">

<img
src="assets/images/logo.png"
style="height:65px;">

<h2 class="fw-bold mt-3 text-dark">

Welcome Back 👋

</h2>

<p class="text-secondary mb-4">

Sign in to continue your learning journey.

</p>

<?= $message; ?>

</div>

<form method="POST">
<div class="mb-4">

<label class="form-label fw-semibold">

Email Address

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fas fa-envelope"></i>

</span>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($_COOKIE['remember_email'] ?? $_POST['email'] ?? '') ?>"
placeholder="Enter your email"
required>

</div>

</div>

<div class="mb-4">

<label class="form-label fw-semibold">

Password

</label>

<div class="input-group">

<span class="input-group-text">

<i class="fas fa-lock"></i>

</span>

<input
type="password"
id="password"
name="password"
class="form-control"
placeholder="Enter your password"
required>

<button
type="button"
class="btn btn-outline-secondary"
onclick="togglePassword()">

<i id="eyeIcon" class="fas fa-eye"></i>

</button>

</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-4">

<div class="form-check">

<input
class="form-check-input"
type="checkbox"
name="remember"
id="remember">

<label
class="form-check-label"
for="remember">

Remember Me

</label>

</div>

<a
href="forgot-password.php"
class="text-decoration-none">

Forgot Password?

</a>

</div>

<button
type="submit"
name="login"
class="btn w-100 py-3 fw-bold text-white"
style="background:#FF8A3D;border:none;border-radius:12px;">

Sign In

</button>
<div class="text-center mt-4">

<a
href="forgot-password.php"
class="text-decoration-none">

Forgot your password?

</a>

<hr class="my-4">

<p class="text-muted">

Don't have an account?

</p>

<a
href="courses.php"
class="btn btn-outline-primary w-100 py-3">

Create Student Account

</a>

<div class="text-center mt-5">

<small class="text-muted">

© <?= date('Y') ?> SolveTech Academy.

All Rights Reserved.

</small>

</div>

</div>

</form>
</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function togglePassword(){

const p=document.getElementById('password');
const e=document.getElementById('eyeIcon');

if(p.type==="password"){

p.type="text";
e.classList.replace("fa-eye","fa-eye-slash");

}else{

p.type="password";
e.classList.replace("fa-eye-slash","fa-eye");

}

}

</script>

</body>

</html>
