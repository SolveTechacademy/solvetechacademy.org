<?php

/**
 * Return the student's profile photo
 */
function studentPhoto($photo)
{
    if (!empty($photo) && file_exists("../../uploads/students/" . $photo)) {
        return "../../uploads/students/" . $photo;
    }

    return "../../assets/images/default-avatar.png";
}

/**
 * Calculate profile completion percentage
 */
function profileCompletion(array $student): int
{
    $fields = [
        'fullname',
        'email',
        'phone',
        'country',
        'city',
        'qualification',
        'occupation',
        'profile_photo'
    ];

    $completed = 0;

    foreach ($fields as $field) {
        if (!empty($student[$field])) {
            $completed++;
        }
    }

    return round(($completed / count($fields)) * 100);
}

/**
 * Return Bootstrap badge class
 */
function statusBadge($status): string
{
    return match ($status) {

        'Active' => 'success',

        'Pending' => 'warning',

        'Graduated' => 'primary',

        'Suspended' => 'danger',

        default => 'secondary'
    };
}

/**
 * Format date
 */
function formatDate($date): string
{
    if (empty($date)) {
        return '-';
    }

    return date("d M Y", strtotime($date));
}

/**
 * Student initials
 */
function studentInitials($name): string
{
    $words = explode(' ', trim($name));

    $initials = '';

    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }

    return substr($initials, 0, 2);
}

/**
 * Escape output
 */
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}