<?php

function generateStudentID(PDO $pdo)
{
    $query = $pdo->query("SELECT id FROM students ORDER BY id DESC LIMIT 1");
    $last = $query->fetch(PDO::FETCH_ASSOC);

    $number = $last ? $last['id'] + 1 : 1;

    return "STA-STU-" . str_pad($number,6,"0",STR_PAD_LEFT);
}

function generateRegistrationID(PDO $pdo)
{
    $query = $pdo->query("SELECT id FROM registrations ORDER BY id DESC LIMIT 1");
    $last = $query->fetch(PDO::FETCH_ASSOC);

    $number = $last ? $last['id'] + 1 : 1;

    return "STA-REG-" . str_pad($number,6,"0",STR_PAD_LEFT);
}

function clean($data)
{
    return htmlspecialchars(trim($data));
}