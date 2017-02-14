<?php
	//TOUTES CES FONCTIONS RENVOIENT true OU false

	function addVehicule($immatricule, $description, $kilometrage, $disponible, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("INSERT INTO vehicule(immatricule, description, kilometrage, disponible, equipement, id_parc_automobile, id_energie, id_type_vehicule) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
		$data = $req->execute(array($immatricule, $description, $kilometrage, $disponible, $equipement, $id_parc_automobile, $id_energie, $id_type_vehicule));
		
		return json_encode($data);
	}
	
	function removeVehiculeByImmatricule($immatricule)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM vehicule WHERE immatricule = ?");
		$data = $req->execute(array($immatricule));
		
		return json_encode($data);
	}
	
	function reservationVehicule($immatricule, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		$id_parc_auto = null;
		$reponse = false;
		
		$req = $bdd->prepare("SELECT COUNT(*) nb FROM vehicule_utilisateur WHERE id_utilisateur = ? AND date_arrivee IS NULL");
		$req->execute(array($id_utilisateur));
		if($data = $req->fetch())
		{
			if($data["nb"] == 0) // Verification si l'utilisateur possède déjà ou non un vehicule
			{
				$req = $bdd->prepare("SELECT id_parc_automobile, disponible FROM vehicule WHERE immatricule = ?");
				$req->execute(array($immatricule));
				if($data = $req->fetch())
				{
					if($data["disponible"] == 1) // Verification si le vehicule est disponible ou non
					{
						$id_parc_auto = $data["id_parc_automobile"];
						
						$req = $bdd->prepare("UPDATE vehicule SET disponible = 0 WHERE immatricule = ?");
						$reponse = $req->execute(array($immatricule));
						
						if($reponse)
						{
							$req = $bdd->prepare("INSERT INTO vehicule_utilisateur(id_utilisateur, immatricule_vehicule, date_depart, id_parc_automobile_depart) VALUES(?, ?, NOW(), ?)");
							$reponse = $req->execute(array($id_utilisateur, $immatricule, $id_parc_auto));
						}
					}
				}
			}
		}
		return json_encode($reponse);
	}
	
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
	
	var_dump(json_decode(rendreVehicule(1, 1, 150)));
?>