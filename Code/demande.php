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
        <!-- SI C'EST UN PROJECT MANAGER; METTRE À DISPOSITION UNE PAGE DE CONFIGURATION DE L'ÈQUIPE. -->
        <?php
            if ($_SESSION['role'] == 1){ 
         ?>
        <div class="line"></div>
        <div class="images">
            <a href="#"><img class="image" src="ressources/user-logo.png" alt="Image 1"></a>
        </div>  <?php } ?>
    </header>
    
    <div class="roles-destinataires">
        <p> Rôles(s) destinataire(s) :   
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

        </div>

        <div class="container">
            <textarea id="text-input" placeholder="Start typing..."></textarea>
        </div>
        <div class="send-button"> 
            <button id="send-button">Envoyer la demande</button>
        </div>

        <script>
        const itemSelect = document.getElementById("role");
        const selectedItemsContainer = document.getElementById("selectedItems");
        const selectedItems = new Set(); // To keep track of selected items
        const sendRequestButton = document.getElementById("send-button");

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
                    itemSelect.value = "";
                }
            }
        });

        sendRequestButton.addEventListener("click", function () {
            const textInput = document.getElementById("text-input").value.trim();
            
            if(selectedItems.size === 0){
                alert("Veuillez choisir au moins un destinataire.");
            }else if (textInput === ""){
                alert("Votre demande doit être rédigée avant d'être envoyée.");
            }else {
                // 
                // Redirect to another page
                window.location.href = "acceuil.php"; // Replace with the actual URL
            }
        });
    </script>
</body>
</html>