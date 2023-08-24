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
	        	<!-- imaginary data, actual data should be fetched from the database later -->
	        	<?php
					$stmt = $conn->prepare("SELECT * FROM conversations, requests,users WHERE conversations.id_request = requests.id_request AND conversations.id_responsible = ? AND conversations.id_responsible = users.id_user");
					$stmt->bind_param("i", $_SESSION['user_id'] );
					$stmt->execute();
					$conversation_list = $stmt->get_result();

					foreach($conversation_list as $conv){
						echo '<div class="conversation_information"> ';
						echo '<div class="conversation_information_request_id"> Demande: ' . $conv["id_request"] . ' </div>';
						echo '<div class="conversation_information_name_sender"> Envoyé par: ' . $conv["firstname_user"] . ' ' . $conv['lastname_user'] .' </div>';
						echo ' </div>';
					}
					if (!empty($_POST['textMessage'])){
						
						// Récuperer l'id du role selectionné. 
					   $stmt = $conn->prepare("INSERT INTO messages (message_text, id_sender, id_conversation) VALUES (?,?,?)");
					   $message_text = $_POST['textMessage'];
					   $id_sender = $_SESSION["user_id"];
					   $id_conversation = 2;
					   $stmt->bind_param("sii", $message_text, $id_sender, $id_conversation);
					   $stmt->execute();
				   }
				?>
	        </div>
	        <div class="selected-conversation">
	            <div class="messages">
	            	<div class="message incoming">Hi there!</div>
				    <div class="message outgoing">Hello! How can I help you?</div>
				    <div class="message incoming">I have a question about the project.</div>
				    <div class="message incoming">Can we discuss it tomorrow?</div>
				    <div class="message outgoing">Sure! Let's meet at 10 AM.</div>
	            </div>
	            <div class="input-area">
	                <input type="text" id="message-input" placeholder="Type your message...">
	                <button id="send-button">Send</button>
	            </div>
	        </div>
	    </div>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				
				const conversations = document.querySelector(".conversations");
				const messages = document.querySelector(".messages");
				const messageInput = document.getElementById("message-input");
				const sendButton = document.getElementById("send-button");
				const requestSquares = document.querySelectorAll('.request');
            	requestSquares.forEach(square => {
					square.addEventListener('click', () => {
					selectedRequest  = {
						id: square.querySelector('.request-id').textContent,
						username: square.querySelector('.username').textContent,
						text: square.querySelector('.text').textContent
					};
					openPopup(selectedRequest);
					});
            	});
				// Add event listener to conversation blocks to load selected conversation
				conversations.addEventListener("click", function(event) {
					if (event.target.classList.contains("conversation")) {
						// Load selected conversation using AJAX or fetch
					}
				});

				// Add event listener to send button
				sendButton.addEventListener("click", function() {
					const messageText = messageInput.value;
					if (messageText.trim() !== "") {
						const data = new FormData();
						data.append("textMessage", messageText);
						
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
						messageInput.value = "";
					}
				});
		    });
		</script>
	</body>
</html>