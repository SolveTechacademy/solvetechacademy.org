<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$type = $_GET['type'] ?? '';

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$title = "";
$html = "";

switch($type){

case 'students':

$title = "Students Report";

$stmt = $pdo->query("
SELECT *
FROM students
ORDER BY id DESC
");

$html .= "
<h2 style='text-align:center;'>SolveTech Academy</h2>
<h3 style='text-align:center;'>Students Report</h3>

<p>Generated: ".date('d M Y H:i')."</p>

<table width='100%' border='1' cellspacing='0' cellpadding='6'>

<tr style='background:#eeeeee;'>

<th>ID</th>
<th>Student ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Status</th>

</tr>
";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

$html .= "

<tr>

<td>{$row['id']}</td>

<td>{$row['student_id']}</td>

<td>".htmlspecialchars($row['fullname'])."</td>

<td>".htmlspecialchars($row['email'])."</td>

<td>".htmlspecialchars($row['phone'])."</td>

<td>{$row['status']}</td>

</tr>

";

}

$html.="</table>";

break;

case 'courses':

$title="Courses Report";

$stmt=$pdo->query("
SELECT *
FROM courses
ORDER BY id DESC
");

$html.="

<h2 style='text-align:center;'>SolveTech Academy</h2>

<h3 style='text-align:center;'>Courses Report</h3>

<p>Generated: ".date('d M Y H:i')."</p>

<table width='100%' border='1' cellspacing='0' cellpadding='6'>

<tr style='background:#eeeeee;'>

<th>Code</th>

<th>Course</th>

<th>Instructor</th>

<th>Category</th>

<th>Fee</th>

<th>Status</th>

</tr>

";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

$html.="

<tr>

<td>{$row['course_code']}</td>

<td>".htmlspecialchars($row['course_title'])."</td>

<td>".htmlspecialchars($row['instructor'])."</td>

<td>{$row['category']}</td>

<td>{$row['course_fee']}</td>

<td>{$row['status']}</td>

</tr>

";

}

$html.="</table>";

break;

case 'payments':

$title="Payments Report";

$stmt=$pdo->query("
SELECT *
FROM payments
ORDER BY id DESC
");

$html.="

<h2 style='text-align:center;'>SolveTech Academy</h2>

<h3 style='text-align:center;'>Payments Report</h3>

<p>Generated: ".date('d M Y H:i')."</p>

<table width='100%' border='1' cellspacing='0' cellpadding='6'>

<tr style='background:#eeeeee;'>

<th>Payment ID</th>

<th>Registration</th>

<th>Amount</th>

<th>Method</th>

<th>Status</th>

</tr>

";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

$html.="

<tr>

<td>{$row['payment_id']}</td>

<td>{$row['registration_id']}</td>

<td>{$row['amount']}</td>

<td>{$row['payment_method']}</td>

<td>{$row['status']}</td>

</tr>

";

}

$html.="</table>";

break;

case 'certificates':

$title="Certificates Report";

$stmt=$pdo->query("
SELECT certificate_number,student_id,course_id,grade,status
FROM certificates
ORDER BY id DESC
");

$html.="

<h2 style='text-align:center;'>SolveTech Academy</h2>

<h3 style='text-align:center;'>Certificates Report</h3>

<p>Generated: ".date('d M Y H:i')."</p>

<table width='100%' border='1' cellspacing='0' cellpadding='6'>

<tr style='background:#eeeeee;'>

<th>Certificate</th>

<th>Student</th>

<th>Course</th>

<th>Grade</th>

<th>Status</th>

</tr>

";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

$html.="

<tr>

<td>{$row['certificate_number']}</td>

<td>{$row['student_id']}</td>

<td>{$row['course_id']}</td>

<td>{$row['grade']}</td>

<td>{$row['status']}</td>

</tr>

";

}

$html.="</table>";

break;

case 'assignments':

$title="Assignments Report";

$stmt=$pdo->query("
SELECT *
FROM assignments
ORDER BY id DESC
");

$html.="

<h2 style='text-align:center;'>SolveTech Academy</h2>

<h3 style='text-align:center;'>Assignments Report</h3>

<p>Generated: ".date('d M Y H:i')."</p>

<table width='100%' border='1' cellspacing='0' cellpadding='6'>

<tr style='background:#eeeeee;'>

<th>Lesson</th>

<th>Title</th>

<th>Deadline</th>

</tr>

";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

$html.="

<tr>

<td>{$row['lesson_id']}</td>

<td>".htmlspecialchars($row['title'])."</td>

<td>{$row['deadline']}</td>

</tr>

";

}

$html.="</table>";

break;

case 'announcements':

$title="Announcements Report";

$stmt=$pdo->query("
SELECT *
FROM announcements
ORDER BY id DESC
");

$html.="

<h2 style='text-align:center;'>SolveTech Academy</h2>

<h3 style='text-align:center;'>Announcements Report</h3>

<p>Generated: ".date('d M Y H:i')."</p>

<table width='100%' border='1' cellspacing='0' cellpadding='6'>

<tr style='background:#eeeeee;'>

<th>Title</th>

<th>Audience</th>

<th>Status</th>

<th>Publish Date</th>

</tr>

";

while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

$html.="

<tr>

<td>".htmlspecialchars($row['title'])."</td>

<td>{$row['audience']}</td>

<td>{$row['status']}</td>

<td>{$row['publish_date']}</td>

</tr>

";

}

$html.="</table>";

break;

default:

die("Invalid report.");

}

$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream($title.".pdf",[
"Attachment"=>true
]);

exit;