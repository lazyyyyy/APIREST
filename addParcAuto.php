<?php
	require_once("fonctions.php");
	
	echo addParcAuto($_POST["libelle"], $_POST["id_lieu"], $_POST["adresseLieu"], $_POST["cpLieu"], $_POST["villeLieu"], $_POST["paysLieu"], $_POST["regionLieu"]);
?>