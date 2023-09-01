<?php
require('./config.php');
session_start();

if (isset($_POST['conv-id'])) {
    $conv_id = $_POST['conv-id'];

    $messages = array(); // The container of the results to be sent to the javascript code

    $stmt = $conn->prepare("SELECT r.id_user FROM conversations c
							JOIN requests r ON c.id_request = r.id_request
							WHERE c.id_conversation = ?; ");
	$stmt->bind_param("i", $_POST['conv-id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$firstRow = $result->fetch_assoc();
	$messages[] = $firstRow;

	$stmt = $conn->prepare("SELECT description FROM requests JOIN conversations c ON c.id_conversation = ? Where c.id_request = requests.id_request;");
    $stmt->bind_param("i", $_POST['conv-id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $secondRow = $result->fetch_assoc();
	$messages[] = $secondRow;

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
    

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
}
?>
