<?php 
    session_start();
    require('./config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="acceuilstyle.css" />
</head>
<body>
    <header>
        <div class="title">Virtu'Com</div>
        <!-- SI C'EST UN PROJECT MANAGER; METTRE À DISPOSITION UNE PAGE DE CONFIGURATION DE L'ÈQUIPE. -->
        <div class="line"></div>
        <div class="images">
            <a href="index.php"><img class="image" src="ressources/deconnexion.png" title="Se déconnecter" alt="Image 1" ></a>
        </div>  
    </header>
    <div class="grid-container">
        <a href="demande.php" class="grid-item">Faire une demande</a>
        <a href="#" class="grid-item">Consulter les notifications</a>
        <a href="#" class="grid-item">Consulter les conversations</a>
        <a href="#" class="grid-item">Consulter les tickets</a>
        <a href="#" class="grid-item">Modifier mes disponibilités</a>
    </div>
</body>
</html>