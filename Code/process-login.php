<?php
require('./config.php');
session_start();

if (!empty($_POST['email']) AND !empty($_POST['password'])){
    // Verifier que l'utilisateur est enregistré dans la BD
    $stmt = $conn->prepare("SELECT * FROM users WHERE email_user = ?");
    $email = htmlspecialchars($_POST['email']);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $email_existence = $stmt->get_result();

    if ($email_existence->num_rows == 1){
        // Comparer le hash_password enregistré dans la BD avec celui saisie par l'utilisateur
        $stmt = $conn->prepare("SELECT id_user, firstname_user, lastname_user, password_user, users.id_role, name_role FROM users, roles WHERE email_user = ? AND users.id_role = roles.id_role");
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
            $_SESSION['role_id'] = $row['id_role'];
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['name_role'] = $row['name_role'];
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