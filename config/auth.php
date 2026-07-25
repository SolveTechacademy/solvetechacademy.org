<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/session.php';

/*
|--------------------------------------------------------------------------
| Generate CSRF Token
|--------------------------------------------------------------------------
*/
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
|--------------------------------------------------------------------------
| Session Timeout (30 Minutes)
|--------------------------------------------------------------------------
*/
if (isset($_SESSION['last_activity'])) {

    if ((time() - $_SESSION['last_activity']) > 1800) {

        session_unset();
        session_destroy();

        header("Location: /solvetechacademy.org/login.php?expired=1");
        exit();
    }
}

$_SESSION['last_activity'] = time();

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
/*
|--------------------------------------------------------------------------
| Validate Student Session
|--------------------------------------------------------------------------
*/
if (empty($_SESSION['student_db_id'])) {

    session_destroy();

    header("Location: /solvetechacademy.org/login.php");

    exit();
}
}