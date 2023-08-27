<!-- Conversation page -->
<?php	
	require('./config.php'); 
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Conversations</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="conversationstyle.css" />
	</head>

	<body>
		<header>
	        <div class="title">Virtu'Com</div>
	        <div class="line"></div>
	        <div class="images">
	            <a href="acceuil.php"><img class="image" src="ressources/menu.png" title="Menu" alt="Image 2" ></a>
	            <a href="index.php"><img class="image" src="ressources/deconnexion.png" title="Se déconnecter" alt="Image 1" ></a>
	        </div>  
	    </header>

		<div class="container">
	        <div class="conversations">
	        	<?php
					$stmt = $conn->prepare("SELECT * FROM conversations, requests,users WHERE conversations.id_request = requests.id_request AND conversations.id_responsible = ? AND conversations.id_responsible = users.id_user");
					$stmt->bind_param("i", $_SESSION['user_id'] );
					$stmt->execute();
					$conversation_list = $stmt->get_result();

					if($conversation_list->num_rows == 0){
	            		echo "<p> Vous n'avez aucune conversation ouverte à afficher.</p>";
	            	}
					foreach($conversation_list as $conv){
						echo '<div class="conversation_information"> ';
						echo '<div class="invisible_div" style="display:none;"> ' . $conv["id_conversation"] . ' </div>';
						echo '<div class="invisible_div_userID" style="display:none;">' . $_SESSION['user_id'] . '</div>';
						echo '<div class="conversation_information_request_id">' . $conv["id_request"] . '</div>';
						echo '<div class="conversation_information_name_sender"> Envoyé par: ' . $conv["firstname_user"] . ' ' . $conv['lastname_user'] .' </div>';
						echo ' </div>';
					}

					// add the new message in  the database if send-button was clicked
					if (!empty($_POST['textMessage']) AND !empty($_POST['conversation-id']) ){ // add the new message in  the database		
					   $stmt = $conn->prepare("INSERT INTO messages (message_text, id_sender, id_conversation) VALUES (?,?,?)");
					   $message_text = $_POST['textMessage'];
					   $id_sender = $_SESSION["user_id"];
					   $id_conversation = $_POST['conversation-id'];
					   $stmt->bind_param("sii", $message_text, $id_sender, $id_conversation);
					   $stmt->execute();
				   }
				?>
	        </div>
	        <div class="selected-conversation">
	            <div class="actual-conversation">
	            	<div class="messages">
	            		<!-- Here will be filled the messages of the conversation with Javascript -->
	            	</div>   	
		            <div class="input-area">
		                <input type="text" id="message-input" placeholder="Type your message...">
		                <button id="send-button">Envoyer</button>
		            </div>
		            <div id="button-container" style="display: none;">
					    <button class="centered-create-button">Créer un ticket</button>
					    <button class="centered-close-button">Fermer la demande</button>
					</div>
	            </div>
	        </div>
	    </div>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				
				const conversations = document.querySelector(".conversations");
				const actualConversation = document.querySelector(".actual-conversation");
				const messages = document.querySelector(".messages");
				const inputArea = document.querySelector(".input-area");
				const messageInput = document.getElementById("message-input");
				const sendButton = document.getElementById("send-button");
				const buttonContainer = document.getElementById("button-container");
				const createButton = document.querySelector(".centered-create-button");
				const closeButton = document.querySelector(".centered-close-button");
				let selectedConversation = null;
				
				// Add event listener to every conversation-block at the left of the screen
				const conversation_information = document.querySelectorAll('.conversation_information');
            	conversation_information.forEach(conversation => {
					conversation.addEventListener('click', () => {
						//Make the messages- and input-areas visible 
						messages.style.display = "flex";
						inputArea.style.display = "flex";

						buttonContainer.style.display = "block";
						selectedConversation  = {
	                      id: conversation.querySelector('.invisible_div').textContent,
	                    };
	                    messages.innerHTML = '';

	                    const xhr = new XMLHttpRequest();
				        xhr.open('POST', 'fetch_messages.php', true);
				        xhr.onreadystatechange = function() {
				            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				                const received_messages = JSON.parse(xhr.responseText);
				                const first_received_msg = received_messages[0];
				                if(first_received_msg === null){ // is there a created ticket for this conversation ?
				                	// make the create-ticket-button visible
				                	createButton.style.color = "red";
				                }

				                received_messages.shift();
				                received_messages.forEach((msg, index) => {
				                    const messageDiv = document.createElement('div');
				                    messageDiv.classList.add('actual-message');
				                    messageDiv.textContent = msg.message_text;

				                    if (msg.id_sender  === parseInt(conversation.querySelector('.invisible_div_userID').textContent)) {
				                    	console.log(msg.id_sender);
								        messageDiv.classList.add('actual-message', 'sender-message');
								    } else {
								        messageDiv.classList.add('actual-message', 'receiver-message');
								    }
				                    messages.appendChild(messageDiv);
				                });
				            }
				        };
				        
				        const data = new FormData();
						data.append("conv-id", selectedConversation.id);
				        xhr.send(data);
					});
            	});

				// Add event listener to send button
				sendButton.addEventListener("click", function() {
					const messageText = messageInput.value;
					if (messageText.trim() !== "") {
						const data = new FormData();
						data.append("textMessage", messageText);
						data.append("conversation-id", selectedConversation.id);
						
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

                		const messageDiv = document.createElement('div');
	                    messageDiv.classList.add('actual-message');
	                    messageDiv.textContent = messageText;
					    messageDiv.classList.add('actual-message', 'sender-message');
					    
	                    messages.appendChild(messageDiv);
						messageInput.value = "";
					}
				});
		    });
		</script>
	</body>
</html>