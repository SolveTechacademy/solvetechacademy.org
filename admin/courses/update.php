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
if (
    empty($course_title) ||
    empty($category) ||
    empty($duration)
) {

    $_SESSION['error'] = "Please complete all required fields.";

    header("Location: edit.php?id=" . $id);

    exit();

}
$sql = "UPDATE courses SET

course_title = ?,
category = ?,
duration = ?,
level = ?,
price = ?,
instructor = ?

WHERE id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    $course_title,
    $category,
    $duration,
    $level,
    $price,
    $instructor,
    $id

]);
$_SESSION['success'] = "Course updated successfully.";

header("Location: index.php");

exit();