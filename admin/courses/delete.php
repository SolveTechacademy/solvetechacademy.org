<?php

require_once '../includes/auth.php';

if (!isset($_GET['id'])) {

    header("Location: index.php");

    exit();

}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT thumbnail FROM courses WHERE id = ?");

$stmt->execute([$id]);

$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {

    $_SESSION['error'] = "Course not found.";

    header("Location: index.php");

    exit();

}
if (!empty($course['thumbnail'])) {

    $imagePath = "../../assets/uploads/courses/" . $course['thumbnail'];

    if (file_exists($imagePath)) {

        unlink($imagePath);

    }

}
$stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");

$stmt->execute([$id]);
$_SESSION['success'] = "Course deleted successfully.";

header("Location: index.php");

exit();