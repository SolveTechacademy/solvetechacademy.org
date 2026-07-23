<?php

require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: ../courses/index.php");
    exit();

}
$id            = (int) $_POST['id'];
$module_id     = (int) $_POST['module_id'];

$lesson_title  = trim($_POST['lesson_title']);
$lesson_type   = trim($_POST['lesson_type']);
$description   = trim($_POST['description']);
$video_url     = trim($_POST['video_url']);
$lesson_order  = (int) $_POST['lesson_order'];
$is_preview    = (int) $_POST['is_preview'];
$status        = trim($_POST['status']);
if (empty($lesson_title)) {

    $_SESSION['error'] = "Lesson title is required.";

    header("Location: edit.php?id=" . $id);

    exit();

}
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");

$stmt->execute([$id]);

$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {

    $_SESSION['error'] = "Lesson not found.";

    header("Location: index.php?module_id=" . $module_id);

    exit();

}

$file_path = $lesson['file_path'];
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

        if (!empty($lesson['file_path'])) {

            @unlink($folder . $lesson['file_path']);

        }

        $filename = uniqid("lesson_") . "." . $extension;

        move_uploaded_file(
            $_FILES['lesson_file']['tmp_name'],
            $folder . $filename
        );

        $file_path = $filename;

    }

}
$sql = "UPDATE lessons SET

lesson_title = ?,
lesson_type = ?,
description = ?,
video_url = ?,
file_path = ?,
lesson_order = ?,
is_preview = ?,
status = ?

WHERE id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    $lesson_title,
    $lesson_type,
    $description,
    $video_url,
    $file_path,
    $lesson_order,
    $is_preview,
    $status,
    $id

]);
$_SESSION['success'] = "Lesson updated successfully.";

header("Location: index.php?module_id=" . $module_id);

exit();