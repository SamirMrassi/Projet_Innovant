<?php 
    session_start();
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
        <?php
            if ($_SESSION['role'] == 1){ 
         ?>
        <div class="line"></div>
        <div class="images">
            <a href="#"><img class="image" src="ressources/user-logo.png" alt="Image 1"></a>
        </div>  <?php } ?>
    </header>
    <div class="grid-container">
        <div class="grid-item">Faire une demande</div>
        <div class="grid-item">Consulter les notifications</div>
        <div class="grid-item">Consulter les conversations</div>
        <div class="grid-item">Consulter les tickets</div>
        <div class="grid-item">Modifier mes disponibilités</div>
    </div>
</body>
</html>