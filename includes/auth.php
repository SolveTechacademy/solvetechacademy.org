<?php

session_start();
require_once __DIR__ . '/flash.php';

require_once __DIR__ . '/config.php';

require_once __DIR__ . '/../../config/database.php';

if(!isset($_SESSION['admin_login'])){

    header("Location: ../login.php");

    exit();

}