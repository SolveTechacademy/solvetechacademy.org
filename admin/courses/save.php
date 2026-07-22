<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add.php");
    exit();
}
$course_code  = trim($_POST['course_code']);
$course_title = trim($_POST['course_title']);
$category     = trim($_POST['category']);
$description  = trim($_POST['description']);
$duration     = trim($_POST['duration']);
$level        = trim($_POST['level']);
$price        = trim($_POST['price']);
$instructor   = trim($_POST['instructor']);
$mode         = trim($_POST['mode']);
$status       = trim($_POST['status']);
if (
    empty($course_title) ||
    empty($category) ||
    empty($duration)
) {

    session_start();

$_SESSION['error'] = "Please complete all required fields.";

header("Location: add.php");

exit();

}
$thumbnail = "";

if(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0){

    $extension = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png','webp'];

    if(in_array($extension,$allowed)){

        $filename = uniqid("course_") . "." . $extension;

        move_uploaded_file(

            $_FILES['thumbnail']['tmp_name'],

            "../../assets/uploads/courses/" . $filename

        );

        $thumbnail = $filename;

    }

}
$sql = "INSERT INTO courses(

course_code,
course_title,
category,
description,
duration,
level,
price,
instructor,
mode,
thumbnail,
status

)

VALUES(

?,?,?,?,?,?,?,?,?,?,?

)";

$stmt = $pdo->prepare($sql);

$stmt->execute([

$course_code,
$course_title,
$category,
$description,
$duration,
$level,
$price,
$instructor,
$mode,
$thumbnail,
$status

]);
$_SESSION['success'] = "Course created successfully.";

header("Location: index.php");

exit();

exit();