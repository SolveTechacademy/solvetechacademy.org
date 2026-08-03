<?php

if (!defined('STUDENT_ENGINE')) {
    define('STUDENT_ENGINE', true);
}

/*
|--------------------------------------------------------------------------
| Approved Course
|--------------------------------------------------------------------------
*/

function getApprovedCourse(PDO $pdo, int $studentId)
{
    $sql = "
        SELECT
            r.*,
            c.course_title,
            c.duration,
            c.level,
            c.mode,
            c.thumbnail
        FROM registrations r
        INNER JOIN courses c
            ON c.id = r.course_id
        WHERE r.student_id = ?
        AND r.approval_status='Approved'
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$studentId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| Total Lessons
|--------------------------------------------------------------------------
*/

function getTotalLessons(PDO $pdo, int $courseId): int
{
    $stmt = $pdo->prepare("
        SELECT COUNT(l.id)

        FROM lessons l

        INNER JOIN course_modules m
            ON m.id = l.module_id

        WHERE m.course_id = ?
        AND l.status='Active'
    ");

    $stmt->execute([$courseId]);

    return (int)$stmt->fetchColumn();
}
/*
|--------------------------------------------------------------------------
| Completed Lessons
|--------------------------------------------------------------------------
*/

function getCompletedLessons(PDO $pdo, int $studentId, int $courseId): int
{
    $stmt = $pdo->prepare("
        SELECT COUNT(lp.id)

        FROM lesson_progress lp

        INNER JOIN lessons l
            ON l.id = lp.lesson_id

        INNER JOIN course_modules m
            ON m.id = l.module_id

        WHERE lp.student_id = ?
        AND lp.completed = 1
        AND m.course_id = ?
    ");

    $stmt->execute([
        $studentId,
        $courseId
    ]);

    return (int)$stmt->fetchColumn();
}

/*
|--------------------------------------------------------------------------
| Progress
|--------------------------------------------------------------------------
*/

function getCourseProgress(PDO $pdo, int $studentId, int $courseId): int
{
    $total = getTotalLessons(
        $pdo,
        $courseId
    );

    if ($total == 0) {
        return 0;
    }

    $completed = getCompletedLessons(
        $pdo,
        $studentId,
        $courseId
    );

    return (int)floor(
        ($completed / $total) * 100
    );
}