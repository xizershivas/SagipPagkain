<?php

session_start();

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
<!-- Wrap both dropdowns in a flex container -->
<div class="d-flex align-items-center gap-3">
<?php

                $notifications = [];

                if (isset($_SESSION["intUserId"])) {

                  $userData = getUser($conn, $_SESSION["intUserId"]);

                  $userInfo = $userData->fetch_object();
 
                  // Fetch unread notifications

                  $stmt = $conn->prepare("WITH RankedUsers AS (

                          SELECT 

                            TTD.intFoodBankId,

                            TU.strFullName,

                            ROW_NUMBER() OVER (PARTITION BY TTD.intFoodBankId ORDER BY TTD.intTrackDonationId) AS rn

                          FROM tbltrackdonation TTD

                          JOIN tbluser TU ON TTD.intUserId = TU.intUserId

                        )
 
                        SELECT  TI.strItem,  TFB.strFoodBank, RU.strFullName FROM tblnotification TN

                        INNER JOIN tblinventory TINV ON TN.intSourceId = TINV.intDonationId

                        INNER JOIN tblitem TI ON TINV.intItemId = TI.intItemId

                        INNER JOIN tblfoodbank TFB ON TINV.intFoodbankId = TFB.intFoodBankId

                        LEFT JOIN RankedUsers RU ON TFB.intFoodBankId = RU.intFoodBankId AND RU.rn = 1

                        WHERE TN.ysnSeen = 0 AND TN.strSourceTable = 'tblinventory'

                        ORDER BY TN.dtmCreatedDate DESC LIMIT 5;");

                  $stmt->execute();

                  $result = $stmt->get_result();
 
                  $stmt2 = $conn->prepare("SELECT COUNT(*) AS unreadCount FROM tblnotification TN WHERE TN.ysnSeen = 0");

                  $stmt2->execute();

                  $result2 = $stmt2->get_result();

                  $row = $result2->fetch_assoc();

                  $unreadCount = $row['unreadCount'];
 
 
                  while ($row = $result->fetch_assoc()) {

                    $notifications[] = $row;

                  }

                }

               ?>
 
             <?php if ($userInfo->intUserId == 1): ?>
<!-- Notification Bell Dropdown -->
<div class="dropdown">
<button class="position-relative border-0 bg-transparent" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fas fa-bell text-white"></i>
<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
<?= $unreadCount ?><span class="visually-hidden">unread messages</span>
</span>
</button>
<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
<li class="dropdown-header">Notifications</li>
<?php if (count($notifications) === 0): ?>
<li><span class="dropdown-item text-muted">No new notifications</span></li>
<?php else: ?>
<?php foreach ($notifications as $notif): ?>
<li>
<a class="dropdown-item text-wrap" href="#" style="white-space: normal;">

                            On <?= htmlspecialchars($notif['strFoodBank']) ?>  <?= htmlspecialchars($notif['strFullName']) ?> donated <?= $notif['strItem'] ?>.
</a>
</li>
<?php endforeach; ?>
<?php endif; ?>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item text-center text-primary" href="#">View All</a></li>
</ul>
</div>
<?php endif; ?>
<!-- Profile Dropdown -->
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
 
    </div>
</header>
 