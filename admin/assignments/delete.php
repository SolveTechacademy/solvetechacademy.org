<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];

try{

    $stmt = $pdo->prepare("
        DELETE FROM assignments
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    header("Location:index.php?deleted=1");
    exit;

}catch(PDOException $e){

    die("Database Error: ".$e->getMessage());

}