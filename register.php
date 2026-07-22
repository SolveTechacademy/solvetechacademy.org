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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration | SolveTech Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f5f7fb;
        }

        .register-card{
            max-width:900px;
            margin:60px auto;
            border:none;
            border-radius:15px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        .card-header{
            background:#0d6efd;
            color:#fff;
            padding:25px;
            text-align:center;
        }

        .card-header h2{
            margin:0;
        }

        .required{
            color:red;
        }

    </style>

</head>

<body>

<div class="container">

<div class="card register-card">

<div class="card-header">

<h2>SolveTech Academy</h2>

<p class="mb-0">Student Registration Form</p>

</div>

<div class="card-body">

<?php if($course): ?>

<div class="alert alert-info">

<strong>Selected Course:</strong>

<?= htmlspecialchars($course['course_title']); ?>

<br>

<strong>Duration:</strong>

<?= htmlspecialchars($course['duration']); ?>

<br>

<strong>Fee:</strong>

<?= number_format($course['price']); ?> FCFA

</div>

<?php endif; ?>

<form action="register_process.php" method="POST">

<input
type="hidden"
name="course"
value="<?= $course ? $course['id'] : ''; ?>">
<div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Full Name <span class="required">*</span>
        </label>

        <input
        type="text"
        name="fullname"
        class="form-control"
        required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Email Address <span class="required">*</span>
        </label>

        <input
        type="email"
        name="email"
        class="form-control"
        required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Phone Number <span class="required">*</span>
        </label>

        <input
        type="text"
        name="phone"
        class="form-control"
        required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Country <span class="required">*</span>
        </label>

        <input
        type="text"
        name="country"
        class="form-control"
        required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
            City <span class="required">*</span>
        </label>

        <input
        type="text"
        name="city"
        class="form-control"
        required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">
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

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Occupation
        </label>

        <input
        type="text"
        name="occupation"
        class="form-control"
        required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Training Mode
        </label>

        <select
        name="mode"
        class="form-select"
        required>

            <option value="">Select Mode</option>

            <option value="Online">Online</option>

            <option value="Onsite">Onsite</option>

        </select>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Password
        </label>

        <input
        type="password"
        name="password"
        class="form-control"
        minlength="8"
        required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            Confirm Password
        </label>

        <input
        type="password"
        name="confirm_password"
        class="form-control"
        minlength="8"
        required>

    </div>

</div>

<div class="form-check mt-3">

    <input
    class="form-check-input"
    type="checkbox"
    required>

    <label class="form-check-label">

        I agree to the SolveTech Academy
        Terms & Conditions.

    </label>

</div>

<div class="d-grid mt-4">

    <button
    type="submit"
    class="btn btn-primary btn-lg">

        Register Now

    </button>

</div>
</form>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>