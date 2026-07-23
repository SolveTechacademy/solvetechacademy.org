<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../courses/index.php");

    exit();

}
$module_id    = (int) $_POST['module_id'];

$lesson_title = trim($_POST['lesson_title']);

$lesson_type  = trim($_POST['lesson_type']);

$description  = trim($_POST['description']);

$video_url    = trim($_POST['video_url']);

$duration     = trim($_POST['duration']);

$lesson_order = (int) $_POST['lesson_order'];

$is_preview   = (int) $_POST['is_preview'];

$status       = trim($_POST['status']);
if (empty($lesson_title)) {

    $_SESSION['error'] = "Lesson title is required.";

    header("Location: add.php?module_id=" . $module_id);

    exit();
}
// Prevent duplicate lesson order in the same module
$checkStmt = $pdo->prepare("
    SELECT id
    FROM lessons
    WHERE module_id = ?
      AND lesson_order = ?
");

$checkStmt->execute([$module_id, $lesson_order]);

if ($checkStmt->fetch()) {

    $_SESSION['error'] = "Another lesson already uses this display order.";

    header("Location: add.php?module_id=" . $module_id);

    exit();

}
    $file_path = "";

if (isset($_FILES['lesson_file']) && $_FILES['lesson_file']['error'] == 0) {

    $extension = strtolower(pathinfo($_FILES['lesson_file']['name'], PATHINFO_EXTENSION));

    switch ($lesson_type) {

        case "Video":
            $folder = "../../assets/uploads/lessons/videos/";
            $allowed = ['mp4','mov','avi','mkv'];
            break;

        case "PDF":
            $folder = "../../assets/uploads/lessons/pdfs/";
            $allowed = ['pdf'];
            break;

        case "Document":
            $folder = "../../assets/uploads/lessons/documents/";
            $allowed = ['doc','docx','ppt','pptx','xls','xlsx','zip'];
            break;

        default:
            $folder = "";
            $allowed = [];
    }

    if (!empty($folder) && in_array($extension, $allowed)) {

        $filename = uniqid("lesson_") . "." . $extension;

        move_uploaded_file(
            $_FILES['lesson_file']['tmp_name'],
            $folder . $filename
        );

        $file_path = $filename;

    }

}
$sql = "INSERT INTO lessons (

module_id,
lesson_title,
lesson_type,
video_url,
duration,
file_path,
description,
lesson_order,
is_preview,
status

)

VALUES (

?,?,?,?,?,?,?,?,?,?

)";
$stmt = $pdo->prepare($sql);

$stmt->execute([

    $module_id,
    $lesson_title,
    $lesson_type,
    $video_url,
    $duration,
    $file_path,
    $description,
    $lesson_order,
    $is_preview,
    $status

]);
$_SESSION['success'] = "Lesson added successfully.";

header("Location: index.php?module_id=" . $module_id);

exit();