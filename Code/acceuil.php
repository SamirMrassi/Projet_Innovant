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
        
        <div class="user_information">
            <?php
                echo ' <span class = "text" style ="color: white; font-weight: bold;"> '. $_SESSION['firstname']. ' ' . $_SESSION['lastname']. ' (' . $_SESSION['name_role']. ') </span>';
            ?>
        </div>
        
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

    <!-- Hidden HTML structure for the popup content, that will only be executed when we click on the "disponibility"-button -->
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

    <?php
        if (isset($_POST['begin-date']) && isset($_POST['end-date'])) {
            $beginDate = $_POST['begin-date'];
            $endDate = $_POST['end-date'];
            
            $stmt = $conn->prepare("INSERT INTO unavailibilities (start_date, end_date, id_user) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $beginDate, $endDate, $_SESSION['user_id']);
            $stmt->execute();
        }
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popupContainer = document.getElementById('popup-container');
            const popupRequestId = document.getElementById('popup-titre');
            const popupUsername = document.getElementById('popup-description');
            const popupText = document.getElementById('popup-calendar');  
            const dateDebutInput = document.getElementById('dateDebut');
            const dateFinInput = document.getElementById('dateFin');
            const errorText = document.createElement('p'); // Create an element for error messages

            const popupClose = document.getElementById('popup-close');
            const acceptButton = document.getElementById('accept-button');
           
            // Event listener in oppening button
            const popupOpen = document.getElementById('bouton_disponibilite');
            popupOpen.addEventListener('click', () => {
                popupContainer.style.display = 'block';
                errorText.textContent = ""; // Clear any previous error messages
            });

            // Event listener in popup close button
            
            popupClose.addEventListener('click', () => {
                popupContainer.style.display = 'none';
            });

            
            acceptButton.addEventListener('click', () => {
                const today = new Date();
                if (!dateDebutInput.value || !dateFinInput.value) {
                    errorText.innerText = "Vous devez remplir la date de début et de fin!";
                    alert("Vous devez remplir la date de début et de fin!");
                    //acceptButton.parentNode.appendChild(errorText);
                } else if (dateDebutInput.value > dateFinInput.value) {
                    errorText.textContent = "Votre date de début ne peut pas être supérieure à votre date de fin!";
                    alert("Votre date de début ne peut pas être supérieure à votre date de fin!");
                    //acceptButton.parentNode.appendChild(errorText);
                } else if (dateDebutInput.value < today.toISOString().split('T')[0]) {
                    errorText.textContent = "Votre indisponibilité ne peut commencer qu'à partir de demain!";
                    alert("Votre indisponibilité ne peut commencer qu'à partir de demain!");
                    //acceptButton.parentNode.appendChild(errorText);
                } else {
                    popupContainer.style.display = 'none'; // Hide the popup
                    const data = new FormData();
                    data.append("begin-date", dateDebutInput.value);
                    data.append("end-date", dateFinInput.value);

                    // Send the data to the server using AJAX
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "", true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                            } else { console.error("Erreur lors de la requête AJAX"); }
                        }
                    };
                    xhr.send(data);
                }
            });

        });
    </script>

</body>
</html>