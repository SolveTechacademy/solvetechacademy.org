<?php
session_start();

require_once '../config/database.php';

if(isset($_SESSION['admin_login'])){
    header("Location: dashboard.php");
    exit();
}

$message="";

if(isset($_POST['login'])){

    $email=trim($_POST['email']);
    $password=$_POST['password'];

    if(empty($email) || empty($password)){

        $message="<div class='alert alert-danger'>
        Please enter your login credentials.
        </div>";

    }else{

        $stmt=$pdo->prepare("
        SELECT *
        FROM admins
        WHERE email=?
        LIMIT 1
        ");

        $stmt->execute([$email]);

        if($stmt->rowCount()){

            $admin=$stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password,$admin['password'])){

                if($admin['status']!="Active"){

                    $message="<div class='alert alert-warning'>
                    Your account has been disabled.
                    </div>";

                }else{

                    $_SESSION['admin_login']=true;

                    $_SESSION['admin_id']=$admin['id'];

                    $_SESSION['admin_name']=$admin['fullname'];

                    $_SESSION['admin_email']=$admin['email'];

                    $_SESSION['admin_role']=$admin['role'];

                    header("Location: dashboard.php");

                    exit();

                }

            }else{

                $message="<div class='alert alert-danger'>
                Invalid password.
                </div>";

            }

        }else{

            $message="<div class='alert alert-danger'>
            Administrator account not found.
            </div>";

        }

    }

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Admin Login</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card shadow mt-5">

<div class="card-header bg-dark text-white">

<h3 class="text-center">

SolveTech Academy Admin

</h3>

</div>

<div class="card-body">

<?= $message ?>

<form method="POST">

<div class="mb-3">

<label>Email</label>

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
class="btn btn-dark w-100"
name="login">

Login

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>