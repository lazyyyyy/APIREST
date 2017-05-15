<?php
	require_once("fonctions.php");
	
	echo modifierRdv($_POST["id"], $_POST["date"], $_POST["titre"], $_POST["description"], $_POST["id_praticien"], $_POST["id_lieu"], $_POST["id_utilisateur"], $_POST["adresseLieu"], $_POST["cpLieu"], $_POST["villeLieu"], $_POST["paysLieu"], $_POST["regionLieu"]);