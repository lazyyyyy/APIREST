<?php
	require_once("fonctions.php");
	
	echo getRdvUtilisateurByDate($_POST["id_utilisateur"], $_POST["date"]);
?>