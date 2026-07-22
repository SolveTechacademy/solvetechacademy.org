<?php
session_start();

require_once 'config/database.php';

if (!isset($_GET['reg'])) {
    die("Invalid Registration.");
}

$registrationID = $_GET['reg'];

$stmt = $pdo->prepare("
SELECT

registrations.id,
registrations.registration_id,

students.student_id,
students.fullname,
students.email,

courses.course_title,
courses.price

FROM registrations

INNER JOIN students
ON registrations.student_id = students.id

INNER JOIN courses
ON registrations.course_id = courses.id

WHERE registrations.registration_id=?

LIMIT 1

");

$stmt->execute([$registrationID]);

$registration = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$registration){

    die("Registration not found.");

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Course Payment | SolveTech Academy</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

background:#f4f6f9;

}

.card{

margin-top:50px;

border-radius:12px;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Course Payment</h3>

</div>

<div class="card-body"></div>
<h5 class="mb-4">Registration Details</h5>

<table class="table table-bordered">

<tr>

<th>Student ID</th>

<td><?= $registration['student_id']; ?></td>

</tr>

<tr>

<th>Student Name</th>

<td><?= $registration['fullname']; ?></td>

</tr>

<tr>

<th>Email</th>

<td><?= $registration['email']; ?></td>

</tr>

<tr>

<th>Registration ID</th>

<td><?= $registration['registration_id']; ?></td>

</tr>

<tr>

<th>Course</th>

<td><?= $registration['course_title']; ?></td>

</tr>

<tr>

<th>Amount</th>

<td>

<strong>

<?= number_format($registration['price']); ?>

FCFA

</strong>

</td>

</tr>

</table>

<hr>

<h5>Payment Instructions</h5>

<div class="alert alert-info">

<strong>MTN Mobile Money</strong>

<br>

Number:

+237 654178586

<br><br>

<strong>Orange Money</strong>

<br>

Number:

+237 654178586

</div>

<?php

if(isset($_POST['submit_payment'])){

    $amount = trim($_POST['amount']);
    $payment_method = trim($_POST['payment_method']);
    $transaction_id = trim($_POST['transaction_id']);

    if(empty($amount) || empty($payment_method) || empty($transaction_id)){

        echo "<div class='alert alert-danger'>
                Please complete all payment details.
              </div>";

    }else{

        $allowed = ['jpg','jpeg','png','pdf'];

        $fileName = $_FILES['payment_proof']['name'];
        $tmpName = $_FILES['payment_proof']['tmp_name'];

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if(!in_array($extension,$allowed)){

            echo "<div class='alert alert-danger'>
                    Invalid file format.
                  </div>";

        }else{

            $newFileName = uniqid("PAY_").".".$extension;

            move_uploaded_file(
                $tmpName,
                "assets/uploads/payments/".$newFileName
            );

            $paymentID = "STA-PAY-".date("Y")."-".rand(100000,999999);

            $insert = $pdo->prepare("
            INSERT INTO payments
            (
                payment_id,
                registration_id,
                amount,
                payment_method,
                transaction_id,
                payment_proof,
                status,
                created_at
            )
            VALUES
            (?,?,?,?,?,?,?,NOW())
            ");

            $insert->execute([

                $paymentID,
                $registration['id'],
                $amount,
                $payment_method,
                $transaction_id,
                $newFileName,
                'Pending'

            ]);

            $update = $pdo->prepare("
            UPDATE registrations
            SET payment_status=?
            WHERE id=?
            ");

            $update->execute([
                'Pending',
                $registration['id']
            ]);

            echo "<div class='alert alert-success'>
                    Payment submitted successfully.
                    Your payment is awaiting verification.
                  </div>";

        }

    }

}

?>

<hr>

<h4 class="mb-3">Upload Payment Proof</h4>

<form action="payment_process.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="registration_id" value="<?= $registration['id']; ?>">

<input type="hidden" name="registration_number" value="<?= $registration['registration_id']; ?>">

<input type="hidden" name="student_name" value="<?= htmlspecialchars($registration['fullname']); ?>">

<input type="hidden" name="student_email" value="<?= htmlspecialchars($registration['email']); ?>">

<input type="hidden" name="student_id" value="<?= $registration['student_id']; ?>">

<input type="hidden" name="course" value="<?= htmlspecialchars($registration['course_title']); ?>">

<div class="mb-3">

<label class="form-label">

Amount Paid

</label>

<input
type="number"
name="amount"
class="form-control"
value="<?= $registration['price']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Payment Method

</label>

<select
name="payment_method"
class="form-select"
required>

<option value="">Select Payment Method</option>

<option>MTN Mobile Money</option>

<option>Orange Money</option>

<option>Bank Transfer</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

Transaction ID

</label>

<input
type="text"
name="transaction_id"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Payment Screenshot / Receipt

</label>

<input
type="file"
name="payment_proof"
class="form-control"
accept=".jpg,.jpeg,.png,.pdf"
required>

</div>

<button
type="submit"
name="submit_payment"
class="btn btn-success">

Submit Payment

</button>

</form>
</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>