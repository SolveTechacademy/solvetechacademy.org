<?php

require_once 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location: courses.php");
    exit();
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id=? AND status='Active'");

$stmt->execute([$id]);

$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header("Location: courses.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SolveTech : Online Courses</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/stasmss.ico" rel="icon"
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <p class="m-0 fw-bold" style="font-size: 25px;"><img src="img/solvetechacademy.png" alt="" height="50px"><span
                    style="color: #fb873f;"></span></p>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
            style="border: none;">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="index.html" class="nav-item nav-link active">Home</a>
                <a href="about.html" class="nav-item nav-link">About</a>
                <a href="courses.html" class="nav-item nav-link">Courses</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="team.html" class="dropdown-item">Our Team</a>
                        <a href="testimonial.html" class="dropdown-item">Testimonial</a>

                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link">Contact</a>
                <a href="login.php" class="nav-item nav-link"><i class="fa fa-user"></i></a>
                <a href="#" class="nav-item nav-link">
                    <div id="google_translate_element"></div>
                </a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Courses</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="index.html">Home</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Courses</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
     <!-- Course Details Start -->
<div class="container-xxl py-5">
    <div class="container">

        <div class="row g-5">

            <div class="col-lg-5">

                <img
                    <img
src="<?= !empty($course['thumbnail']) ? 'assets/uploads/courses/' . $course['thumbnail'] : 'img/course-default.jpg'; ?>"
class="img-fluid rounded shadow w-100"
alt="<?= htmlspecialchars($course['course_title']); ?>">

            </div>

            <div class="col-lg-7">

                <h2 class="mb-3">
                    <?= htmlspecialchars($course['course_title']); ?>
                </h2>

                <p>
                    <strong>Category:</strong>
                    <?= htmlspecialchars($course['category']); ?>
                </p>

                <p>
                    <strong>Instructor:</strong>
                    <?= htmlspecialchars($course['instructor']); ?>
                </p>

                <p>
                    <strong>Duration:</strong>
                    <?= htmlspecialchars($course['duration']); ?>
                </p>

                <p>
                    <strong>Level:</strong>
                    <?= htmlspecialchars($course['level']); ?>
                </p>

                <p>
                    <strong>Mode:</strong>
                    <?= htmlspecialchars($course['mode']); ?>
                </p>

                <p>
                    <strong>Price:</strong>
                    <?= number_format($course['price']); ?> FCFA
                </p>

                <a
                    href="register.php?course=<?= $course['id']; ?>"
                    class="btn btn-primary btn-lg">

                    Enroll Now

                </a>

            </div>

        </div>

    </div>
</div>
<!-- Course Details End -->
 <!-- About Course Start -->
<div class="container-xxl pb-5">

    <div class="container">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h3 class="mb-4">
                    About This Course
                </h3>

                <p style="line-height:30px; text-align:justify;">

                    <?= nl2br(htmlspecialchars($course['full_description'])); ?>

                </p>

            </div>

        </div>

    </div>

</div>
<!-- About Course End -->
 <!-- Learning Outcomes Start -->
<div class="container-xxl pb-5">

    <div class="container">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h3 class="mb-4">
                    What You'll Learn
                </h3>

                <?php
                $outcomes = explode("\n", $course['learning_outcomes']);
                ?>

                <ul class="list-group list-group-flush">

                    <?php foreach ($outcomes as $outcome): ?>

                        <?php if(trim($outcome) != ""): ?>

                            <li class="list-group-item">

                                <i class="fa fa-check-circle text-success me-2"></i>

                                <?= htmlspecialchars(trim($outcome)); ?>

                            </li>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>
<!-- Learning Outcomes End -->
 <!-- Career Opportunities Start -->
<div class="container-xxl pb-5">

    <div class="container">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h3 class="mb-4">
                    Career Opportunities
                </h3>

                <?php
                $careers = explode("\n", $course['career_opportunities']);
                ?>

                <ul class="list-group list-group-flush">

                    <?php foreach ($careers as $career): ?>

                        <?php if(trim($career) != ""): ?>

                            <li class="list-group-item">

                                <i class="fa fa-briefcase text-primary me-2"></i>

                                <?= htmlspecialchars(trim($career)); ?>

                            </li>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>
<!-- Career Opportunities End -->
 <!-- Prerequisites Start -->
<div class="container-xxl pb-5">

    <div class="container">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h3 class="mb-4">
                    Prerequisites
                </h3>

                <?php
                $prerequisites = explode("\n", $course['prerequisites']);
                ?>

                <ul class="list-group list-group-flush">

                    <?php foreach ($prerequisites as $item): ?>

                        <?php if(trim($item) != ""): ?>

                            <li class="list-group-item">

                                <i class="fa fa-angle-right text-warning me-2"></i>

                                <?= htmlspecialchars(trim($item)); ?>

                            </li>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>
<!-- Prerequisites End -->
 <!-- Target Audience Start -->
<div class="container-xxl pb-5">

    <div class="container">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <h3 class="mb-4">
                    Who Should Take This Course?
                </h3>

                <?php
                $audience = explode("\n", $course['target_audience']);
                ?>

                <ul class="list-group list-group-flush">

                    <?php foreach ($audience as $person): ?>

                        <?php if(trim($person) != ""): ?>

                            <li class="list-group-item">

                                <i class="fa fa-user-graduate text-info me-2"></i>

                                <?= htmlspecialchars(trim($person)); ?>

                            </li>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>
<!-- Target Audience End -->
 <!-- Certificate Information Start -->
<div class="container-xxl pb-5">

    <div class="container">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h3 class="mb-4">

                    <i class="fa fa-certificate text-warning me-2"></i>

                    Certificate Information

                </h3>

                <p style="line-height:30px; text-align:justify;">

                    <?= nl2br(htmlspecialchars($course['certificate_info'])); ?>

                </p>

            </div>

        </div>

    </div>

</div>
<!-- Certificate Information End -->
 <!-- Demo Video Start -->
<?php if (!empty($course['demo_video'])): ?>

<div class="container-xxl pb-5">

    <div class="container">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h3 class="mb-4">

                    <i class="fa fa-play-circle text-danger me-2"></i>

                    Course Preview

                </h3>

                <?php

                $video = trim($course['demo_video']);

                // Convert normal YouTube URL to embed URL
                if (strpos($video, "watch?v=") !== false) {

                    $video = str_replace("watch?v=", "embed/", $video);

                }

                ?>

                <div class="ratio ratio-16x9">

                    <iframe
                        src="<?= htmlspecialchars($video); ?>"
                        title="Course Preview"
                        allowfullscreen>
                    </iframe>

                </div>

            </div>

        </div>

    </div>

</div>

<?php endif; ?>
<!-- Demo Video End -->

     <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-white mb-3">Quick Link</h4>
                    <p><a class="text-light" href="about.html">About Us</a></p>
                    <p><a class="text-light" href="contact.php">Contact Us</a></p>
                    <p><a class="text-light" href="">Privacy Policy</a></p>
                    <p><a class="text-light" href="">Terms & Condition</a></p>
                    <p><a class="text-light" href="">FAQs & Help</a></p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Wilmington, DE, United States, 19802</p>
                    <!-- <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+1 856-689-7776</p> -->
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@solvetechacademy.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href="https://www.X.com/@solvetech_a"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/profile.php?id=100077896834045"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://www.youtube.com/@solvetechacademy1555"><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://www.linkedin.com/in/nkam-valery-8a835625a/"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h4 class="text-white mb-3">Subscribe to our Newsletter</h4>
                    <p>Subscribe now and join our growing community of learners committed to lifelong education! </p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <form action="#">
                            <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="email"
                                placeholder="Your email" required>
                            <button type="submit"
                                class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="index.html">SolveTech</a>, All Right Reserved.

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>