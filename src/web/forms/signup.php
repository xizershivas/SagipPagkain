<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - Sign Up</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="../../../assets/img/favicon.png" rel="icon">
  <link href="../../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Data Table CSS CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
  <!-- <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

  <!-- Main CSS File -->
  <link href="../../../app/css/app.css" rel="stylesheet">
  <link href="../../../app/css/signup.css" rel="stylesheet">

</head>
<body>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="../../../index.php" class="logo d-flex align-items-center me-auto me-xl-0">

        <img src="../../../assets/img/sagip-pagkain-logo.JPEG" alt="Sagip Logo" oncontextmenu="return false;" draggable="false">
        <div>
          <h2 class="sitename" style="padding-left: 10px;"><b>SAGIP</b><span>.</span></h2>
          <h4 class="sitename subtitle" style="padding-left: -10px; letter-spacing: 10.5px;">PAGKAIN</h4>
        </div>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="../../../index.php#about">
            <div>
              <h6>Our Role in</h6>
              <h5><span>FOOD SYSTEMS</span></h5>
            </div>
          </a></li>
          <li><a href="../../../index.php#system">
            <div>
              <h6>Our</h6>
              <h5>APPROACH</h5>
            </div>
          </a></li>
          <li><a href="../../../index.php#stats">
            <div>
              <h5>IMPACT</h5>
            </div>
          </a></li>
          <li><a href="../../../index.php#services">
            <div>
              <h6>About</h6>
              <h6>Sagip pagkain</h6>
            </div>
          </a></li>
          <li><a href="../../../index.php#recent-posts">
            <div>
              <h5>COMMUNITY-LED</h5>
            </div>
          </a></li>
          <li><a href="../../../index.php#contact">
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

<!-- Page Title -->
<div class="page-title" data-aos="fade">
  <div class="heading pb-0">
  </div>
</div><!-- End Page Title -->

<main class="main mt-5">
  <section class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 form">
        <h1 class="signup-heading">Sign Up</h1>
        <form class="row g-3 needs-validation" id="frmSignUp" novalidate>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="fullname">Full Name</label>
            <div class="input-group">
              <span class="input-group-text signup-form-icon" id="addon-fullname"><i class="bi bi-person-vcard-fill"></i></span>
              <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full Name" aria-label="fullname" aria-describedby="addon-fullname" required>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="email">Email</label>
            <div class="input-group">
              <span class="input-group-text signup-form-icon" id="addon-email"><i class="bi bi-envelope-at-fill"></i></span>
              <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" aria-label="email" aria-describedby="addon-email">
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="contact">Contact</label>
            <div class="input-group">
              <span class="input-group-text signup-form-icon" id="addon-contact"><i class="bi bi-telephone-fill"></i></span>
              <input type="text" class="form-control" name="contact" id="contact" placeholder="Contact Number" aria-label="contact" aria-describedby="addon-contact">
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="username">Username</label>
            <div class="input-group">
              <span class="input-group-text signup-form-icon" id="addon-username"><i class="bi bi-person-circle"></i></span>
              <input type="text" class="form-control" name="username" id="username" placeholder="Username" aria-label="username" aria-describedby="addon-username" required>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="password">Password</label>
            <div class="input-group">
              <span class="input-group-text signup-form-icon" id="addon-password"><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" aria-label="password" aria-describedby="addon-password" required>
              <span class="input-group-text signup-form-icon"><i class="bi bi-eye-fill show-hide-password" id="eyePassword"></i></span>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="confirmPassword">Confirm Password</label>
            <div class="input-group">
              <span class="input-group-text signup-form-icon" id="addon-confirm-password"><i class="bi bi-lock-fill"></i></span>
              <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" aria-label="confirm-password" aria-describedby="addon-confirm-password" required>
              <span class="input-group-text signup-form-icon"><i class="bi bi-eye-fill show-hide-password"></i></span>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold signup-form-label" for="accountType">Account Type</label>
            <select class="form-select" aria-label="Large select example" name="accountType" id="accountType" required>
              <option selected disabled value="">-- Select account type --</option>
              <option value="donor">Donor</option>
              <option value="partner">Partner (NGO, Cooperative, Youth Org.)</option>
            </select>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100" id="btnSignUp">Submit</button>
          </div>
        </form>
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
<script src="../../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/vendor/php-email-form/validate.js"></script>
<script src="../../../assets/vendor/aos/aos.js"></script>
<script src="../../../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../../../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../../../assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="../../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="../../../assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Data Table JS CDN -->
<!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

<!-- Main JS File -->
<script src="../../../app/js/app.js"></script>
<script src="../../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../../app/js/formValidation.js"></script>
<script src="../../../app/js/signup.js"></script>
</body>
</html>