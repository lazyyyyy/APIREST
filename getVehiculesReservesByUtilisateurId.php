<?php
	require_once("fonctions.php");
	
	echo getVehiculesReservesByUtilisateurId($_POST["id_utilisateur"]);
?>