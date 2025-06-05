<?php
include "../../../app/functions/profile.php";
$userInfo = null;

if (isset($_SESSION["intUserId"])) {
  $userData = getUser($conn, $_SESSION["intUserId"]);
  $userInfo = $userData->fetch_object();
}
?>

<header id="header" class="header d-flex fixed-top">
  <div class="container-fluid position-relative d-flex justify-content-between">
    <a href="../../../index.php" class="logo d-flex me-auto me-xl-0">
      <img src="../../../assets/img/sagiplogo.png" alt="Sagip Logo" oncontextmenu="return false;" draggable="false">
      <div>
        <h2 class="sitename" style="padding-left: 10px;"><b>SAGIP</b><span>.</span></h2>
        <h4 class="sitename subtitle" style="letter-spacing: 10.5px;">PAGKAIN</h4>
      </div>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="../../../index.php#about"></a></li>
        <li><a href="../../../index.php#system"></a></li>
        <li><a href="../../../index.php#stats"></a></li>
        <li><a href="../../../index.php#services"></a></li>
        <li><a href="../../../index.php#recent-posts"></a></li>
        <li><a href="../../../index.php#contact"></a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <div class="d-flex align-items-center gap-3">
      <?php
      $notifications = [];
      $unreadCount = 0;
      if (isset($_SESSION["intUserId"])) {
        $stmt = $conn->prepare("
          SELECT TN.intNotificationId, TI.strItem, TFBD.strFoodBankName 
        FROM tblnotification TN
        INNER JOIN tblinventory TINV ON TN.intSourceId = TINV.intDonationId
        INNER JOIN tblitem TI ON TINV.intItemId = TI.intItemId
        INNER JOIN tblfoodbankdetail TFBD ON TINV.intFoodBankDetailId = TFBD.intFoodBankDetailId
        WHERE TN.ysnSeen = 0 AND TN.strSourceTable = 'tblinventory'
        ORDER BY TN.dtmCreatedDate DESC LIMIT 5;");
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt2 = $conn->prepare("SELECT COUNT(*) AS unreadCount FROM tblnotification WHERE ysnSeen = 0");
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row = $result2->fetch_assoc();
        $unreadCount = $row['unreadCount'];

        while ($row = $result->fetch_assoc()) {
          $notifications[] = $row;
        }
      }
      ?>

      <?php if ($userInfo && $userInfo->intUserId == 1): ?>
      <div class="dropdown">
        <button class="btn btn-link position-relative dropdown-toggle text-white" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-bell"></i>
          <?php if ($unreadCount > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?= $unreadCount ?>
            </span>
          <?php endif; ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px;">
          <li class="dropdown-header">Notifications</li>
          <?php if (empty($notifications)): ?>
            <li><span class="dropdown-item text-muted">No new notifications</span></li>
          <?php else: ?>
            <?php foreach ($notifications as $notif): ?>
              <li>
                <a href="#" class="dropdown-item text-wrap show-notif-modal"
                   data-foodbank="<?= htmlspecialchars($notif['strFoodBankName']) ?>"
                   data-id="<?= htmlspecialchars($notif['intNotificationId']) ?>"
                   data-item="<?= htmlspecialchars($notif['strItem']) ?>">
                  On <b><?= htmlspecialchars($notif['strFoodBankName']) ?></b> </b> someone donated <b><?= htmlspecialchars($notif['strItem']) ?></b>.
                </a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-center text-primary" href="#">View All</a></li>
        </ul>
      </div>
      <?php endif; ?>

      <div class="dropdown">
        <button class="btn btn-link dropdown-toggle profile-btn text-white" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-user"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
          <li class="dropdown-header">Hi!, <?= isset($userInfo) ? strtoupper($userInfo->strUsername) : '' ?></li>
          <li><a class="dropdown-item" href="../app/profile.php">See profile details</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="window.location.href='../forms/logout.php'">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</header>

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-black">
      <div class="modal-header">
        <h5 class="modal-title text-black" id="notificationModalLabel">Donation Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="notificationModalBody">
        <!-- filled dynamically -->
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
  const notifLinks = document.querySelectorAll('.show-notif-modal');
  const modalBody = document.getElementById('notificationModalBody');

  notifLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      const foodbank = this.dataset.foodbank;
      const item = this.dataset.item;
      const notifId = this.dataset.id;

      // Update notification as seen via AJAX
      fetch('../../../app/functions/update_notification.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notification_id=${notifId}`
      }).then(res => res.json())
        .then(data => {
          if (data.success) {
            // Optionally update the badge count here
          }
        });

      modalBody.innerHTML = `
        <p><strong>Food Bank:</strong> ${foodbank}</p>
        <p><strong>Item Donated:</strong> ${item}</p>
      `;

      const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
      modal.show();
    });
  });
});

</script>
