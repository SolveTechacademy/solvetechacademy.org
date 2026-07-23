<?php

require_once '../includes/auth.php';

$pageTitle = "Add New Course";

require_once '../includes/header.php';
require_once '../includes/sidebar.php';
require_once '../includes/topbar.php';



if(isset($_SESSION['success'])){

    echo '<div class="alert alert-success alert-dismissible fade show">
            '.$_SESSION['success'].'
            <button class="btn-close" data-bs-dismiss="alert"></button>
          </div>';

    unset($_SESSION['success']);

}

if(isset($_SESSION['error'])){

    echo '<div class="alert alert-danger alert-dismissible fade show">
            '.$_SESSION['error'].'
            <button class="btn-close" data-bs-dismiss="alert"></button>
          </div>';

    unset($_SESSION['error']);

}




/*
|--------------------------------------------------------------------------
| Generate Course Code
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("SELECT id FROM courses ORDER BY id DESC LIMIT 1");

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$nextID = $row ? $row['id'] + 1 : 1;

$courseCode = "STA-CRS-" . str_pad($nextID, 6, "0", STR_PAD_LEFT);

?>
<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="fas fa-book me-2"></i>

Add New Course

</h4>

</div>

<div class="card-body">

<form action="save.php" method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label>Course Code</label>

<input
type="text"
class="form-control"
name="course_code"
value="<?= $courseCode; ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label>Course Title</label>

<input
type="text"
class="form-control"
name="course_title"
required>

</div>

<div class="col-md-6 mb-3">

<label>Category</label>

<input
type="text"
class="form-control"
name="category"
placeholder="Cloud Computing">

</div>

<div class="col-md-6 mb-3">

<label>Instructor</label>

<input
type="text"
class="form-control"
name="instructor">

</div>

<div class="col-md-4 mb-3">

<label>Duration</label>

<input
type="text"
class="form-control"
name="duration">

</div>

<div class="col-md-4 mb-3">

<label>Level</label>

<select
class="form-select"
name="level">

<option>Beginner</option>

<option>Intermediate</option>

<option>Advanced</option>

</select>

</div>

<div class="col-md-4 mb-3">

<label>Price (FCFA)</label>

<input
type="number"
class="form-control"
name="price">

</div>

<div class="col-md-6 mb-3">

<label>Mode</label>

<select
class="form-select"
name="mode">

<option>Online</option>

<option>Onsite</option>

<option>Both</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Status</label>

<select
class="form-select"
name="status">

<option>Active</option>

<option>Inactive</option>

</select>

</div>

<div class="col-md-12 mb-3">

<label>Description</label>

<textarea
class="form-control"
rows="5"
name="description"></textarea>

</div>
<div class="col-md-12 mb-3">

<label>Short Description</label>

<textarea
class="form-control"
rows="3"
name="short_description"
placeholder="Brief summary displayed on the course card"></textarea>

</div>
<div class="col-md-12 mb-3">

<label>Full Course Description</label>

<textarea
class="form-control"
rows="8"
name="full_description"
placeholder="Explain the course in detail, what students will learn and why they should enroll."></textarea>

</div>
<div class="col-md-12 mb-3">

<label>Learning Outcomes</label>

<textarea
class="form-control"
rows="6"
name="learning_outcomes"
placeholder="Example:

• Linux Administration
• Docker
• Kubernetes
• AWS
• CI/CD
• Git & GitHub"></textarea>

</div>
<div class="col-md-12 mb-3">

<label>Career Opportunities</label>

<textarea
class="form-control"
rows="5"
name="career_opportunities"
placeholder="Example:

DevOps Engineer
Cloud Engineer
AWS Engineer
Platform Engineer
Site Reliability Engineer"></textarea>

</div>
<div class="col-md-6 mb-3">

<label>Prerequisites</label>

<textarea
class="form-control"
rows="4"
name="prerequisites"></textarea>

</div>
<div class="col-md-6 mb-3">

<label>Target Audience</label>

<textarea
class="form-control"
rows="4"
name="target_audience"></textarea>

</div>
<div class="col-md-12 mb-3">

<label>Certificate Information</label>

<textarea
class="form-control"
rows="4"
name="certificate_info"
placeholder="Explain the certificate students will receive after completion."></textarea>

</div>
<div class="col-md-12 mb-3">

<label>Course Demo Video (YouTube URL)</label>

<input
type="url"
class="form-control"
name="demo_video">

</div>

<div class="col-md-12 mb-3">

<label>Course Thumbnail</label>

<input
type="file"
class="form-control"
name="thumbnail"
accept="image/*">

</div>

<div class="col-md-12">

<button
class="btn btn-primary">

<i class="fas fa-save"></i>

Save Course

</button>

</div>

</div>

</form>

</div>

</div>

<?php

require_once '../includes/footer.php';

?>