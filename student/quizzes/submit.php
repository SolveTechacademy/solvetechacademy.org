<?php
session_start();

require_once '../../config/auth.php';
require_once '../../config/database.php';

if (!isset($_SESSION['student_login'])) {
    header("Location: ../../login.php");
    exit();
}

$student_db_id = $_SESSION['student_db_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

if (!isset($_POST['quiz_id']) || !is_numeric($_POST['quiz_id'])) {
    die("Invalid quiz.");
}

$quiz_id = (int)$_POST['quiz_id'];

if (!isset($_POST['answers']) || !is_array($_POST['answers'])) {
    die("No answers submitted.");
}

$answers = $_POST['answers'];

/*
|--------------------------------------------------------------------------
| Load Quiz
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM quizzes
WHERE id=?
AND status='Active'
LIMIT 1
");

$stmt->execute([$quiz_id]);

$quiz = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die("Quiz not found.");
}

/*
|--------------------------------------------------------------------------
| Check Attempts
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT COUNT(*)
FROM quiz_attempts
WHERE quiz_id=?
AND student_id=?
");

$stmt->execute([
    $quiz_id,
    $student_db_id
]);

$attempts_used = $stmt->fetchColumn();

if ($attempts_used >= $quiz['attempts']) {
    die("Maximum attempts reached.");
}

/*
|--------------------------------------------------------------------------
| Load Questions
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM quiz_questions
WHERE quiz_id=?
");

$stmt->execute([$quiz_id]);

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_questions = count($questions);
$correct_answers = 0;

$pdo->beginTransaction();

try {

    /*
    |--------------------------------------------------------------------------
    | Create Attempt
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
INSERT INTO quiz_attempts
(
    student_id,
    quiz_id,
    score,
    total_questions,
    percentage,
    status,
    started_at,
    completed_at
)
VALUES
(
:student_id,
:quiz_id,
0,
:total_questions,
0,
:status,
NOW(),
NOW()
)
");

$stmt->execute([
    ':student_id'      => $student_db_id,
    ':quiz_id'         => $quiz_id,
    ':total_questions' => $total_questions,
    ':status'          => 'Failed'
]);

$attempt_id = $pdo->lastInsertId();
    /*
    |--------------------------------------------------------------------------
    | Save Answers
    |--------------------------------------------------------------------------
    */

    foreach ($questions as $question) {

    $question_id = $question['id'];

    $student_answer = strtoupper($answers[$question_id] ?? '');

    $correct_answer = strtoupper(trim($question['correct_option']));

    $is_correct = ($student_answer === $correct_answer) ? 1 : 0;

    if ($is_correct) {
        $correct_answers++;
    }

    $stmt = $pdo->prepare("
        INSERT INTO quiz_answers
        (
            attempt_id,
            question_id,
            selected_option,
            is_correct
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $attempt_id,
        $question_id,
        $student_answer,
        $is_correct
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Calculate Result
    |--------------------------------------------------------------------------
    */

    $percentage = 0;

    if ($total_questions > 0) {
        $percentage = round(($correct_answers / $total_questions) * 100, 2);
    }

    $status = ($percentage >= $quiz['pass_mark'])
? 'Passed'
: 'Failed';

$stmt = $pdo->prepare("
UPDATE quiz_attempts
SET
score=?,
percentage=?,
status=?,
completed_at=NOW()
WHERE id=?
");

$stmt->execute([
$correct_answers,
$percentage,
$status,
$attempt_id
]);

/*
|--------------------------------------------------------------------------
| AUTO GENERATE CERTIFICATE
|--------------------------------------------------------------------------
*/

if ($status == 'Passed') {

    // Get the course linked to this quiz
    $stmt = $pdo->prepare("
        SELECT cm.course_id
        FROM quizzes q
        INNER JOIN course_modules cm
            ON cm.id = q.module_id
        WHERE q.id = ?
        LIMIT 1
    ");

    $stmt->execute([$quiz_id]);

    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {

        $course_id = $course['course_id'];

        // Get student's approved registration
        $stmt = $pdo->prepare("
            SELECT *
            FROM registrations
            WHERE student_id = ?
            AND course_id = ?
            AND approval_status = 'Approved'
            LIMIT 1
        ");

        $stmt->execute([
            $student_db_id,
            $course_id
        ]);

        $registration = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registration) {

            // Check if certificate already exists
            $stmt = $pdo->prepare("
                SELECT id
                FROM certificates
                WHERE registration_id = ?
                LIMIT 1
            ");

            $stmt->execute([
                $registration['id']
            ]);

            if (!$stmt->fetch()) {

                // Generate Certificate Number
                $year = date('Y');

                $next = $pdo->query("
                    SELECT COUNT(*) + 1
                    FROM certificates
                ")->fetchColumn();

                $certificate_number =
                    "STA-CERT-" .
                    $year .
                    "-" .
                    str_pad($next, 6, "0", STR_PAD_LEFT);

                // Verification Code
                $verification_code =
                    strtoupper(bin2hex(random_bytes(8)));

                // Grade
                if ($percentage >= 90) {
                    $grade = "A";
                } elseif ($percentage >= 80) {
                    $grade = "B";
                } elseif ($percentage >= 70) {
                    $grade = "C";
                } elseif ($percentage >= 60) {
                    $grade = "D";
                } else {
                    $grade = "F";
                }

                // Insert Certificate
                $stmt = $pdo->prepare("
                    INSERT INTO certificates
                    (
                        student_id,
                        registration_id,
                        course_id,
                        certificate_number,
                        verification_code,
                        issue_date,
                        completion_date,
                        grade,
                        final_score,
                        status
                    )
                    VALUES
                    (
                        ?,?,?,?,?,CURDATE(),CURDATE(),?,?,?
                    )
                ");

                $stmt->execute([
                    $student_db_id,
                    $registration['id'],
                    $course_id,
                    $certificate_number,
                    $verification_code,
                    $grade,
                    $percentage,
                    'Issued'
                ]);

            }

        }

    }

}

    $pdo->commit();

    header("Location: result.php?attempt_id=".$attempt_id);
    exit();

} catch (Exception $e) {

    $pdo->rollBack();

    die("Error saving quiz. ".$e->getMessage());

}