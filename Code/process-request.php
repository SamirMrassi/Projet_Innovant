<!-- If the user clicks on "Envoyer la demande", this php-block will receive the data from Javascript through ajax (httpRequest) and push it in the DB -->
<?php 
require('./config.php');
session_start();


if (!empty($_POST['userInput']) AND !empty($_POST['selectedRole'])){
     // Récuperer l'id du role selectionné. 
    $stmt = $conn->prepare("SELECT * FROM roles");
    $stmt->execute();
    $roles_list = $stmt->get_result();
    if ($roles_list->num_rows > 0) {
        while ($row = $roles_list->fetch_assoc()) 
            if ($row["name_role"] == $_POST['selectedRole']){
                $selectedRole = $row["id_role"];
                break;
            }
    }
    // Inserting the request data into the DB.
    $stmt = $conn->prepare("INSERT INTO requests (description, request_status, id_role, id_user) VALUES (?,?,?,?)");
    $userInput = $_POST['userInput'];
    $userId = $_SESSION["user_id"];
    $requestStatus = 2;
    $stmt->bind_param("siii", $userInput, $requestStatus, $selectedRole, $userId);
    $stmt->execute();
}
?>