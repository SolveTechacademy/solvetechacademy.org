<?php

require_once '../includes/auth.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Student not found.";
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

try {

    $pdo->beginTransaction();

    // Get all registration IDs belonging to the student
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE student_id = ?");
    $stmt->execute([$id]);
    $registrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Delete payments linked to those registrations
    if (!empty($registrations)) {

        $placeholders = implode(',', array_fill(0, count($registrations), '?'));

        $stmt = $pdo->prepare("DELETE FROM payments WHERE registration_id IN ($placeholders)");
        $stmt->execute($registrations);

    }

    // Delete registrations
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE student_id = ?");
    $stmt->execute([$id]);

    // Delete student
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);

    $pdo->commit();

    $_SESSION['success'] = "Student deleted successfully.";

} catch (PDOException $e) {

    $pdo->rollBack();

    $_SESSION['error'] = $e->getMessage();

}

header("Location: index.php");
exit();