<?php 
require('./config.php');
session_start();

// if the "accept request"-button is clicked:
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $requestId = $_POST['popupRequestId'];
      //$requestId = 65;
      // Update query
      $stmt = $conn->prepare("UPDATE requests SET request_status = '1' WHERE id_request = ?");
      $stmt->bind_param("i", $requestId);
      if ($stmt->execute()) {
        echo "success";
      } else {
        echo "error";
      }
    }

?>