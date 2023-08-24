<?php 
require('./config.php');
session_start();

// if the "accept request"-button is clicked:
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      
      // Request-status should be changed from "Pending" to "accepted"
      $requestId = $_POST['popupRequestId'];
      $stmt = $conn->prepare("UPDATE requests SET request_status = '1' WHERE id_request = ?");
      $stmt->bind_param("i", $requestId);
      $stmt->execute();

      // Since a request was accepted, a new conversation will be created in the database.
      $responsibleId = $_SESSION['user_id'];
      $stmt = $conn->prepare("INSERT INTO conversations (id_responsible, id_request) VALUES (?,?)");
      $stmt->bind_param("ii", $responsibleId, $requestId);
      $stmt->execute();
    }
?>