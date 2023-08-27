<?php
require('./config.php');
session_start();

if (isset($_POST['conv-id'])) {
    $conv_id = $_POST['conv-id'];

    // Verify if there is a ticket for this conversation
	$stmt = $conn->prepare("SELECT m.id_message, m.message_text, m.id_sender,
    						u_sender.id_user AS sender_id,
    						u_sender.firstname_user AS sender_firstname,
    						u_sender.lastname_user AS sender_lastname,
    						u_sender.id_role AS sender_role_id,
    						r.id_user AS request_user_id
							FROM messages m
							JOIN conversations c ON m.id_conversation = c.id_conversation
							JOIN users u_sender ON m.id_sender = u_sender.id_user
							JOIN requests r ON c.id_request = r.id_request
							WHERE m.id_conversation = ? ORDER BY m.id_message;");
	$stmt->bind_param("i", $conv_id);
	$stmt->execute();
    $result = $stmt->get_result();
    $messages = array();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
}
?>
