<?php
	function rendreVehicule($id_utilisateur, $id_parc_automobile_arrivee, $distance_parcourue)
	{
		include("connexionBdd.php");
		
		$reponse = false;
		
		$req = $bdd->prepare("SELECT immatricule_vehicule  FROM vehicule_utilisateur WHERE id_utilisateur  = ? AND date_arrivee IS NULL");
		$req->execute(array($id_utilisateur));
		if($data = $req->fetch())
		{
			$req = $bdd->prepare("UPDATE vehicule SET disponible = 1 WHERE immatricule = ?");
			$reponse = $req->execute(array($data["immatricule"]));
			if($reponse)
			{
				$req = $bdd->prepare("UPDATE vehicule_utilisateur SET date_arrivee = NOW(), id_parc_automobile_arrivee = ?, distance_parcourue = ? WHERE id_utilisateur  = ? AND date_arrivee IS NULL");
				$reponse = $req->execute(array($id_parc_automobile_arrivee, $distance_parcourue, $id_utilisateur));
			}
		}
		
		return json_encode($reponse);
	}
	
	echo rendreVehicule($_POST["id_utilisateur"], $_POST["id_parc_automobile"], $_POST["distance_parcourue"]);
?>