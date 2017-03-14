<?php
	/*function removeVehiculeByImmatricule($immatricule)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM vehicule WHERE immatricule = ?");
		$data = $req->execute(array($immatricule));
		
		return json_encode($data);
	}*/
	include("fonctions.php");
	
	echo removeVehiculeByImmatricule($_POST["immatricule_vehicule"]);
?>