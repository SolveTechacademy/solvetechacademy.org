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

<html>

<head>

<meta charset="UTF-8">

<title>Student Login</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card shadow mt-5">

<div class="card-header bg-primary text-white">

<h3 class="text-center">

Student Login

</h3>

</div>

<div class="card-body">

<?= $message; ?>

<form method="POST">

<div class="mb-3">

<label>Email Address</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($_COOKIE['remember_email'] ?? $_POST['email'] ?? '') ?>"
required
autocomplete="email">

</div>

<div class="mb-3">

<label>Password</label>

<div class="input-group">

    <input
    type="password"
    id="password"
    name="password"
    class="form-control"
    required
    autocomplete="current-password">

    <button
    type="button"
    class="btn btn-outline-secondary"
    onclick="togglePassword()">

        <i id="eyeIcon" class="fas fa-eye"></i>

    </button>

</div>
<div class="form-check mb-3">

    <input
    class="form-check-input"
    type="checkbox"
    name="remember"
    id="remember">

    <label class="form-check-label" for="remember">

        Remember Me

    </label>

</div>

</div>

<button
class="btn btn-primary w-100"
name="login">

Login

</button>

<div class="text-end mt-3">
<a href="forgot-password.php">Forgot Password?</a>
</div>

</form>

<br>

<p class="text-center">

Don't have an account?

<a href="courses.php">

Register Here

</a>

</p>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(){const p=document.getElementById('password');const e=document.getElementById('eyeIcon');if(p.type==='password'){p.type='text';e.classList.replace('fa-eye','fa-eye-slash');}else{p.type='password';e.classList.replace('fa-eye-slash','fa-eye');}}
</script>
</body>

</html>