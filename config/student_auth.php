<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

if (!isset($_SESSION['student_login']) || !isset($_SESSION['student_db_id'])) {

    $_SESSION['error'] = "Please login first.";

    header("Location: ../login.php");

    exit();

}

$student_db_id = (int)$_SESSION['student_db_id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$student_db_id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {

    session_destroy();

    header("Location: ../login.php");

    exit();

}

if (strcasecmp(trim($student['status']), 'Active') !== 0) {

    session_destroy();

    $_SESSION['error'] = "Your account has not yet been approved.";

    header("Location: ../login.php");

    exit();

}

$GLOBALS['student'] = $student;