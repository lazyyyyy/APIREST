<?php
	require_once("fonctions.php");
	echo modifierVehicule($_POST["ancien_immatricule_vehicule"], $_POST["nouveau_immatricule_vehicule"], $_POST["id_marque"], $_POST["id_model"], $_POST["description"], $_POST["kilometrage"], $_POST["equipement"], $_POST["id_parc_automobile"], $_POST["id_energie"], $_POST["id_type_vehicule"]);
?>