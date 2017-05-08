<?php
	require_once("fonctions.php");
	echo modifierFrais($_POST["montant"], $_POST["commentaire"], $_POST["date"], $_POST["id_type_frais"], $_POST["id_frais"]);
?>