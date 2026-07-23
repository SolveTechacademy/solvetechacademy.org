<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../courses/index.php");

    exit();

}
$course_id = (int) $_POST['course_id'];

$module_title = trim($_POST['module_title']);

$description = trim($_POST['description']);

$module_order = (int) $_POST['module_order'];

$status = trim($_POST['status']);
if (empty($module_title)) {

    $_SESSION['error'] = "Module title is required.";

    header("Location: add.php?course_id=" . $course_id);

    exit();

}
// Check if the display order already exists for this course
$checkStmt = $pdo->prepare("
    SELECT id
    FROM course_modules
    WHERE course_id = ?
      AND module_order = ?
");

$checkStmt->execute([$course_id, $module_order]);

if ($checkStmt->fetch()) {

    $_SESSION['error'] = "Another module already uses this display order.";

    header("Location: add.php?course_id=" . $course_id);

    exit();

}
$sql = "INSERT INTO course_modules (

course_id,

module_title,

description,

module_order,

status

)

VALUES (

?,?,?,?,?

)";
$stmt = $pdo->prepare($sql);

$stmt->execute([

    $course_id,

    $module_title,

    $description,

    $module_order,

    $status

]);
$_SESSION['success'] = "Module created successfully.";

header("Location: index.php?course_id=" . $course_id);

exit();