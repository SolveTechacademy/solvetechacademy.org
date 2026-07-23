<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/auth.php';

if (!isset($_GET['id'])) {

    header("Location: ../courses/index.php");
    exit();

}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");

$stmt->execute([$id]);

$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) {

    $_SESSION['error'] = "Lesson not found.";

    header("Location: ../courses/index.php");

    exit();

}

$module_id = $lesson['module_id'];
switch ($lesson['lesson_type']) {

    case "Video":
        $folder = "../../assets/uploads/lessons/videos/";
        break;

    case "PDF":
        $folder = "../../assets/uploads/lessons/pdfs/";
        break;

    case "Document":
        $folder = "../../assets/uploads/lessons/documents/";
        break;

    default:
        $folder = "";
}

if (!empty($lesson['file_path']) && !empty($folder)) {

    $file = $folder . $lesson['file_path'];

    if (file_exists($file)) {

        unlink($file);

    }

}

$stmt = $pdo->prepare("DELETE FROM lessons WHERE id = ?");

$stmt->execute([$id]);

$_SESSION['success'] = "Lesson deleted successfully.";

header("Location: index.php?module_id=" . $module_id);

exit();