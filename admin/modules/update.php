<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../courses/index.php");
    exit();

}

$id = (int) $_POST['id'];
$course_id = (int) $_POST['course_id'];

$module_title = trim($_POST['module_title']);
$description = trim($_POST['description']);
$module_order = (int) $_POST['module_order'];
$status = trim($_POST['status']);

if (empty($module_title)) {

    $_SESSION['error'] = "Module title is required.";

    header("Location: edit.php?id=".$id);
    exit();

}

/*
|--------------------------------------------------------------------------
| Prevent duplicate display order
|--------------------------------------------------------------------------
*/

$checkStmt = $pdo->prepare("
    SELECT id
    FROM course_modules
    WHERE course_id = ?
      AND module_order = ?
      AND id <> ?
");

$checkStmt->execute([
    $course_id,
    $module_order,
    $id
]);

if ($checkStmt->fetch()) {

    $_SESSION['error'] = "Another module already uses this display order.";

    header("Location: edit.php?id=".$id);
    exit();

}

/*
|--------------------------------------------------------------------------
| Update Module
|--------------------------------------------------------------------------
*/

$sql = "
UPDATE course_modules
SET
    module_title = ?,
    description = ?,
    module_order = ?,
    status = ?
WHERE id = ?
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $module_title,
    $description,
    $module_order,
    $status,
    $id
]);

$_SESSION['success'] = "Module updated successfully.";

header("Location: index.php?course_id=".$course_id);

exit();