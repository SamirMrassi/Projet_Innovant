<?php	require('./config.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inscription</title>
		<meta charset="utf-8">
	</head>

	<body>
		<h1> Virtu'Com </h1>
		<h4> Inscription </h4>
		<form method="POST" action="">
			<p> Prenom: 
			<input type="text" name="firstname"></p>
			<p> Nom: 
			<input type="text" name="lastname"></p>
			<p> E-mail: 
			<input type="text" name="email"></p>
			<p> Saisissez un mot de passe: 
			<input type="password" name="password"></p>
			<p> Role: 
			<label for="role">Sélectionnez une option :</label>
       		<select id="role" name="role" style="width: 200px;">
			    <option value="">Sélectionnez un rôle</option>
            	<?php
					$stmt = $conn->prepare("SELECT name_role FROM roles");
                	$stmt->execute();
                	$roles_list = $stmt->get_result();
					if ($roles_list->num_rows > 0) {
						while ($row = $roles_list->fetch_assoc()) {
							echo '<option value="' . $row["name_role"] . '"  >' . $row["name_role"] . '</option>';

						}
					}
				?>
       		 </select>
			<input type="reset" value="Reset">
			<input type="submit" name="inscription" value="Valider">
		</form>

		<?php 
		if (isset($_POST['inscription'])){
			if (!empty($_POST['firstname']) AND !empty($_POST['lastname']) AND !empty($_POST['email']) AND !empty($_POST['password']) AND!empty($_POST['role'])){
				$email = $_POST['email'];
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					echo "L'adresse e-mail n'est pas valide.";
				}else{

					// Verifier que l'email n'est pas connu par le système 
					$stmt = $conn->prepare("SELECT email_user FROM users WHERE users.email_user =?");
					$email = htmlspecialchars($_POST['email']);
					$stmt->bind_param("s", $email);
					$stmt->execute();
					$email_list_existence = $stmt->get_result();

					if ($email_list_existence->num_rows == 0){
						// Verifier que le password répond aux conditions définies
						$password = htmlspecialchars($_POST['password']);
						// longueur minimal de 8 chars
					
						$pattern = '/^(?=.*[!@#$%^&*-])(?=.{8,30})(?=.*[0-9])(?=.*[a-zA-Z]).{5,}$/';
						// se composant de lettres chiffres et char spéciaux
						if (preg_match($pattern, $password)){
							$roleName = $_POST['role'];
							$stmtRole = $conn->prepare("SELECT id_role FROM roles WHERE name_role = ?");
							$stmtRole->bind_param("s", $roleName);
							$stmtRole->execute();
							$roleResult = $stmtRole->get_result();
							$roleRow = $roleResult->fetch_assoc();
							$roleId = $roleRow['id_role'];
							// Enregistrer le nouvel utilisateur dans la BD.
							$stmt = $conn->prepare("INSERT INTO users (firstname_user, lastname_user, email_user, password_user, id_role) VALUES (?,?,?,?,?)");
							$hashed_password = password_hash($password, PASSWORD_BCRYPT);
							$firstname=$_POST['firstname'];
							$lastname=$_POST['lastname'];
							
							$stmt->bind_param("sssss", $firstname, $lastname, $email, $roleId, $hashed_password);
							$stmt->execute();
							echo "<p>Merci pour votre inscription! Vous êtes maintenant notre client !</p>";
							echo "<a href='index.php' > Page d'acceuil</a>";
						}else{
							echo "<p style='color:red'>Le mot de passe doit contenir des lettres, des chiffres et des caractères spéciaux, et il doit se composer de minimum 8 caractères!</p>";
						}
					}else{
						echo "<p style='color:red'>Cet utilisateur existe déjà!</p>"; 
					}
				}	
			}else{
				echo "<p style='color:red'>Veuillez compléter tous les champs!</p>";
			}
		}
		?>
		
	</body>
</html>