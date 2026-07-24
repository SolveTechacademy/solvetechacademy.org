<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$id = (int)$_POST['id'];

$stmt = $pdo->prepare("SELECT * FROM settings WHERE id=?");
$stmt->execute([$id]);
$old = $stmt->fetch(PDO::FETCH_ASSOC);

$logo = $old['academy_logo'];
$favicon = $old['academy_favicon'];

$uploadDir = "../../uploads/settings/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

/* ===========================
   Upload Logo
=========================== */

if(isset($_FILES['academy_logo']) && $_FILES['academy_logo']['error']==0){

    $ext = strtolower(pathinfo($_FILES['academy_logo']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png','gif','webp'];

    if(in_array($ext,$allowed)){

        $logo = uniqid('logo_').'.'.$ext;

        move_uploaded_file(
            $_FILES['academy_logo']['tmp_name'],
            $uploadDir.$logo
        );

    }

}

/* ===========================
   Upload Favicon
=========================== */

if(isset($_FILES['academy_favicon']) && $_FILES['academy_favicon']['error']==0){

    $ext = strtolower(pathinfo($_FILES['academy_favicon']['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png','gif','ico','webp'];

    if(in_array($ext,$allowed)){

        $favicon = uniqid('favicon_').'.'.$ext;

        move_uploaded_file(
            $_FILES['academy_favicon']['tmp_name'],
            $uploadDir.$favicon
        );

    }

}

/* ===========================
   Update Settings
=========================== */

$stmt = $pdo->prepare("

UPDATE settings SET

academy_name=?,
academy_email=?,
academy_phone=?,
academy_address=?,
academy_logo=?,
academy_favicon=?,
website=?,
timezone=?,
currency=?,
smtp_host=?,
smtp_port=?,
smtp_username=?,
smtp_password=?,
smtp_encryption=?,
sender_name=?,
sender_email=?,
maintenance_mode=?

WHERE id=?

");

$stmt->execute([

trim($_POST['academy_name']),
trim($_POST['academy_email']),
trim($_POST['academy_phone']),
trim($_POST['academy_address']),

$logo,
$favicon,

trim($_POST['website']),
trim($_POST['timezone']),
trim($_POST['currency']),

trim($_POST['smtp_host']),
trim($_POST['smtp_port']),
trim($_POST['smtp_username']),
trim($_POST['smtp_password']),
trim($_POST['smtp_encryption']),
trim($_POST['sender_name']),
trim($_POST['sender_email']),
trim($_POST['maintenance_mode']),

$id

]);

header("Location: index.php?success=1");
exit;