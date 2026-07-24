<?php
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../includes/auth.php';

$type = $_GET['type'] ?? '';

switch ($type) {

    case 'students':

        $filename = "Students_Report_" . date('Ymd_His') . ".csv";

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output = fopen("php://output", "w");

        fputcsv($output, [
            'Student ID',
            'Full Name',
            'Email',
            'Phone',
            'Country',
            'City',
            'Qualification',
            'Occupation',
            'Status',
            'Registered'
        ]);

        $stmt = $pdo->query("
            SELECT *
            FROM students
            ORDER BY id DESC
        ");

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            fputcsv($output, [

                $row['student_id'],
                $row['fullname'],
                $row['email'],
                $row['phone'],
                $row['country'],
                $row['city'],
                $row['qualification'],
                $row['occupation'],
                $row['status'],
                $row['created_at']

            ]);

        }

        fclose($output);

        exit;


    case 'courses':

        $filename = "Courses_Report_" . date('Ymd_His') . ".csv";

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output = fopen("php://output","w");

        fputcsv($output,[
            'Course Code',
            'Course Title',
            'Category',
            'Instructor',
            'Duration',
            'Level',
            'Mode',
            'Fee',
            'Status',
            'Created'
        ]);

        $stmt = $pdo->query("
            SELECT *
            FROM courses
            ORDER BY id DESC
        ");

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

            fputcsv($output,[

                $row['course_code'],
                $row['course_title'],
                $row['category'],
                $row['instructor'],
                $row['duration'],
                $row['level'],
                $row['mode'],
                $row['course_fee'],
                $row['status'],
                $row['created_at']

            ]);

        }

        fclose($output);

        exit;


    case 'payments':

        $filename="Payments_Report_".date('Ymd_His').".csv";

        header("Content-Type:text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output=fopen("php://output","w");

        fputcsv($output,[
            'Payment ID',
            'Registration ID',
            'Amount',
            'Method',
            'Transaction',
            'Status',
            'Date'
        ]);

        $stmt=$pdo->query("
            SELECT *
            FROM payments
            ORDER BY id DESC
        ");

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

            fputcsv($output,[

                $row['payment_id'],
                $row['registration_id'],
                $row['amount'],
                $row['payment_method'],
                $row['transaction_id'],
                $row['status'],
                $row['created_at']

            ]);

        }

        fclose($output);

        exit;


    case 'certificates':

        $filename="Certificates_Report_".date('Ymd_His').".csv";

        header("Content-Type:text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output=fopen("php://output","w");

        fputcsv($output,[
            'Certificate No',
            'Student ID',
            'Course ID',
            'Registration ID',
            'Grade',
            'Issue Date',
            'Completion Date',
            'Status'
        ]);

        $stmt=$pdo->query("
            SELECT *
            FROM certificates
            ORDER BY id DESC
        ");

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

            fputcsv($output,[

                $row['certificate_number'],
                $row['student_id'],
                $row['course_id'],
                $row['registration_id'],
                $row['grade'],
                $row['issue_date'],
                $row['completion_date'],
                $row['status']

            ]);

        }

        fclose($output);

        exit;


    case 'assignments':

        $filename="Assignments_Report_".date('Ymd_His').".csv";

        header("Content-Type:text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output=fopen("php://output","w");

        fputcsv($output,[
            'Lesson ID',
            'Title',
            'Instructions',
            'Deadline',
            'Created'
        ]);

        $stmt=$pdo->query("
            SELECT *
            FROM assignments
            ORDER BY id DESC
        ");

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

            fputcsv($output,[

                $row['lesson_id'],
                $row['title'],
                $row['instructions'],
                $row['deadline'],
                $row['created_at']

            ]);

        }

        fclose($output);

        exit;


    case 'announcements':

        $filename="Announcements_Report_".date('Ymd_His').".csv";

        header("Content-Type:text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output=fopen("php://output","w");

        fputcsv($output,[
            'Title',
            'Audience',
            'Course ID',
            'Status',
            'Publish Date',
            'Expiry Date'
        ]);

        $stmt=$pdo->query("
            SELECT *
            FROM announcements
            ORDER BY id DESC
        ");

        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

            fputcsv($output,[

                $row['title'],
                $row['audience'],
                $row['course_id'],
                $row['status'],
                $row['publish_date'],
                $row['expiry_date']

            ]);

        }

        fclose($output);

        exit;

    default:

        die("Invalid report type.");

}