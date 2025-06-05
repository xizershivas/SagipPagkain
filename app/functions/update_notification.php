<?php
include "../config/db_connection.php"; 
ob_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $notificationId = $_POST['notification_id'] ?? null;

  if ($notificationId) {
    $stmt = $conn->prepare("UPDATE tblnotification SET ysnSeen = 1 WHERE intNotificationId = ?");
    $stmt->bind_param("i", $notificationId);
    $stmt->execute();

    echo json_encode(["success" => true]);
    exit;
  }
}

echo json_encode(["success" => false]);
ob_end_flush();
?>
