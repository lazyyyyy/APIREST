<?php

	/*function addVehicule($immatricule, $description, $kilometrage, $disponible, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO vehicule(immatricule, description, kilometrage, disponible, equipement, id_parc_automobile, id_energie, id_type_vehicule) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($immatricule, $description, $kilometrage, $disponible, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule));
		}
		catch(Excpetion $e)
		{
			$data = false;
		}
		
		return json_encode($data);
	}*/
	require_once("fonctions.php");
	
	echo addVehicule($_POST["immatricule_vehicule"], $_POST["description"], $_POST["kilometrage"], $_POST["disponible"], $_POST["equipement"], $_POST["id_parc_automobile"], $_POST["id_energie"], $_POST["id_type_vehicule"]);
?>