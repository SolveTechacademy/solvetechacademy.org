<?php
session_start();

require_once '../config/auth.php';
require_once '../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../login.php");
    exit();
}

$student_db_id = $_SESSION['student_db_id'];

$stmt = $pdo->prepare("
SELECT *
FROM students
WHERE id=?
LIMIT 1
");

$stmt->execute([$student_db_id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$student){
    session_destroy();
    header("Location: ../login.php");
    exit();
}

$message="";
$error="";

if(isset($_POST['update_profile'])){

/*
|--------------------------------------------------------------------------
| Profile Photo Upload
|--------------------------------------------------------------------------
*/

$profilePhoto = $student['profile_photo'];

if(
    isset($_FILES['profile_photo']) &&
    $_FILES['profile_photo']['error']==0
){

    $allowed=['jpg','jpeg','png'];

    $extension=strtolower(
        pathinfo(
            $_FILES['profile_photo']['name'],
            PATHINFO_EXTENSION
        )
    );

    if(in_array($extension,$allowed)){

        $newName='student_'.
        time().
        '.'.$extension;

        move_uploaded_file(

            $_FILES['profile_photo']['tmp_name'],

            "../uploads/profile_photos/".$newName

        );

        $profilePhoto=$newName;

    }

}

    $phone=trim($_POST['phone']);
    $country=trim($_POST['country']);

$city=trim($_POST['city']);

$qualification=trim($_POST['qualification']);

$occupation=trim($_POST['occupation']);
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $update=$pdo->prepare("
 UPDATE students
SET

phone=?,
country=?,
city=?,
qualification=?,
occupation=?,
profile_photo=?

WHERE id=?
    ");

    if($update->execute([
$phone,
$country,
$city,
$qualification,
$occupation,
$profilePhoto,
$student_db_id
    ])){

        $message="Profile updated successfully.";

        $stmt->execute([$student_db_id]);
        $student=$stmt->fetch(PDO::FETCH_ASSOC);

    }else{

        $error="Unable to update profile.";

    }

    /*
|--------------------------------------------------------------------------
| Change Password
|--------------------------------------------------------------------------
*/

if(isset($_POST['change_password'])){

    $currentPassword = $_POST['current_password'];
    $newPassword     = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if(empty($currentPassword) || empty($newPassword) || empty($confirmPassword)){

        $error = "All password fields are required.";

    }elseif(!password_verify($currentPassword,$student['password'])){

        $error = "Current password is incorrect.";

    }elseif($newPassword != $confirmPassword){

        $error = "New passwords do not match.";

    }else{

        $hashed = password_hash($newPassword,PASSWORD_DEFAULT);

        $update = $pdo->prepare("
            UPDATE students
            SET password=?
            WHERE id=?
        ");

        $update->execute([
            $hashed,
            $student_db_id
        ]);

        $message = "Password changed successfully.";

    }

}

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>

My Profile

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
rel="stylesheet">

<style>

body{

background:#f4f7fb;

}

.card{

border:none;

border-radius:12px;

box-shadow:0 4px 15px rgba(0,0,0,.08);

}

.profile-avatar{

font-size:90px;

color:#0d6efd;

}

</style>

</head>

<body>

<div class="container py-4">
    <div class="row">

    <div class="col-lg-4 mb-4">

        <div class="card">

            <div class="card-body text-center">

                <i class="fas fa-user-circle profile-avatar"></i>

                <h4 class="mt-3">

                    <?= htmlspecialchars($student['fullname']); ?>

                </h4>

                <p class="text-muted">

                    <?= htmlspecialchars($student['student_id']); ?>

                </p>

                <hr>

                <table class="table table-borderless">

                    <tr>

                        <th>Email</th>

                    </tr>

                    <tr>

                        <td>

                            <?= htmlspecialchars($student['email']); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>Phone</th>

                    </tr>

                    <tr>

                        <td>

                            <?= htmlspecialchars($student['phone']); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>Status</th>

                    </tr>

                    <tr>

                        <td>

                            <span class="badge bg-success">

                                <?= htmlspecialchars($student['status']); ?>

                            </span>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>
    <div class="col-lg-8">

<div class="card">

<div class="card-header bg-primary text-white">

<h4>

Edit Profile

</h4>

</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?= $message ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?= $error ?>

</div>

<?php } ?>

<div class="card mt-4 shadow">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">

            🔒 Change Password

        </h5>

    </div>

    <div class="card-body">

        <form method="POST">

            <div class="mb-3">

                <label>

                    Current Password

                </label>

                <input
                type="password"
                name="current_password"
                class="form-control"
                required>

            </div>

            <div class="mb-3">

                <label>

                    New Password

                </label>

                <input
                type="password"
                name="new_password"
                class="form-control"
                required>

            </div>

            <div class="mb-3">

                <label>

                    Confirm Password

                </label>

                <input
                type="password"
                name="confirm_password"
                class="form-control"
                required>

            </div>

            <button
            type="submit"
            name="change_password"
            class="btn btn-dark">

                Change Password

            </button>

        </form>

    </div>

</div>

<?php

$courseCount = $pdo->prepare("
SELECT COUNT(*)
FROM registrations
WHERE student_id=?
AND approval_status='Approved'
");

$courseCount->execute([$student_db_id]);

$totalCourses = $courseCount->fetchColumn();

$certificateCount = $pdo->prepare("
SELECT COUNT(*)
FROM certificates
WHERE student_id=?
");

$certificateCount->execute([$student_db_id]);

$totalCertificates = $certificateCount->fetchColumn();

?>

<div class="card mt-4 shadow">

<div class="card-header bg-success text-white">

<h5>

📊 Student Statistics

</h5>

</div>

<div class="card-body">

<div class="row text-center">

<div class="col-md-6">

<h2 class="text-primary">

<?= $totalCourses ?>

</h2>

<p>Courses</p>

</div>

<div class="col-md-6">

<h2 class="text-success">

<?= $totalCertificates ?>

</h2>

<p>Certificates</p>

</div>

</div>

</div>

</div>
<br>

<form method="POST" enctype="multipart/form-data">

<div class="col-12 text-center mb-4">

<?php if(!empty($student['profile_photo'])){ ?>

<img
src="../uploads/profile_photos/<?= $student['profile_photo']; ?>"
style="
width:140px;
height:140px;
border-radius:50%;
object-fit:cover;
">

<?php }else{ ?>

<i
class="fas fa-user-circle"
style="
font-size:130px;
color:#0d6efd;
"></i>

<?php } ?>

<br><br>

<input
type="file"
name="profile_photo"
class="form-control">

</div>

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($student['fullname']); ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Student ID

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($student['student_id']); ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
class="form-control"
value="<?= htmlspecialchars($student['email']); ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Phone

</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($student['phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>

Country

</label>

<input
type="text"
name="country"
class="form-control"
value="<?= htmlspecialchars($student['country']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>

City

</label>

<input
type="text"
name="city"
class="form-control"
value="<?= htmlspecialchars($student['city']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>

Qualification

</label>

<input
type="text"
name="qualification"
class="form-control"
value="<?= htmlspecialchars($student['qualification']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>

Occupation

</label>

<input
type="text"
name="occupation"
class="form-control"
value="<?= htmlspecialchars($student['occupation']); ?>">

</div>

<div class="col-12">

<button
type="submit"
name="update_profile"
class="btn btn-primary">

<i class="fas fa-save"></i>

Update Profile

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>