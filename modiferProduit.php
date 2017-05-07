<?php
	require_once("fonctions.php");
	
	echo modiferProduit($_POST["id_produit"], $_POST["libelle"], $_POST["effets"], $_POST["contre_indications"], $_POST["dosage"], $_POST["type_individu"], $_POST["id_laboratoire"], $_POST["id_famille"], $_POST["composants"]);
?>