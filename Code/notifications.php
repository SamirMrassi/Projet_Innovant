<?php
require('./config.php');
session_start();
?>

<!DOCTYPE html>
<head>
    <title>Notifications</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="notificationstyle.css" />
</head>

<body>
    <header>
        <div class="title">Virtu'Com</div>
        <!-- SI C'EST UN PROJECT MANAGER; METTRE À DISPOSITION UNE PAGE DE CONFIGURATION DE L'ÈQUIPE. -->
        <div class="line"></div>
        <div class="images">
            <a href="acceuil.php"><img class="image" src="ressources/menu.png" title="Menu" alt="Image 2" ></a>
            <a href="index.php"><img class="image" src="ressources/deconnexion.png" title="Se déconnecter" alt="Image 1" ></a>
        </div>  
    </header>
  <?php
    // Simulating fetching demands from the database
    $role_id = $_SESSION['role_id'];
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT r.request_status, r.id_request, r.description, u.id_role, u.firstname_user, u.lastname_user
                              FROM requests AS r
                              JOIN users AS u ON r.id_user = u.id_user
                              WHERE r.request_status = 2 AND r.id_role = ? AND r.id_user != ?");
    $stmt->bind_param("ii", $role_id, $user_id);
    $stmt->execute();
    $requests_list = $stmt->get_result();

    // fetching the roles table (to display role_name in each ticket)
    $stmt = $conn->prepare("SELECT id_role, name_role FROM roles");
    $stmt->execute();
    $roles_list = $stmt->get_result();

    // if the "accept request"-button is clicked:
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $requestId = $_POST['id'];

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
    <div class="grid">
      <?php foreach ($requests_list as $request):
                $rolename = $request['id_role'];
                foreach($roles_list as $role){
                    if ($role['id_role'] == $request['id_role']){
                        $rolename = $role['name_role']; break;
                    }
                }
        ?>
        <div class="request">
            <div class="request-id"><?php echo "Ticket-ID : " . $request['id_request']; ?></div>
            <div class="username"><?php echo "Provenant de : " . $request['firstname_user'] . " " . $request['lastname_user'] . " ( " . $rolename . " )" ?></div>
            <div class="text"><?php echo $request['description']; ?></div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Your existing code -->

    <!-- Hidden HTML structure for the popup content -->
    <div id="popup-container" class="popup-container">
      <div class="popup-content">
        <span class="popup-close" id="popup-close">&times;</span>
        <div class="popup-request-id" id="popup-request-id"></div>
        <div class="popup-username" id="popup-username"></div>
        <div class="popup-text" id="popup-text"></div>
        <button class="accept-button" id="accept-button">Accept request</button>
      </div>
    </div>

    <script>
         let popupRequestId;
      // Function to open the popup and populate its content
      function openPopup(request) {
        const popupContainer = document.getElementById('popup-container');
        popupRequestId = document.getElementById('popup-request-id');
        const popupUsername = document.getElementById('popup-username');
        const popupText = document.getElementById('popup-text');

        popupRequestId.textContent = request.id;
        popupUsername.textContent = request.username;
        popupText.textContent = request.text;

        popupContainer.style.display = 'block';
      }

      // Event listeners for request squares
      const requestSquares = document.querySelectorAll('.request');
      requestSquares.forEach(square => {
        square.addEventListener('click', () => {
          const request = {
            id: square.querySelector('.request-id').textContent,
            username: square.querySelector('.username').textContent,
            text: square.querySelector('.text').textContent
          };
          openPopup(request);
        });
      });

      // Event listener for popup close button
      const popupClose = document.getElementById('popup-close');
      popupClose.addEventListener('click', () => {
        const popupContainer = document.getElementById('popup-container');
        popupContainer.style.display = 'none';
      });

      // Event listener for "Accept request" button
      const acceptButton = document.getElementById('accept-button');
      acceptButton.addEventListener('click', () => {
        // Add your logic to handle accepting the request here
        const popupContainer = document.getElementById('popup-container');
        popupContainer.style.display = 'none';
        const request = {
          id: popupRequestId,
          // ...
        };

        // Send AJAX request to update_request.php
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            if (xhr.responseText === 'success') {
              console.log('Request accepted and updated in the database.');
              const popupContainer = document.getElementById('popup-container');
              popupContainer.style.display = 'none';
            } else {
              console.error('Error updating request in the database.');
            }
          }
        };
        xhr.send(request);

      });


    </script>

</body>
</html>


</body>
</html>

</body>
</html>
