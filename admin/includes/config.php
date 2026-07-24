<?php

/*
|--------------------------------------------------------------------------
| SolveTech Academy LMS Configuration
|--------------------------------------------------------------------------
| Central application configuration.
| Edit values here instead of hardcoding them throughout the project.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Application
|--------------------------------------------------------------------------
*/
define('APP_NAME', 'SolveTech Academy LMS');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');   // development | production

/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Africa/Douala');

/*
|--------------------------------------------------------------------------
| Base URL
|--------------------------------------------------------------------------
*/

define('BASE_URL', '/solvetechacademy.org');
define('ADMIN_URL', BASE_URL . '/admin');

/*
|--------------------------------------------------------------------------
| Assets
|--------------------------------------------------------------------------
*/

define('ASSETS_URL', BASE_URL . '/assets');
define('ADMIN_ASSETS', ASSETS_URL);

/*
|--------------------------------------------------------------------------
| Upload Directories (Filesystem)
|--------------------------------------------------------------------------
*/

define('UPLOAD_PATH', dirname(__DIR__, 2) . '/assets/uploads');

define('COURSE_UPLOADS', UPLOAD_PATH . '/courses');
define('LESSON_UPLOADS', UPLOAD_PATH . '/lessons');
define('INSTRUCTOR_UPLOADS', UPLOAD_PATH . '/instructors');
define('STUDENT_UPLOADS', UPLOAD_PATH . '/students');
define('CERTIFICATE_UPLOADS', UPLOAD_PATH . '/certificates');
define('ASSIGNMENT_UPLOADS', UPLOAD_PATH . '/assignments');

/*
|--------------------------------------------------------------------------
| Upload URLs
|--------------------------------------------------------------------------
*/

define('UPLOAD_URL', BASE_URL . '/assets/uploads');

define('COURSE_UPLOAD_URL', UPLOAD_URL . '/courses');
define('LESSON_UPLOAD_URL', UPLOAD_URL . '/lessons');
define('INSTRUCTOR_UPLOAD_URL', UPLOAD_URL . '/instructors');
define('STUDENT_UPLOAD_URL', UPLOAD_URL . '/students');

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

define('ITEMS_PER_PAGE', 20);

/*
|--------------------------------------------------------------------------
| Currency
|--------------------------------------------------------------------------
*/

define('DEFAULT_CURRENCY', 'XAF');
define('CURRENCY_SYMBOL', 'FCFA');

/*
|--------------------------------------------------------------------------
| Date Formats
|--------------------------------------------------------------------------
*/

define('DATE_FORMAT', 'd M Y');
define('DATETIME_FORMAT', 'd M Y H:i');

/*
|--------------------------------------------------------------------------
| Allowed Upload Types
|--------------------------------------------------------------------------
*/

define('IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp']);

define('VIDEO_TYPES', ['mp4', 'mov', 'avi', 'mkv']);

define('DOCUMENT_TYPES', [
    'pdf',
    'doc',
    'docx',
    'ppt',
    'pptx',
    'xls',
    'xlsx',
    'zip'
]);

/*
|--------------------------------------------------------------------------
| Maximum Upload Size
|--------------------------------------------------------------------------
*/

define('MAX_UPLOAD_SIZE', 50 * 1024 * 1024); // 50MB