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
        $stmt = $conn->prepare("SELECT firstname_user, lastname_user, password_user FROM users WHERE email_user = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $password_given = htmlspecialchars($_POST['password']);
        if (password_verify($password_given, $row['password_user'])){
            // Remplir les informations de session
            $_SESSION['firstname'] = $row['firstname_user'];
            $_SESSION['lastname'] = $row['lastname_user'];
            $_SESSION['email'] = $email;
            
            echo "success";
        }else{
            echo "Mot de passe incorrect! Veuillez réessayer";
        }
    }else{
        echo "Votre email n'existe pas dans nos bases de données!"; 
    }
}else{
    echo "Veuillez compléter tous les champs!";
}
?>