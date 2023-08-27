<?php 
    session_start();
    require('./config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="acceuilstyle.css" />
</head>
<body>
    <header>
        <div class="title">Virtu'Com</div>
        <!-- SI C'EST UN PROJECT MANAGER; METTRE À DISPOSITION UNE PAGE DE CONFIGURATION DE L'ÈQUIPE. -->
        <div class="line"></div>
        <div class="images">
            <img class="image"  id= "bouton_disponibilite"src="ressources/cocher.png" title="Cocher" alt="Image 1" >
            <a href="index.php"><img class="image" src="ressources/deconnexion.png" title="Se déconnecter" alt="Image 1" ></a>        
        </div>  
    </header>
    <div class="grid-container">
        <a href="demande.php" class="grid-item">Faire une demande</a>
        <a href="notifications.php" class="grid-item">Consulter les notifications</a>
        <a href="conversation.php" class="grid-item">Consulter les conversations</a>
        <a href="#" class="grid-item">Consulter les tickets</a>
    </div>

    <!-- Hidden HTML structure for the popup content, that will only be executed when we click on a "demande" -->
    <div id="popup-container" class="popup-container">
      <div class="popup-content">
        <span class="popup-close" id="popup-close">&times;</span>
        <div class="popup-titre" id="popup-title"> <h1> Disponibilités : </h1></div>
        <div class="popup-description" id="popup-description"> <h2> Veuillez entrer vos dates d'indisponibilité </h2> </div>
        <div class="popup-calendar" id="popup-calendar">
            <div class="dateDebut">
                <label for="dateDebut">Date de début:</label>
                <input type="date" id="dateDebut" name="dateDebut">
            </div>
            <div class="dateFin">
                <label for="dateFin">Date de fin:</label>
                <input type="date" id="dateFin" name="dateFin">
            </div>           
        </div>
        <button class="accept-button" id="accept-button">Sauvegarder</button>
      </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popupContainer = document.getElementById('popup-container');
            const popupRequestId = document.getElementById('popup-titre');
            const popupUsername = document.getElementById('popup-description');
            const popupText = document.getElementById('popup-calendar');  
           
           
           // Event listener in oppening button
           const popupOpen = document.getElementById('bouton_disponibilite');
            popupOpen.addEventListener('click', () => {
                popupContainer.style.display = 'block';
            });

            // Event listener in popup close button
            const popupClose = document.getElementById('popup-close');
            popupClose.addEventListener('click', () => {
                popupContainer.style.display = 'none';
            });

            const acceptButton = document.getElementById('accept-button');
            acceptButton.addEventListener('click', () => {
                // Add your logic to handle accepting the request here
                popupContainer.style.display = 'none';  // make the popup disappear
                const data = new FormData(); 
               
                xhr.send(data);
            });

        });
    </script>

</body>
</html>