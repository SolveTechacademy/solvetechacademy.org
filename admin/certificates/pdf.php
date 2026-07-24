<?php
require_once dirname(__DIR__,2).'/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

if(!isset($_GET['registration_id'])){

die("Invalid Request");

}

$registration_id=(int)$_GET['registration_id'];

$stmt=$pdo->prepare("

SELECT

cert.*,

s.student_id,
s.fullname,

c.course_title,
c.duration,

r.training_mode

FROM certificates cert

INNER JOIN students s
ON cert.student_id=s.id

INNER JOIN courses c
ON cert.course_id=c.id

INNER JOIN registrations r
ON cert.registration_id=r.id

WHERE cert.registration_id=?

LIMIT 1

");

$stmt->execute([$registration_id]);

$certificate=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$certificate){

die("Certificate not found.");

}

$options=new Options();
$options->set('isRemoteEnabled',true);

$dompdf=new Dompdf($options);

$html='

<html>

<head>

<style>

body{

font-family:DejaVu Sans;

margin:40px;

}

.certificate{

border:12px solid #0d6efd;

padding:60px;

text-align:center;

}

h1{

font-size:42px;

color:#0d6efd;

}

h2{

font-size:30px;

margin-bottom:40px;

}

.student{

font-size:34px;

font-weight:bold;

color:#198754;

margin:30px 0;

}

p{

font-size:18px;

line-height:1.8;

}

.footer{

margin-top:70px;

width:100%;

}

.left{

float:left;

width:40%;

text-align:center;

}

.right{

float:right;

width:40%;

text-align:center;

}

.line{

border-top:1px solid #000;

margin-bottom:8px;

}

</style>

</head>

<body>

<div class="certificate">

<h1>

SolveTech Academy

</h1>

<h2>

CERTIFICATE OF COMPLETION

</h2>

<p>

This certificate is proudly awarded to

</p>

<p class="student">

'.$certificate['fullname'].'

</p>

<p>

For successfully completing the professional training programme in

</p>

<p>

<b>'.$certificate['course_title'].'</b>

</p>

';

$html .= '

<p>

Training Mode:
<b>'.$certificate['training_mode'].'</b>

</p>

<p>

Course Duration:
<b>'.$certificate['duration'].'</b>

</p>

<p>

Completion Date:
<b>'.date("d F Y",strtotime($certificate['completion_date'])).'</b>

</p>

<p>

Certificate Number:
<b>'.$certificate['certificate_number'].'</b>

</p>

<div class="footer">

<div class="left">

<div class="line"></div>

Academic Director

</div>

<div class="right">

<div class="line"></div>

Executive Director

</div>

<div style="clear:both;"></div>

</div>

</div>

</body>

</html>

';

$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream(

$certificate['certificate_number'].'.pdf',

["Attachment"=>false]

);

exit;