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
		<link rel="stylesheet" href="conversations.css" />
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
					$stmt = $conn->prepare("SELECT c.id_conversation, c.id_responsible, c.id_request
											FROM conversations c
											WHERE (c.id_responsible = ?
											OR c.id_request IN (
											    SELECT r.id_request
											    FROM requests r
											    WHERE r.id_user = ?
											) OR c.id_request IN (SELECT r.id_request FROM requests r WHERE r.id_role=?)) AND c.id_request IN (SELECT r.id_request FROM requests r WHERE r.request_status = 1); ");
					$stmt->bind_param("iii", $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['role_id'] );
					$stmt->execute();
					$conversation_list = $stmt->get_result();

					if($conversation_list->num_rows == 0){
	            		echo "<p> Vous n'avez aucune conversation ouverte à afficher.</p>";
	            	} else{
	            		foreach($conversation_list as $conv){


							$stmt = $conn->prepare("SELECT req.object_text, u.firstname_user, u.lastname_user, r.name_role
													FROM users u
													INNER JOIN requests req ON u.id_user = req.id_user
													INNER JOIN roles r ON r.id_role = req.id_role
													WHERE req.id_request = ?;");
							$stmt->bind_param("i", $conv['id_request']);
							$stmt->execute();
							$sender_receiver_info = $stmt->get_result();
							echo '<div class="conversation_information"> ';
							echo '<div class="invisible_div" style="display:none;"> ' . $conv["id_conversation"] . ' </div>';
							echo '<div class="invisible_div_userID" style="display:none;">' . $_SESSION['user_id'] . '</div>';
							echo '<div class="invisible_div_username" style="display:none;">' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] . '</div>';
							echo '<div class="conversation_information_request_id">' . $conv["id_request"] . '</div>';
							foreach($sender_receiver_info as $info){
								echo '<div class="conversation_information_name_sender conv-info-text"> De <strong>' . $info["firstname_user"] . ' ' . $info['lastname_user'] . ' </strong> vers le rôle <strong>' . $info['name_role'] . '</strong> </div>';
								echo '<div class="conversation_information_name_sender conv-info-text"> <strong>Object: </strong>' . $info["object_text"] . '</div>';
							}
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

				    // If change status request was trigerred
				    if (!empty($_POST['conv-id'])){
				   	    $stmt = $conn->prepare("UPDATE requests SET request_status = 3
												WHERE id_request = (SELECT id_request FROM conversations WHERE id_conversation = 1);");
					    $conversationID = $_POST['conv-id'];
					    $stmt->bind_param("i", $conversationID);
					    $stmt->execute();
				    } 	 
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
					<button class="change-conv-status" title="La conversation disparaitra">Clore la demande</button>
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
				const changeStatus = document.querySelector(".change-conv-status");
				const sendButton = document.getElementById("send-button");
				let username = document.querySelector(".invisible_div_username").textContent;
				let interlocuteur = null;
				let selectedConversation = null;
				
				// Add event listener to every conversation-block at the left of the screen
				const conversation_information = document.querySelectorAll('.conversation_information');
            	conversation_information.forEach(conversation => {
					conversation.addEventListener('click', () => {
						const thisUserID = parseInt(conversation.querySelector('.invisible_div_userID').textContent);
						// Make the messages- and input-areas visible 
						messages.style.display = "flex";
						inputArea.style.display = "flex";
 
						// Si l'utilisateur est celui qui a fait la demande, rendre visible le bouton 
						// qui changera le status de la demande
						

						// Préparer la donnée de la conversation (id) à envoyer au script php 
						// afin de recevoir les messages de la conversation de la base des données
						selectedConversation  = {
	                      id: conversation.querySelector('.invisible_div').textContent,
	                    };
	                    messages.innerHTML = '';

	                    // Envoyer les information via Ajax et enclencher l'affichage des messages après les avoir reçus du script php.
	                    const xhr = new XMLHttpRequest();
				        xhr.open('POST', 'fetch_messages.php', true);
				        xhr.onreadystatechange = function() {
				            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				                const received_messages = JSON.parse(xhr.responseText);
				                received_messages.forEach((msg, index) => {
				                	//If this user created the request, he should be able to change the status of the request to completed
				                	if(msg.request_user_id === thisUserID){
				                		changeStatus.style.display = "block";
				                	}else{
				                		changeStatus.style.display = "none";
				                	}

				                	const senderDiv = document.createElement('div');
				                    senderDiv.classList.add('message-information');

				                    const messageDiv = document.createElement('div');
				                    messageDiv.classList.add('actual-message');
				                    messageDiv.textContent = msg.message_text;

				                    if (msg.id_sender  === thisUserID) {
				                    	username = msg.sender_firstname + " " + msg.sender_lastname;
								        senderDiv.classList.add('sender-message');
				                    	senderDiv.textContent = username;
								        messageDiv.classList.add('actual-message', 'sender-message');
								    } else {
								    	interlocuteur = msg.sender_firstname + " " + msg.sender_lastname;
								    	senderDiv.classList.add('receiver-message');
								        senderDiv.textContent = interlocuteur;
								        messageDiv.classList.add('actual-message', 'receiver-message');
								    }
								    messages.appendChild(senderDiv);
				                    messages.appendChild(messageDiv);
				                });
				            }
				        };
				        const data = new FormData();
						data.append("conv-id", selectedConversation.id);
				        xhr.send(data);

				        //Add eventlistener for closing a request if this user has created it
				        changeStatus.addEventListener('click', () => {
				        	// Send the data to the server using AJAX
							const xhr = new XMLHttpRequest();
							xhr.open("POST", "", true);
							xhr.onreadystatechange = function () {

								if (xhr.readyState === XMLHttpRequest.DONE) {
									if (xhr.status === 200) {
										window.location.href = "conversation.php";
									} else { console.error("Erreur lors de la requête AJAX"); }
								}
	                    	};
	                		xhr.send(data);
				        });
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

                		const senderDiv = document.createElement('div');
				        senderDiv.classList.add('message-information');
				        senderDiv.classList.add('sender-message');
				        senderDiv.textContent = username;

                		const messageDiv = document.createElement('div');
	                    messageDiv.classList.add('actual-message');
	                    messageDiv.textContent = messageText;
					    messageDiv.classList.add('actual-message', 'sender-message');
					    
					    messages.appendChild(senderDiv);
	                    messages.appendChild(messageDiv);
						messageInput.value = "";
					}
				});
		    });
		</script>
	</body>
</html>