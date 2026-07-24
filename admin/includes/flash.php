<?php

if (!function_exists('success')) {

    function success($message)
    {
        $_SESSION['success'] = $message;
    }

}

if (!function_exists('error')) {

    function error($message)
    {
        $_SESSION['error'] = $message;
    }

}

if (!function_exists('displayFlash')) {

    function displayFlash()
    {

        if (isset($_SESSION['success'])) {

            echo '

            <div class="alert alert-success alert-dismissible fade show">

                '.$_SESSION['success'].'

                <button class="btn-close" data-bs-dismiss="alert"></button>

            </div>

            ';

            unset($_SESSION['success']);

        }

        if (isset($_SESSION['error'])) {

            echo '

            <div class="alert alert-danger alert-dismissible fade show">

                '.$_SESSION['error'].'

                <button class="btn-close" data-bs-dismiss="alert"></button>

            </div>

            ';

            unset($_SESSION['error']);

        }

    }

}