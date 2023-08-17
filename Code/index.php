<?php
    require('./config.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Virtu'Com</title>
        <meta charset="utf-8">
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const transformButton = document.getElementById("transformButton");
                const ConnexionForm = document.getElementById("ConnexionForm");

                transformButton.addEventListener("click", function() {
                    // Créer le formulaire
                    const form = document.createElement("form");
                    form.innerHTML = `
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email"><br><br>
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password"><br><br>
                        <input type="submit" value="se connecter">
                        <h4> Vous n'avez pas de compte?  </h4>
                        <a href="inscription.php" > Inscrivez-vous!</a>
                    `;

                    // Ajouter un gestionnaire d'événements pour la soumission du formulaire
                    form.addEventListener("submit", function(event) {
                        event.preventDefault(); // Empêche la soumission normale du formulaire

                        const email = form.elements.email.value;
                        const password = form.elements.password.value;

                        // Ici, vous pouvez mettre en place la logique de vérification de l'email et du mot de passe
                        // Si les vérifications réussissent, vous pouvez rediriger vers la page d'accueil
                        // Sinon, vous pouvez afficher un message d'erreur et laisser le formulaire intact

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
                                        errorDiv.style.color = "red";
                                        errorDiv.textContent = response;
                                        form.appendChild(errorDiv);
                                    }
                                } else {
                                    // Gérer les erreurs en cas de problème de communication avec le serveur
                                    console.error("Erreur lors de la requête AJAX");
                                }
                            }
                        };
                        xhr.send(formData);

                    });
                
                    // Remplacer le bouton par le formulaire
                    ConnexionForm.innerHTML = "";
                    ConnexionForm.appendChild(form);

                    // Supprimer le message d'erreur lors de la prochaine interaction
                    transformButton.addEventListener("click", function() {
                        const errorElement = document.getElementById("errorDiv");
                        if (errorElement) {
                            form.removeChild(errorElement);
                        }
                    });
                });
            });

        </script>
    </head>

    <body>
        <h1> Virtu'Com </h1>
        <h4> Une communication d'équipe sans frontières ni retards : Virtu'Com réinvente la collaboration en se concentrant sur les rôles, pour des échanges toujours ciblés et en temps voulu. Avec Virtu'Com, dites adieu aux retards de communication ! </h4>
        <br><br>

        <?php
            echo "Veuillez vous connecter :";
            ?>
            <div id="ConnexionForm">
                <button id="transformButton">Connexion</button>
            </div>

        
    </body>
</html>









<!-- <!DOCTYPE html>
<html>
<head>
    <title>Mon Site Web</title>
</head>
<body>
    <h1>Mon premier site web</h1>
    <p>Cliquez sur le bouton pour afficher un message :</p>
    <button onclick="afficherMessage()">Cliquez ici</button>

    <script>
        function afficherMessage() {
            alert('Bonjour !');
        }
    </script>
</body>
</html> -->
