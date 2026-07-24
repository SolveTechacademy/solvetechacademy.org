<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if($_SERVER['REQUEST_METHOD']!='POST'){
    header("Location:index.php");
    exit;
}

$stmt=$pdo->prepare("
UPDATE announcements
SET
title=?,
message=?,
audience=?,
course_id=?,
status=?,
publish_date=?,
expiry_date=?
WHERE id=?
");

$stmt->execute([
trim($_POST['title']),
trim($_POST['message']),
trim($_POST['audience']),
!empty($_POST['course_id'])?$_POST['course_id']:NULL,
trim($_POST['status']),
$_POST['publish_date'],
!empty($_POST['expiry_date'])?$_POST['expiry_date']:NULL,
$_POST['id']
]);

header("Location:index.php?updated=1");
exit;