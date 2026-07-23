<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

/*
|--------------------------------------------------------------------------
| Student Authentication
|--------------------------------------------------------------------------
*/

$currentPage = basename($_SERVER['PHP_SELF']);

$publicPages = [
    'login.php',
    'register.php',
    'register_process.php',
    'forgot-password.php',
    'reset-password.php'
];

if (!in_array($currentPage, $publicPages)) {

    if (
        !isset($_SESSION['student_login']) ||
        $_SESSION['student_login'] !== true
    ) {

        header("Location: /solvetechacademy.org/login.php");
        exit();

    }

}