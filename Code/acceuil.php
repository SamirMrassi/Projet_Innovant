<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Virtu'Com</title>
        <meta charset="utf-8">
    </head>

    <body>
        <?php
            echo "<p> Bienvenue " . $_SESSION['firstname'] . " " .$_SESSION['lastname'] . "! Vous êtes maintenant connecté à votre compte. </p>";
        ?>
            <form method="POST" action="">
                <input type="submit" name="deconnexion" value="Déconnexion">
            </form>
    </body>