<?php

if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();

}

$timeout = 1800;

if (isset($_SESSION['last_activity'])) {

    if ((time() - $_SESSION['last_activity']) > $timeout) {

        session_unset();

        session_destroy();

        session_start();

        $_SESSION['error'] = "Your session expired. Please login again.";

    }

}

$_SESSION['last_activity'] = time();