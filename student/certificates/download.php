<?php

session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

require_once '../../vendor/autoload.php';


use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

if (!isset($_SESSION['student_login'])) {

    header("Location: ../../login.php");
    exit();

}

if (!isset($_GET['id'])) {

    die("Invalid Certificate");

}

$student_db_id = $_SESSION['student_db_id'];

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("

SELECT

c.*,

co.course_title,

s.fullname,

s.email

FROM certificates c

INNER JOIN students s
ON s.id = c.student_id

INNER JOIN courses co
ON co.id = c.course_id

WHERE

c.id=?

AND

c.student_id=?

LIMIT 1

");

$stmt->execute([

$id,

$student_db_id

]);

$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$certificate){

die("Certificate not found.");

}
$pdfDirectory = "../../uploads/certificates/";

if (!is_dir($pdfDirectory)) {
    mkdir($pdfDirectory, 0775, true);
}

$pdfFile = $pdfDirectory . $certificate['certificate_number'] . ".pdf";
if (file_exists($pdfFile)) {

    header("Content-Type: application/pdf");
    header(
        "Content-Disposition: attachment; filename=\"" .
        basename($pdfFile) .
        "\""
    );

    readfile($pdfFile);

    exit;
}

$options = new Options();

$options->set('isHtml5ParserEnabled',true);

$options->set('isRemoteEnabled',true);

$dompdf = new Dompdf($options);

$studentName = $certificate['fullname'];

$logo="logo.png";

$trainingLogo="icon.png";

$signature="signature.png";

$verificationLink =
"https://solvetechacademy.org/student/certificates/verify.php?code="

.$certificate['verification_code'];
/*
|--------------------------------------------------------------------------
| Generate QR Code
|--------------------------------------------------------------------------
*/

$qrFile = sys_get_temp_dir() . '/certificate_qr_' . $certificate['id'] . '.png';


$result = new Builder(
    writer: new PngWriter(),
    data: $verificationLink,
    size: 220,
    margin: 10
);

$result->build()->saveToFile($qrFile);

$html='



<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<style>

*{

margin:0;

padding:0;

box-sizing:border-box;

}

body{

font-family:DejaVu Sans,sans-serif;

background:#ffffff;

padding:18px;

}

.certificate{

position:relative;

width:100%;

height:100%;

padding:40px;

border:12px solid #163d7a;

background:#fff;

}

.inner-border{

position:absolute;

top:18px;

left:18px;

right:18px;

bottom:18px;

border:3px solid #d4af37;

}

.watermark{

position:absolute;

top:140px;

left:170px;

opacity:.06;

}

.header{

width:100%;

margin-bottom:30px;

}

.left-logo{

float:left;

width:230px;

}

.right-logo{

float:right;

width:170px;

margin-top:15px;

}

.clear{

clear:both;

}

.title{

text-align:center;

font-size:34px;

font-weight:bold;

color:#163d7a;

letter-spacing:4px;

margin-top:20px;

}

.line{

width:180px;

height:4px;

background:#d4af37;

margin:15px auto;

}

.subtitle{

text-align:center;

font-size:19px;

color:#666;

margin-top:25px;

}

.student{

font-size:44px;

font-family:Georgia;

font-weight:bold;

color:#163d7a;

margin-top:20px;

letter-spacing:1px;

}

.student-line{

width:72%;

margin:10px auto 0 auto;

border-bottom:2px solid #163d7a;

}

.text{

margin-top:28px;

text-align:center;

font-size:18px;

line-height:34px;

color:#444;

}

.course{

margin-top:18px;

text-align:center;

font-size:31px;

font-weight:bold;

color:#163d7a;

text-transform:uppercase;

letter-spacing:1px;

}
.ribbon{

position:absolute;

top:0;

right:55px;

width:110px;

height:540px;

background:#163d7a;

border-left:4px solid #d4af37;

border-right:4px solid #d4af37;

}

.ribbon-bottom{

position:absolute;

bottom:-1px;

left:0;

width:0;

height:0;

border-left:55px solid transparent;

border-right:55px solid transparent;

border-top:45px solid #d4af37;

}

.seal{

position:absolute;

right:12px;

top:205px;

width:195px;

height:195px;

border-radius:50%;

background:#fff;

border:8px solid #163d7a;

text-align:center;

padding-top:20px;

box-shadow:0 0 15px rgba(0,0,0,.18);

}

.seal img{

width:80px;

margin-top:8px;

}

.seal-title{

font-size:15px;

font-weight:bold;

color:#163d7a;

letter-spacing:2px;

margin-bottom:8px;

}

.info{

margin-top:55px;

width:72%;

border-collapse:collapse;

}

.info td{

padding:12px;

border-bottom:1px solid #ddd;

font-size:16px;

}

.label{

font-weight:bold;

color:#163d7a;

width:220px;

}

.footer{

margin-top:70px;

width:100%;

}

.signature{

float:left;

width:45%;

text-align:center;

}

.signature img{

height:90px;

}

.signature-name{

margin-top:8px;

font-size:20px;

font-weight:bold;

color:#163d7a;

}

.signature-title{

font-size:16px;

color:#555;

}

.qr{

float:right;

width:35%;

text-align:center;

}

.qr-box{

width:130px;

height:130px;

margin:auto;

border:1px solid #ccc;

padding:8px;

}

.verify{

margin-top:10px;

font-size:13px;

color:#666;

}

.verify strong{

color:#163d7a;

}

.clearfix{

clear:both;

}

