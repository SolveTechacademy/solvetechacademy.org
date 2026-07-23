<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}
$id           = (int) $_POST['id'];
$course_title = trim($_POST['course_title']);
$category     = trim($_POST['category']);
$duration     = trim($_POST['duration']);
$level        = trim($_POST['level']);
$price        = trim($_POST['price']);
$instructor   = trim($_POST['instructor']);
$mode                  = trim($_POST['mode']);
$status                = trim($_POST['status']);
$short_description     = trim($_POST['short_description']);
$full_description      = trim($_POST['full_description']);
$learning_outcomes     = trim($_POST['learning_outcomes']);
$career_opportunities  = trim($_POST['career_opportunities']);
$prerequisites         = trim($_POST['prerequisites']);
$target_audience       = trim($_POST['target_audience']);
$certificate_info      = trim($_POST['certificate_info']);
$demo_video            = trim($_POST['demo_video']);
if (
    empty($course_title) ||
    empty($category) ||
    empty($duration)
) {

    $_SESSION['error'] = "Please complete all required fields.";

    header("Location: edit.php?id=" . $id);

    exit();

}
// Get current thumbnail
$stmt = $pdo->prepare("SELECT thumbnail FROM courses WHERE id=?");
$stmt->execute([$id]);
$currentCourse = $stmt->fetch(PDO::FETCH_ASSOC);

$thumbnail = $currentCourse['thumbnail'];

// Upload new thumbnail if selected
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
$sql = "UPDATE courses SET

course_title = ?,
category = ?,
duration = ?,
level = ?,
price = ?,
instructor = ?,
mode = ?,
status = ?,
short_description = ?,
full_description = ?,
learning_outcomes = ?,
career_opportunities = ?,
prerequisites = ?,
target_audience = ?,
certificate_info = ?,
demo_video = ?,
thumbnail = ?

WHERE id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    $course_title,
    $category,
    $duration,
    $level,
    $price,
    $instructor,
    $mode,
    $status,
    $short_description,
    $full_description,
    $learning_outcomes,
    $career_opportunities,
    $prerequisites,
    $target_audience,
    $certificate_info,
    $demo_video,
    $thumbnail,
    $id

]);
$_SESSION['success'] = "Course updated successfully.";

header("Location: index.php");

exit();