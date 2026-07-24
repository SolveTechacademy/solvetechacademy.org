<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if (!isset($_GET['registration_id'])) {
    header("Location: index.php");
    exit;
}

$registration_id = (int) $_GET['registration_id'];

$stmt = $pdo->prepare("
SELECT
    r.*,
    s.id AS student_id,
    c.id AS course_id

FROM registrations r

INNER JOIN students s
ON r.student_id = s.id

INNER JOIN courses c
ON r.course_id = c.id

WHERE r.id=?
");

$stmt->execute([$registration_id]);

$registration = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$registration){

die("Registration not found.");

}

$check = $pdo->prepare("
SELECT id
FROM certificates
WHERE registration_id=?
");

$check->execute([$registration_id]);

if($check->rowCount()>0){

die("Certificate already exists.");

}

$certificateNumber =
"STA-".
date("Y").
"-".
str_pad(rand(1,999999),6,"0",STR_PAD_LEFT);

$insert = $pdo->prepare("
INSERT INTO certificates(

student_id,
registration_id,
course_id,
certificate_number,
issue_date,
completion_date,
grade,
status

)

VALUES(

?,
?,
?,
?,
CURDATE(),
CURDATE(),
'PASS',
'Issued'

)

");
$insert = $pdo->prepare("
INSERT INTO certificates(

student_id,
registration_id,
course_id,
certificate_number,
issue_date,
completion_date,
grade,
status

)

VALUES(

?,
?,
?,
?,
CURDATE(),
CURDATE(),
'PASS',
'Issued'

)

");
$insert->execute([

    $registration['student_id'],
    $registration_id,
    $registration['course_id'],
    $certificateNumber

]);

header("Location: view.php?registration_id=".$registration_id);

exit;