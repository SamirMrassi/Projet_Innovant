<!-- Page of notifications : Displays all the "demandes" that aren't accepted, and every "demande" in a popup-screen by click-->
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
        
			<div class="user_information">
				<?php
					echo ' <span class = "text" style ="color: white; font-weight: bold;"> '. $_SESSION['firstname']. ' ' . $_SESSION['lastname']. ' (' . $_SESSION['name_role']. ') </span>';
				?>
        	</div>
			
        <div class="line"></div>
        <div class="images">
            <a href="acceuil.php"><img class="image"src="ressources/menu.png" title="menu" alt="Image 1" > </a>
            <a href="index.php"><img class="image" src="ressources/deconnexion.png" title="Se déconnecter" alt="Image 1" ></a>        
        </div>  
    </header>
    <?php
        // check if the user is available right-now
        $stmt = $conn->prepare("SELECT DATE_FORMAT(start_date, '%Y-%m-%d') as start_date, DATE_FORMAT(end_date, '%Y-%m-%d') as end_date FROM users 
                                WHERE id_user=?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $dates_list = $result->fetch_assoc();

        // fetching the "demandes" from the database
        $role_id = $_SESSION['role_id'];
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT r.object_text, r.request_status, r.id_request, r.description, u.id_role, u.firstname_user, u.lastname_user
                              FROM requests AS r
                              JOIN users AS u ON r.id_user = u.id_user
                              WHERE r.request_status = 2 AND r.id_role = ? AND r.id_user != ?");
        $stmt->bind_param("ii", $role_id, $user_id);
        $stmt->execute();
        $requests_list = $stmt->get_result();

        // fetching the "roles"-table (to display role_name in each ticket)
        $stmt = $conn->prepare("SELECT id_role, name_role FROM roles");
        $stmt->execute();
        $roles_list = $stmt->get_result();

        $today = new DateTime();
        $startDate = new DateTime($dates_list["start_date"]);
        $endDate = new DateTime($dates_list["end_date"]);
        if ($today >= $startDate && $today <= $endDate) {
            echo 
                '<div class="div_no_requests" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;">
                <span class="no_requests" style="color: black; font-weight: bold; font-size: 20px; margin-bottom: 20px;">
                    Profitez de vos vacances!
                </span>
                <img class="image" src="ressources/vacances.png" title="Vous êtes en vacances :)" alt="Image 1" style="width: 50%; height: 60%;">
            </div>';
        } else if($requests_list->num_rows == 0){
            echo 
                '<div class="div_no_requests" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;">
                    <span class="no_requests" style="color: black; font-weight: bold; font-size: 20px; margin-bottom: 20px;">
                        Aucune notification à afficher !
                    </span>
                    <img class="image" src="ressources/nothing.jpg" title="Se déconnecter" alt="Image 1" style="width: 50%; height: 60%;">
                </div>';
        }else{
            echo '<div class="grid">';
            foreach ($requests_list as $request):
                $rolename = $request['id_role'];
                foreach($roles_list as $role){
                    if ($role['id_role'] == $request['id_role']){
                        $rolename = $role['name_role']; break;
                    }
                }
            
        ?>
            <div class="request">   
                <div class="request-box">
                    <div class="request-id-title"><?php echo "Demande Nr. "; ?></div>
                    <div class="request-id"><?php echo $request['id_request']; ?></div>
                </div>
                <div class="username"><?php echo "Provenant de : " . $request['firstname_user'] . " " . $request['lastname_user'] . " ( " . $rolename . " )" ?></div>
                <div class="object"><?php echo "<strong>Objet : </strong>" . $request['object_text']; ?></div>
                <div class="invisible_div" style="display:none;"><?php echo ""  . $request['description']; ?></div> 
            </div>
      <?php endforeach; }?>
    </div>


    <!-- Hidden HTML structure for the popup content, that will only be executed when we click on a "demande" -->
    <div id="popup-container" class="popup-container">
      <div class="popup-content">
        <span class="popup-close" id="popup-close">&times;</span>
        <div class="popup-request-id" id="popup-request-id"></div>
        <div class="popup-username" id="popup-username"></div>
        <div class="popup-object" id="popup-object"></div>
        <div class="popup-text" id="popup-text"></div>
        <button class="accept-button" id="accept-button">Accept request</button>
      </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popupContainer = document.getElementById('popup-container');
            const popupRequestId = document.getElementById('popup-request-id');
            const popupUsername = document.getElementById('popup-username');
            const popupObject = document.getElementById('popup-object');
            const popupText = document.getElementById('popup-text');      
            let selectedRequest = null;

            // A function that reaches the css-variable "display" and change it from "none" to "block" in order to make the popup appear. 
            function openPopup(request) {
                popupRequestId.textContent = request.id;
                popupUsername.textContent = request.username;  
                popupObject.textContent = request.object;
                popupText.textContent = request.text;
                popupContainer.style.display = 'block';
            }

            // Event listeners in every "demande" 
            const requestSquares = document.querySelectorAll('.request');
            requestSquares.forEach(square => {
                square.addEventListener('click', () => {
                  selectedRequest  = {
                    id: square.querySelector('.request-id').textContent,
                    username: square.querySelector('.username').textContent,
                    object: square.querySelector('.object').textContent,
                    text: square.querySelector('.invisible_div').textContent
                  };
                  openPopup(selectedRequest);
                });
            });

            // Event listener in popup close button
            const popupClose = document.getElementById('popup-close');
            popupClose.addEventListener('click', () => {
                popupContainer.style.display = 'none';
            });

            // Event listener in "Accept request" button
            const acceptButton = document.getElementById('accept-button');
            acceptButton.addEventListener('click', () => {
                // Add your logic to handle accepting the request here
                popupContainer.style.display = 'none';  // make the popup disappear
                const data = new FormData();            // prepare the data to send to accepted_request.php
                data.append("popupRequestId", selectedRequest.id);
                
                // Send AJAX request to update_request.php
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "accepted_request.php", true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        // if the php-script was executed successfully, refresh the page. 
                        window.location.href = "notifications.php";
                    } else {
                        console.error('Error updating request in the database.');
                    }
                };
                xhr.send(data);
            });
        });
    </script>
  </body>
</html>