<?php

require '../../config/database.php';

$course = $_GET['course_id'];

$stmt = $pdo->prepare("
SELECT
id,
module_title
FROM course_modules
WHERE course_id=?
ORDER BY module_order
");

$stmt->execute([$course]);

echo '<option value="">Select Module</option>';

while($row=$stmt->fetch(PDO::FETCH_ASSOC))
{
    echo '<option value="'.$row['id'].'">'
    .htmlspecialchars($row['module_title']).
    '</option>';
}