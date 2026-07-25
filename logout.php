<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Remove Remember Me Cookie
|--------------------------------------------------------------------------
*/
if (isset($_COOKIE['remember_email'])) {
    setcookie(
        'remember_email',
        '',
        time() - 3600,
        '/',
        '',
        false,
        true
    );
}

/*
|--------------------------------------------------------------------------
| Clear Session Data
|--------------------------------------------------------------------------
*/
$_SESSION = [];

/*
|--------------------------------------------------------------------------
| Destroy Session Cookie
|--------------------------------------------------------------------------
*/
if (ini_get('session.use_cookies')) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

/*
|--------------------------------------------------------------------------
| Destroy Session
|--------------------------------------------------------------------------
*/
session_destroy();

/*
|--------------------------------------------------------------------------
| Start Fresh Session
|--------------------------------------------------------------------------
*/
session_start();

session_regenerate_id(true);

$_SESSION['success'] = "You have been logged out successfully.";

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/
header("Location: login.php");

exit();