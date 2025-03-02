<?php
session_start();
include "../../../app/config/db_connection.php";
include "../../../app/functions/manageBeneficiary.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sagip Pagkain - User Profile</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <style>
        .profile-container {
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background: white;
            text-align: center;
            color: black;
        }
        .profile-container h2{
            color: #3e3f40;
        }
        .profile-container h3{
            color: #332;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .tabs {
            margin-top: 20px;
           
        }
        .tab-pane{
            padding: 40px 20%
        }
        tab-pane 
        .form-control:read-only {
            background-color: #e9ecef;
            opacity: 1;
        }
        .form-control {
            width: 50%;
            margin: auto;
        }
    </style>

 <!-- Include global stylesheet -->
 <?php include '../global/stylesheet.php'; ?>

  <!-- Data Table CSS CDN -->
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" /> -->
  <link href="../../../app/css/manageBeneficiary.css" rel="stylesheet">
</head>

<body class="services-details-page">

<!-- Include Header -->
<?php include '../global/header.php'; ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
      <div class="heading">
        
      </div>
      <nav class="breadcrumbs">
        <div class="container-fluid">
          <ol>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="current">User Profile</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

      <div class="container-fluid">

        <div class="row gy-5">

          <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">

            <div class="service-box">
              <h4>Services List</h4>
              <div class="services-list">
                <a href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
                <a href="user.php"><i class="bi bi-person-gear"></i><span>User Management</span></a>
                <a href="donationManagement.php"><i class="bi bi-hand-thumbs-up"></i><span>Donation Management</span></a>
                <a href="volunteerManagement.php"><i class="bi bi-people"></i><span>Volunteer Management</span></a>
                <a href="foodBankCenter.php"><i class="bi bi-basket-fill"></i><span>Food Bank Center</span></a>
                <a href="dataAnalysisReport.php"><i class="bi bi-pie-chart-fill"></i><span>Data Analysis And Reporting</span></a>
                <a href="findFood.php"><i class="bi bi-box-seam"></i><span>Request Food</span></a>
                <a href="manageBeneficiary.php"><i class="bi bi-person-heart"></i><span>Manage Beneficiaries</span></a>
                <a href="inventoryManagement.php"><i class="bi bi-clipboard-data"></i><span>Inventory Management</span></a>
              </div>
            </div><!-- End Services List -->

            <div class="help-box d-flex flex-column justify-content-center align-items-center">
              <i class="bi bi-headset help-icon"></i>
              <h4>Have a Question?</h4>
              <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
              <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
            </div>
          </div>

  
          <div class="col-lg-9 tbl table-donor pe-2" data-aos="fade-up" data-aos-delay="200">
            <div class="profile-container">
                <img src="../../../assets/img/profile/default-profile-img.jpeg" alt="Profile Image" class="profile-img">
                <h3>Welcome</h3>
                <h2>Jason</h2>
                <p>Admin User</p>
                
                <ul class="nav nav-tabs tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="posts-tab" data-bs-toggle="tab" data-bs-target="#posts" type="button" role="tab">Account Details</button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="profileTabsContent">
                    <div class="tab-pane fade show active" id="posts" role="tabpanel">
                    <form>
                            <div class="mb-2">
                                <label for="userName" class="form-label">Username</label>
                                <input type="text" class="form-control" id="userName" value="Jason123" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" value="Jason Smith" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact" value="+1234567890" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="jason@example.com" readonly>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
       
          </div>
          
        </div>
      </div>
    </section><!-- /Service Details Section -->

  </main>

  <!-- Include global footer  -->
  <?php include '../global/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>


    <!-- Include global JS -->
    <?php include '../global/script.php'; ?>

  <!-- Data Table JS CDN -->
   <!-- <script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script> -->
   <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
   <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

</body>

</html>