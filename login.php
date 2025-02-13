<?php
session_start();

if (isset($_SESSION["intUserId"]) && $_SESSION["ysnAdmin"] == 1) {
  header("Location: dashboard.php");
  exit();
} else if (isset($_SESSION["intUserId"]) && $_SESSION["ysnAdmin"] == 0) {
  header("Location: index.php");
  exit();
}
?>
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
  <link href="app/css/login.css" rel="stylesheet">

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
              <h5><span>FOOD SYSTEMS</span></h5>
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
        <div class="login-container">
            <div class="login-box">
                <div class="login-img"></div>
                <div class="login-form">
                    <h3 class="mb-3 signin">Sign In</h3>
                    <form class="was-validated" id="frmLogin">
                        <div class="mb-3">
                            <label class="form-label" >Username</label>
                            <input class="form-control" type="text" name="username" id="username" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label class="form-label">Login As</label>
                            <select class="form-select" id="userRole" onchange="UserSelectionChanged()">
                                <option value="admin">Admin</option>
                                <option value="donor">Donor</option>
                            </select>
                        </div> -->
                        <button type="submit" class="btn btn-warning w-100" id="btnSignIn">Sign In</button>
                        <div class="d-flex justify-content-between mt-2">
                            <div>
                                <input type="checkbox"> Remember Me
                            </div>
                            <a href="#">Forgot Password?</a>
                        </div>
                        <div class="text-center mt-3">
                            Not a member? <a href="signup.php">Sign Up</a>
                        </div>
                    </form>
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
        <a href="./dashboard.php"><i class="bi bi-twitter-x"></i></a>
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
    <script src="app/js/login.js"></script>
    <!-- <script>

      // function UserSelectionChanged(){
      //   var getValue = document.getElementById("userRole").value;

      //   if(getValue === "admin"){
      //     $(".signin").html("Login as Admin");
      //   }else{
      //     $(".signin").html("Login as Donor");
      //   }
      // }

    // document.getElementById("signIn").onclick = function () {
    //     var getUname = document.getElementById("uname").value;
    //     var getPword = document.getElementById("pword").value;

    //     if(getUname === "admin" && getPword === "admin")
    //     {
    //       alert("Login success");
    //       document.body.innerHTML += ``;
    //       location.href = "./dashboard.php";
    //     }else
    //     {
    //       alert("Login fail");
    //     document.body.innerHTML += ``;
    //     }
    // };
    </script> -->
</body>
</html>