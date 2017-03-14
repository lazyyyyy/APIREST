<?php
	/*function reservationVehicule($immatricule, $id_utilisateur)
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
	}*/
	include("fonctions.php");
	
	echo reservationVehicule($_POST["immatricule_vehicule"], $_POST["id_utilisateur"]);
?>