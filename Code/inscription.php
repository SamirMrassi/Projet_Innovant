<?php	require('./config.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inscription</title>
		<meta charset="utf-8">
	</head>

	<body>
		<h2> Page d'inscription </h2>

		<form method="POST" action="">
			<p> Saisissez un nom d'utilisateur: 
			<input type="text" name="inscription_username"></p>
			<p> Saisissez un mot de passe: 
			<input type="password" name="inscription_password"></p>
			<input type="reset" value="Reset">
			<input type="submit" name="inscription" value="Valider">
		</form>

		<?php 
		if (isset($_POST['inscription'])){
			if (!empty($_POST['inscription_username']) AND !empty($_POST['inscription_password'])){

				// Verifier que le nom d'utilisateur n'est pas connu par le système 
				$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
				$username = htmlspecialchars($_POST['inscription_username']);
			    $stmt->bind_param("s", $username);
			    $stmt->execute();
			    $username_existence = $stmt->get_result();

			    if ($username_existence->num_rows == 0){
			    	// Verifier que le password répond aux conditions définies
			    	$password = htmlspecialchars($_POST['inscription_password']);
			    	// longueur minimal de 5 chars
			    	if (strlen($password) >= 5){
			    		$pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[a-zA-Z]).{5,}$/';
			    		// se composant de lettres chiffres et char spéciaux
			    		if (preg_match($pattern, $password)){
			    			// Enregistrer le nouvel utilisateur dans la BD.
			    			$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?,?)");
							$hashed_password = password_hash($password, PASSWORD_BCRYPT);
						    $stmt->bind_param("ss", $username, $hashed_password);
						    $stmt->execute();
			    			echo "<p>Merci pour votre inscription! Vous êtes maintenant notre client !</p>";
			    			echo "<a href='connexion.php' > Connectez-vous!</a>";
			    		}else{
			    			echo "<p style='color:red'>Le mot de passe doit contenir des lettres, des chiffres et des caractères spéciaux, et il doit se composer de minimum 5 caractères!</p>";
			    		}
			    	}else{
			    		echo "<p style='color:red'>Le mot de passe doit contenir des lettres, des chiffres et des caractères spéciaux, et il doit se composer de minimum 5 caractères!</p>";
			    	}
			    }else{
			     	echo "<p style='color:red'>le nom d'utilisateur existe déjà!</p>"; 
			    }
			}else{
				echo "<p style='color:red'>Veuillez compléter tous les champs!</p>";
			}
		}
		?>
		
	</body>
</html>