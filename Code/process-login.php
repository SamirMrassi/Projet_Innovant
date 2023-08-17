<?php
require('./config.php');

if (!empty($_POST['email']) AND !empty($_POST['password'])){
    // Verifier que l'utilisateur est enregistré dans la BD
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_user = ?");
    $email = htmlspecialchars($_POST['email']);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $email_existence = $stmt->get_result();

    if ($email_existence->num_rows == 1){
        // Comparer le hash_password enregistré dans la BD avec celui saisie par l'utilisateur
        $stmt = $conn->prepare("SELECT id_user,password_user FROM users WHERE email_user = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $password_given = htmlspecialchars($_POST['password']);
        
        if (password_verify($password_given, $row['password'])){
            // Remplir les informations de session
            $id = $row['id'];
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            
            echo "success";
        }else{
            echo "wrong password! Please try again";
        }
    }else{
        echo "The Email does not exist in our database!"; 
    }
}else{
    echo "Veuillez compléter tous les champs!";
}
?>