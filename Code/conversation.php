<!-- Conversation page -->
<?php	require('./config.php'); ?>
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
	        	<div class="conversation">John Doe</div>
				<div class="conversation">Jane Smith</div>
				<div class="conversation">Alice Johnson</div>
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
	</body>
</html>