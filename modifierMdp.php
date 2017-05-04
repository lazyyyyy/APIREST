<?php
	require_once("fonctions.php");
	
	echo modifierMdp($_POST["mdp"], $_POST["id_utilisateur"]);
?>