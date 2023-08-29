<!-- Inscription page -->
<?php	require('./config.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Inscription</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="inscriptionstyle.css" />
	</head>

	<body>
		<header>
            <div class="title">Virtu'Com</div>
			<a href="index.php" class="return">Page d'accueil</a>
            <!-- SI C'EST UN PROJECT MANAGER; METTRE À DISPOSITION UNE PAGE DE CONFIGURATION DE L'ÈQUIPE. -->
        </header>
		<div class="flex-container" id="flex-container">
			<div class="inscription_text">
				<h2> Rejoins-nous ! </h2>
				<p> Rejoins-nous dès aujourd'hui pour entamer une nouvelle aventure avec Virtu'Com ! Inscris-toi maintenant et découvre comment notre plateforme révolutionne la collaboration, en faisant de chaque échange une opportunité ciblée et sans délai. Ensemble, disons adieu aux retards de communication et embrassons l'efficacité de demain</p>
			</div>

			<div class="inscription_form">
				<form method="POST" class="inscription-form" action="">
					<p> Prenom: <br> <input type="text" name="firstname"></p>
					<p> Nom: <br><input type="text" name="lastname"></p>
					<p> E-mail:<br> <input type="text" name="email"></p>
					<p> Mot de passe:<br> <input type="password" name="password"></p>
					<p> Rôle: <br><label for="role"></label>
					<select id="role" name="role" style="width: 200px;">
						<option value=""></option>
						<!-- An sql-request to get all roles from the database and display them as a list -->
						<?php
							$stmt = $conn->prepare("SELECT * FROM roles");
							$stmt->execute();
							$roles_list = $stmt->get_result();
							if ($roles_list->num_rows > 0) {
								while ($row = $roles_list->fetch_assoc()) 
									echo '<option value="' . $row["id_role"] . '"  >' . $row["name_role"] . '</option>';
							}
						?>
					</select><br>
					<input type="submit" name="inscription" value="S'inscrire">
				

					<?php 
						if (isset($_POST['inscription'])){
							//Vérifier que tous les champs du formulaire sont remplit
							if (!empty($_POST['firstname']) AND !empty($_POST['lastname']) AND !empty($_POST['email']) AND !empty($_POST['password']) AND!empty($_POST['role'])){
								//Vérifier que l'email saisi es valide
								$email = $_POST['email'];
								if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
									echo "L'adresse e-mail n'est pas valide.";
								}else{

									// Verifier que l'email n'est pas connu par le système (utilisateur non existant)
									$stmt = $conn->prepare("SELECT email_user FROM users WHERE users.email_user =?");
									$email = htmlspecialchars($_POST['email']);
									$stmt->bind_param("s", $email);
									$stmt->execute();
									$email_list_existence = $stmt->get_result();

									if ($email_list_existence->num_rows == 0){
										// Si l'utilisateur n'existe pas dans la BDD,vérifier que le password répond aux conditions définies
										$password = htmlspecialchars($_POST['password']);					
										$pattern = '/^(?=.*[!@#$%^&*-])(?=.{8,30})(?=.*[0-9])(?=.*[a-zA-Z]).{5,}$/'; // se composant de lettres chiffres et char spéciaux
										if (preg_match($pattern, $password)){
											// Enregistrer le nouvel utilisateur dans la BD.
											$stmt = $conn->prepare("INSERT INTO users (firstname_user, lastname_user, email_user, password_user, id_role) VALUES (?,?,?,?,?)");
											$hashed_password = password_hash($password, PASSWORD_BCRYPT);
											$firstname = htmlspecialchars($_POST['firstname']);
											$lastname = htmlspecialchars($_POST['lastname']);
											$stmt->bind_param("ssssi", $firstname, $lastname, $email, $hashed_password, $_POST['role']);
											$stmt->execute();
											echo "<p>Merci pour votre inscription! Vous êtes maintenant notre client !</p>";
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
				</form>
			</div>
		</div>
		<footer class="footer">
			<div class="footer-content">
				<div class="footer-item">
					<img src="ressources/verifier.png" alt="Icône 1">
					<p class="footer_text">Efficacité Instantanée.</p>
				</div>
				<div class="footer-item">
					<img src="ressources/verifier.png" alt="Icône 2">
					<p class="footer_text">Collaboration Sans Frontières.</p>
				</div>
				<div class="footer-item">
					<img src="ressources/verifier.png" alt="Icône 3">
					<p class="footer_text">Communication Ciblée.</p>
				</div>
			</div>
    	</footer>
	</body>
</html>