<?php

session_start();

if (!isset($_SESSION['admin_login'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/database.php';