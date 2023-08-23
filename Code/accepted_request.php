<?php 
require('./config.php');
session_start();

// if the "accept request"-button is clicked:
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $requestId = $_POST['popupRequestId'];

      $stmt = $conn->prepare("UPDATE requests SET request_status = '1' WHERE id_request = ?");
      $stmt->bind_param("i", $requestId);
      $stmt->execute();


      $responsibleId = $_SESSION['user_id'];
      $stmt = $conn->prepare("INSERT INTO conversations (id_responsible, id_request) VALUES (?,?)");
      $stmt->bind_param("ii", $responsibleId, $requestId);
      $stmt->execute();
    }

?>