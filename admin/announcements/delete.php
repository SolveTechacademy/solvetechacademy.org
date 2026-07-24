<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if(!isset($_GET['id'])){
    header("Location:index.php");
    exit;
}

$stmt=$pdo->prepare("
DELETE FROM announcements
WHERE id=?
");

$stmt->execute([(int)$_GET['id']]);

header("Location:index.php?deleted=1");
exit;