<?php
include "../../../app/functions/profile.php";
$userInfo;
if (isset($_SESSION["intUserId"])) {
  $userData = getUser($conn, $_SESSION["intUserId"]);
  $userInfo = $userData->fetch_object();
}
?>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="../../../index.php" class="logo d-flex align-items-center me-auto me-xl-0">

        <img src="../../../assets/img/sagiplogo.png" alt="Sagip Logo" oncontextmenu="return false;" draggable="false">
        <div>
          <h2 class="sitename" style="padding-left: 10px;"><b>SAGIP</b><span>.</span></h2>
          <h4 class="sitename subtitle" style="padding-left: -10px; letter-spacing: 10.5px;">PAGKAIN</h4>
        </div>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="../../../index.php#about">
            <div>
            </div>
          </a></li>
          <li><a href="../../../index.php#system">
            <div>
            </div>
          </a></li>
          <li><a href="../../../index.php#stats">
            <div>
            </div>
          </a></li>
          <li><a href="../../../index.php#services">
            <div>
            </div>
          </a></li>
          <li><a href="../../../index.php#recent-posts">
            <div>
            </div>
          </a></li>
          <li><a href="../../../index.php#contact">
            <div>
          </div>
          </a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

            <div class="dropdown">
                <button class="profile-btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li class="dropdown-header">Hi!,&nbsp;
                    <?php
                      if (isset($_SESSION["intUserId"])) {
                        echo strtoupper($userInfo->strUsername);
                      }
                    ?>
                    </li>
                    <li><a class="dropdown-item" href="../app/profile.php">See profile details</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="window.location.href='../forms/logout.php'">Logout</a></li>
                </ul>
            </div>

      </div>

    </div>
  </header>