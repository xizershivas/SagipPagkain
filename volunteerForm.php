<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Volunteer Application</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="app/css/app.css" rel="stylesheet">
  <link href="app/css/volunteer.css" rel="stylesheet">

</head>
<body>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">

        <img src="assets/img/sagip-pagkain-logo.JPEG" alt="Sagip Logo" oncontextmenu="return false;" draggable="false">
        <div>
          <h2 class="sitename" style="padding-left: 10px;"><b>SAGIP</b><span>.</span></h2>
          <h4 class="sitename subtitle" style="padding-left: -10px; letter-spacing: 10.5px;">PAGKAIN</h4>
        </div>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php#about">
            <div>
              <h6>Our Role in</h6>
              <h5>FOOD SYSTEMS<span></h5>
            </div>
          </a></li>
          <li><a href="index.php#system">
            <div>
              <h6>Our</h6>
              <h5>APPROACH</h5>
            </div>
          </a></li>
          <li><a href="index.php#stats">
            <div>
              <h5>IMPACT</h5>
            </div>
          </a></li>
          <li><a href="index.php#services">
            <div>
              <h6>About</h6>
              <h6>Sagip pagkain</h6>
            </div>
          </a></li>
          <li><a href="index.php#recent-posts">
            <div>
              <h5>COMMUNITY-LED</h5>
            </div>
          </a></li>
          <li><a href="index.php#contact">
            <div>
            <h5>OUR SUPPORT</h5>
          </div>
          </a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="index.php#donate">DONATE</a>

    </div>
  </header>
<main class="main">

<!-- Page Title -->
<div class="page-title" data-aos="fade">
  <div class="heading">
  </div>
  <nav class="breadcrumbs">
    <div class="container-fluid">
     <h4 class="current">Food Bank Volunteer Application Form</h4>
    </div>
  </nav>
</div><!-- End Page Title -->

<section class="container cons">
        <div class="row justify-content-center">
            <div class="col-md-8 form">
                <nav class="mb-2">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link w-50 active" id="nav-personal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal" type="button" role="tab" aria-controls="nav-personal" aria-selected="true">01. Personal</button>
                        <button class="nav-link w-50" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">02. Contact</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <!-- 01. PERSONAL -->
                    <div class="tab-pane fade show active" id="nav-personal" role="tabpanel" aria-labelledby="nav-personal-tab" tabindex="0">
                        <form id="volunteerForm">
                            <label class="form-label mb-1" for="firstname"><strong>Name</strong></label>
                            <div class="row g-2">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="">
                                        <label class="form-label" for="firstname">First Name</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="">
                                        <label class="form-label" for="lastname">Last Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label mb-1" for="male"><strong>Gender</strong></label>
                                            <div class="container">
                                                <div class="row justify-content-start">
                                                    <div class="col-3 col-md-4 col-lg-3 form-check">
                                                        <input class="form-check-input" type="radio" name="gender" id="male" value="M" checked>
                                                        <label class="form-check-label" for="male">
                                                            Male
                                                        </label>
                                                    </div>
                                                    <div class="col-3 col-md-4 col-lg-3 form-check">
                                                        <input class="form-check-input" type="radio" name="gender" id="female" value="F">
                                                        <label class="form-check-label" for="female">
                                                            Female
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label mb-1" for="dateOfBirth"><strong>Date of Birth</strong></label>
                                            <input type="date" class="form-control form-control-lg" name="dateOfBirth" id="dateOfBirth" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <label class="form-label mb-1" for="street"><strong>Address</strong></label>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-lg" name="street" id="street" placeholder="">
                                        <label class="form-label" for="lastname">Street Address</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-lg" name="address" id="address" placeholder="">
                                        <label class="form-label" for="lastname">Street Address Line 2</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="city" id="city" placeholder="">
                                        <label class="form-label" for="city">City/Municipality</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="region" id="region" placeholder="">
                                        <label class="form-label" for="region">Region</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="">
                                        <label class="form-label" for="zipcode">Postal/Zip Code</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="country" id="country" value="Philippines" placeholder="">
                                        <label class="form-label" for="country">Country</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-12 col-sm-12 col-md-8 col-lg-4">
                                    <button type="button" class="btn btn-lg btn-success w-100" id="btnNext">Next</button>
                                </div>
                            </div>
                    </div>
                    <!-- END PERSONAL -->
                    <!-- 02. CONTACT -->
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                    <div class="row my-2">
                        <label class="form-label mb-1" for="contact"><strong>Contact Number</strong></label>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-lg" name="contact" id="contact" placeholder="">
                                    <label class="form-label" for="contact">Tel No. / Mobile No.</label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <label class="form-label mb-1" for="email"><strong>Email Address</strong></label>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-lg" name="email" id="email" placeholder="">
                                    <label class="form-label" for="email">e.g. juan.delacruz@gmail.com</label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-12">
                                <label class="form-label mb-1"><strong>Terms of Volunteering</strong></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the terms of volunteering.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-2">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
                                <label class="form-label" for=""><strong>Select Signature</strong> (Image: JPG/PNG Max: 3MB)</label>
                                <input class="form-control" type="file" name="signature" id="signature" required>
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-12">
                                <label class="form-label" for=""><strong>Verification</strong></label>
                                Captcha
                            </div>
                        </div>
                        <div class="row g-2 justify-content-center">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <button type="button" class="btn btn-lg btn-outline-success w-100" id="btnPrev">Previous</button>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <button type="submit" class="btn btn-lg btn-primary w-100" id="btnApply">Apply</button>
                            </div>
                        </div>
                        <div class="row text-center mt-5">
                            <div class="col-12">
                                <p>Never submit sensitive information such as passwords. <a href="javascript:void(0)">Report abuse</a></p>
                            </div>
                        </div>
                    </form>
                    </div>
                    <!-- END CONTACT -->
                </div>
            </div>
        </div>
    </section>
</main>
<footer id="footer" class="footer position-relative light-background">

<div class="container footer-top">
  <div class="row gy-4">
    <div class="col-lg-5 col-md-12 footer-about">
      <a href="index.php" class="logo d-flex align-items-center">
        <span class="sitename">Sagip pagkain</span>
      </a>
      <p>Dummy footer</p>
      <div class="social-links d-flex mt-4">
        <a href=""><i class="bi bi-twitter-x"></i></a>
        <a href=""><i class="bi bi-facebook"></i></a>
        <a href=""><i class="bi bi-instagram"></i></a>
        <a href=""><i class="bi bi-linkedin"></i></a>
      </div>
    </div>


  </div>
</div>

<div class="container copyright text-center mt-4">
  <p>© <span>Copyright</span> <strong class="sitename">Sagip pagkain</strong> <span>All Rights Reserved</span></p>
  <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you've purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
  </div>
</div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Data Table JS CDN -->
<!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

<!-- Main JS File -->
<script src="app/js/app.js"></script>
<script>
$(document).ready(function() {
  $('#userDataTable').DataTable();
});
</script>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="app/js/volunteerForm.js"></script>
</body>
</html>