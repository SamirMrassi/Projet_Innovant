<?php
    require('./config.php');
   // session_destroy();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Virtu'Com</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="indexstyle.css" />
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const transformButton = document.getElementById("transformButton");
                const ConnexionForm = document.getElementById("ConnexionForm");
                const flexContainer = document.querySelector(".flex-container");

                transformButton.addEventListener("click", function() {
                    // Créer le formulaire
                    const form = document.createElement("form");
                    form.className = "connection-form";
                    form.innerHTML = `
                        <label for="email">Email :</label><br>
                        <input type="text" id="email" name="email"><br><br>
                        <label for="password">Mot de passe :</label><br>
                        <input type="password" id="password" name="password"><br><br>
                        <input class="submit_button" type="submit" value="se connecter">
                        <p> Vous n'avez pas de compte?  <a href="inscription.php" > Inscrivez-vous!</a></p>                        
                    `;

                    // Ajouter un gestionnaire d'événements pour la soumission du formulaire
                    form.addEventListener("submit", function(event) {
                        event.preventDefault(); // Empêche la soumission normale du formulaire

                        // Supprimer le message d'erreur lors de la prochaine interaction
                        const errorElement = document.getElementById("errorDiv");
                        if (errorElement) {
                            form.removeChild(errorElement);
                        }

                        const email = form.elements.email.value;
                        const password = form.elements.password.value;

                        // Créer un objet FormData pour collecter les données du formulaire
                        const formData = new FormData();
                        formData.append("email", email);
                        formData.append("password", password);

                        // Effectuer une requête AJAX vers le fichier PHP de traitement
                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "process-login.php", true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    // La requête a été traitée avec succès, vous pouvez afficher la réponse
                                    const response = xhr.responseText;
                                    if (response === "success") {
                                        // Rediriger vers la page d'accueil
                                        window.location.href = "acceuil.php";
                                    } else {
                                        // Afficher un message d'erreur
                                        const errorDiv = document.createElement("div");
                                        errorDiv.id = "errorDiv";
                                        errorDiv.className = "error-message";
                                        errorDiv.textContent = response;
                                        form.appendChild(errorDiv);
                                    }
                                } else { console.error("Erreur lors de la requête AJAX"); }
                            }
                        };
                        xhr.send(formData);
                    });
                
                    // Remplacer le bouton par le formulaire
                    ConnexionForm.innerHTML = "";
                    flexContainer.appendChild(form);
                });
            });

        </script>
    </head>

    <body>
        <header>
            <div class="title">Virtu'Com</div>
            <!-- SI C'EST UN PROJECT MANAGER; METTRE À DISPOSITION UNE PAGE DE CONFIGURATION DE L'ÈQUIPE. -->
        </header>

        <div class="flex-container" id="flex-container">
            <div class="div_left">
                <h2> Comment rendre  <br>la communication plus productive<br> dans votre équipe ? <br> Nous avons la réponse. </h2>
                <p> _____________________________ </p>
                <p> Une communication d'équipe sans frontières ni retards : Virtu'Com réinvente la collaboration en se concentrant sur les rôles, pour des échanges toujours ciblés et en temps voulu. Avec Virtu'Com, dites adieu aux retards de communication ! </p>
                <br><br>   
                <div id="ConnexionForm">
                   <button id="transformButton">Connexion</button> 
                </div>   
            </div>
            <div class="div_right">
                <img class="welcome_page_image"  id= "welcome_page_image" src="ressources/communication.png" > 
            </div>
        </div>
        
    </body>
</html>