</style>

</head>

<body>

<div class="certificate">
<div class="inner-border"></div>

    <!-- Top Left Corner -->
    <div style="
        position:absolute;
        top:0;
        left:0;
        width:90px;
        height:90px;
        border-top:14px solid #d4af37;
        border-left:14px solid #d4af37;
    "></div>

    <!-- Top Right Corner -->
    <div style="
        position:absolute;
        top:0;
        right:0;
        width:90px;
        height:90px;
        border-top:14px solid #d4af37;
        border-right:14px solid #d4af37;
    "></div>

    <!-- Bottom Left Corner -->
    <div style="
        position:absolute;
        bottom:0;
        left:0;
        width:90px;
        height:90px;
        border-bottom:14px solid #d4af37;
        border-left:14px solid #d4af37;
    "></div>

    <!-- Bottom Right Corner -->
    <div style="
        position:absolute;
        bottom:0;
        right:0;
        width:90px;
        height:90px;
        border-bottom:14px solid #d4af37;
        border-right:14px solid #d4af37;
    "></div>

   <div class="watermark">

<img
src="'.$logo.'"
style="
width:420px;
">

</div>

<table
width="100%"
style="margin-bottom:20px;"
>

<tr>

<td width="25%">

<img
src="'.$logo.'"
style="
height:75px;
">

</td>

<td
width="50%"
align="center"
>

<div
style="
font-size:18px;
font-weight:bold;
color:#163d7a;
">

SOLVETECH ACADEMY

</div>

<div
style="
font-size:13px;
color:#777;
margin-top:5px;
">

Professional IT Training & Certification

</div>

</td>

<td
width="25%"
align="right"
>

<img
src="'.$trainingLogo.'"
style="
height:75px;
">

</td>

</tr>

</table>

<div class="title">

CERTIFICATE OF COMPLETION

</div>

<div class="line"></div>

<div class="subtitle">

This Certificate is Proudly Presented To

</div>

<div class="student">

'.$studentName.'

</div>

<div class="student-line"></div>

<div class="text">

For successfully completing the professional training programme

</div>

<div class="course">

'.$certificate['course_title'].'

</div>
<div
style="
width:260px;
height:3px;
background:#d4af37;
margin:25px auto;
">
</div>
<table
width="70%"
align="center"
style="
margin-top:50px;
border-collapse:collapse;
font-size:15px;
">

<tr>

<td class="label">

Certificate Number

</td>

<td>

'.$certificate['certificate_number'].'

</td>

</tr>

<tr>

<td class="label">

Completion Date

</td>

<td>

'.date("d F Y",strtotime($certificate['completion_date'])).'

</td>

</tr>

<tr>

<td class="label">

Verification Code

</td>

<td>

'.$certificate['verification_code'].'

</td>

</tr>

</table>

<div class="ribbon">

<div class="ribbon-bottom"></div>

</div>

<div
style="
position:absolute;
right:65px;
top:220px;
width:170px;
height:170px;
border:6px solid #163d7a;
border-radius:50%;
text-align:center;
background:#ffffff;
">

<div
style="
margin-top:18px;
font-size:11px;
font-weight:bold;
color:#163d7a;
letter-spacing:2px;
">

SOLVETECH

</div>

<div
style="
font-size:11px;
font-weight:bold;
color:#163d7a;
letter-spacing:2px;
">

ACADEMY

</div>

<img
src="'.$trainingLogo.'"
style="
width:65px;
margin-top:12px;
">

<div
style="
margin-top:10px;
font-size:13px;
font-weight:bold;
color:#d4af37;
">

OFFICIAL

</div>

<div
style="
font-size:13px;
font-weight:bold;
color:#163d7a;
">

CERTIFICATE

</div>

</div>

<div class="footer">

<table style="width:100%;margin-top:60px;">

<tr>

<td style="width:35%;text-align:center;vertical-align:bottom;">

<img
src="'.$signature.'"
style="
height:85px;
">

<hr style="margin-top:8px;">

<div style="
font-size:20px;
font-weight:bold;
color:#163d7a;
">

Engr. Valery Nkam

</div>

<div style="
font-size:15px;
color:#555;
">

Founder & Academy Director

</div>

</td>

<td style="width:30%;text-align:center;vertical-align:bottom;">

<div style="
font-size:13px;
color:#666;
">

Officially Issued by

</div>

<div style="
font-size:18px;
font-weight:bold;
color:#163d7a;
margin-top:5px;
">

SolveTech Academy

</div>

<div style="
margin-top:10px;
font-size:13px;
color:#777;
">

Professional IT Training & Certification

</div>

</td>

<td style="width:35%;text-align:center;">

<img
src="'.$qrFile.'"
style="
width:125px;
height:125px;
">

<div style="
margin-top:10px;
font-size:15px;
font-weight:bold;
color:#163d7a;
">

Scan to Verify

</div>

<div style="
font-size:11px;
color:#666;
margin-top:4px;
">

'.$verificationLink.'

</div>

</td>

</tr>

</table>

</div>

<div class="clearfix"></div>

</div>

</div>
<div
style="
position:absolute;
bottom:18px;
left:40px;
font-size:10px;
color:#999;
">

Certificate ID:
'.$certificate['certificate_number'].'

</div>

</body>

</html>

';
$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream(
    $certificate['certificate_number'] . ".pdf",
    [
        "Attachment" => true
    ]
);

if (file_exists($qrFile)) {
    unlink($qrFile);
}

exit;