<?php
	session_start();

	// Création de la connexion avec la base de données
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "virtucom";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	  exit();
	}
?>