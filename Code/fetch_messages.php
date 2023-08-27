<?php
require('./config.php');
session_start();

if (isset($_POST['conv-id'])) {
    $conv_id = $_POST['conv-id'];

    // Verify if there is a ticket for this conversation
	$stmt = $conn->prepare("SELECT id_ticket FROM tickets WHERE id_conversation = ?");
	$stmt->bind_param("i", $conv_id);
	$stmt->execute();
    $ticket = $stmt->get_result();

    // Fetch all messages corresponding to this conversation and sent it back to the javascript-code with ajax
    $stmt = $conn->prepare("SELECT message_text, id_sender FROM messages WHERE id_conversation = ? ORDER BY id_message ");
    $stmt->bind_param("i", $conv_id);
    $stmt->execute();
    $result = $stmt->get_result();


    $messages = array();
    $messages[] = $ticket->fetch_assoc();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
}
?>
