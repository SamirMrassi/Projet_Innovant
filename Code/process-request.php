<!-- If the user clicks on "Envoyer la demande", this php-block will receive the data from Javascript through ajax (httpRequest) and push it in the DB -->
<?php 
require('./config.php');
session_start();


if (!empty($_POST['userInput']) AND !empty($_POST['selectedRole']) AND !empty($_POST['object'])) {
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
    // Insérer la demande dans la bd.
    $stmt = $conn->prepare("INSERT INTO requests (description, request_status, id_role, id_user, object_text) VALUES (?,?,?,?,?)");
    $userInput = $_POST['userInput'];
    $object = $_POST['object'];
    $userId = $_SESSION["user_id"];
    $requestStatus = 2;
    $stmt->bind_param("siiis", $userInput, $requestStatus, $selectedRole, $userId, $object);
    $stmt->execute();
}
?>