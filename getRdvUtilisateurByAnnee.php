<?php
	require_once("fonctions.php");
	
	echo getRdvUtilisateurByAnnee($_POST["annee"], $_POST["id_utilisateur"]);
?>