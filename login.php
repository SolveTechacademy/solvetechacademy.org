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

                    $_SESSION['student_login']=true;

                    $_SESSION['student_db_id']=$student['id'];

                    $_SESSION['student_id']=$student['student_id'];

                    $_SESSION['student_name']=$student['fullname'];

                    $_SESSION['student_email']=$student['email'];

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
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-primary w-100"
name="login">

Login

</button>

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

</body>

</html>