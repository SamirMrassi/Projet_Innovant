<!-- Faire une demande -->
<?php 
    session_start();
    require('./config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Faire une demande</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="demandestyle.css" />
</head>

<body>
    <header>
        <div class="title">Virtu'Com</div>
        
			<div class="user_information">
				<?php
					echo ' <span class = "text" style ="color: white; font-weight: bold;"> '. $_SESSION['firstname']. ' ' . $_SESSION['lastname']. ' (' . $_SESSION['name_role']. ') </span>';
				?>
        	</div>
			
        <div class="line"></div>
        <div class="images">
            <a href="acceuil.php"><img class="image" src="ressources/menu.png" title="Menu" alt="Image 2" ></a>
            <a href="index.php"><img class="image" src="ressources/deconnexion.png" title="Se déconnecter" alt="Image 1" ></a>
        </div>  
    </header>
    
    <div class="request-area">
        <div class="roles-destinataires">
            <p> Rôles destinataire :
            <select class="request-select" id="role" name="role" style="width: 200px;">
                <option value=""></option>
                <?php
                    $stmt = $conn->prepare("SELECT * FROM roles");
                    $stmt->execute();
                    $roles_list = $stmt->get_result();
                    if ($roles_list->num_rows > 0) {
                        while ($row = $roles_list->fetch_assoc()) 
                            echo '<option value="' . $row["name_role"] . '"  >' . $row["name_role"] . '</option>';
                    }
                ?>

            </select></p>
            <div class="selected-items" id="selectedItems"> </div>

        <div>           
            <div class="container">
                <textarea id="object-input" placeholder="Object..."></textarea>
                <textarea id="text-input" placeholder="Start typing..."></textarea>
            </div>

            <div class="send-button"> 
                <button id="send-button">Envoyer la demande</button>
            </div>
            
        </div>

    </div>            

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-item">
                <img src="ressources/demande1.png" alt="Icône 1">
                <p class="footer_text">1. Je formule ma demande</p>
            </div>
            <div class="footer-item">
                <img src="ressources/demande2.png" alt="Icône 2">
                <p class="footer_text">2. Je l'assigne à un rôle</p>
            </div>
            <div class="footer-item">
                <img src="ressources/demande3.png" alt="Icône 3">
                <p class="footer_text">3. Et je l'envoie !</p>
            </div>
        </div>
    </footer>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const itemSelect = document.getElementById("role");
        const selectedItemsContainer = document.getElementById("selectedItems");
        const selectedItems = new Set(); // To keep track of selected items
        const sendRequestButton = document.getElementById("send-button");

        // Add eventlistener to capture the selected-item from the roles-list. 
        itemSelect.addEventListener("change", function () {
            const selectedItemValue = itemSelect.value;
            if (selectedItemValue !== "") {
                if (!selectedItems.has(selectedItemValue)){ 
                    selectedItems.add(selectedItemValue);
                    const newItemElement = document.createElement("div");
                    newItemElement.classList.add("selected-item");
                    newItemElement.innerHTML = `
                        <span class="selected-item-text">${selectedItemValue}</span>
                        <span class="delete-button">X</span>
                    `;
                    const deleteButton = newItemElement.querySelector(".delete-button");
                    deleteButton.addEventListener("click", function () {
                        selectedItemsContainer.removeChild(newItemElement);
                        selectedItems.removeChild(selectedItemValue)
                    });
                    selectedItemsContainer.appendChild(newItemElement);
                }
            }
        });

        // Add eventlistenenr to the sendRequestButton. This should trigger a php-script that adds the new request in the database.
        sendRequestButton.addEventListener("click", function () {
            const textInput = document.getElementById("text-input").value;
            const objectInput = document.getElementById("object-input").value;
            
            if (selectedItems.size === 0) {                                         // if no role-receiver was chosen
                alert("Veuillez choisir au moins un destinataire.");
            } else if(objectInput === ""){
                alert("Votre demande doit impérativement avoir un Objet");
            } else if (textInput === "") {                                          // if no text for the request was written
                alert("Votre demande doit être rédigée avant d'être envoyée.");
            } else {                                                                // else, create the new "demande" in the database
                const selectedRole = itemSelect.value;
                // Create a data object to send via AJAX
                const data = new FormData();
                data.append("selectedRole", selectedRole);
                data.append("userInput", textInput);
                data.append("object", objectInput);

                // Send the data to the server using AJAX
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "process-request.php", true);
                xhr.onreadystatechange = function () {

                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            window.location.href = "acceuil.php";
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