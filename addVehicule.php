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
	
	//la variable $_POST["image"] est inutile, je l'ai laissée uniquement pour éviter les problèmes avec le code déjà fait.
	echo addVehicule($_POST["immatricule_vehicule"], $_POST["id_marque"], $_POST["id_model"], $_POST["description"], $_POST["kilometrage"], $_POST["equipement"], $_POST["id_parc_automobile"], $_POST["id_energie"], $_POST["id_type_vehicule"], $_POST["image"]);
?>