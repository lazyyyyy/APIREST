<?php
	require_once("fonctions.php");
	
	echo addLieu($_POST["libelle"], $_POST["adresse"], $_POST["cp"], $_POST["ville"], $_POST["pays"], $_POST["region_id"]);
?>