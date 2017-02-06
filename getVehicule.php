<?php

	function getVehicule($immatricule)
	{
		include("connexionBdd.php");
		$vehicule = null;
		
		$req = $bdd->prepare("SELECT * FROM vehicule WHERE immatricule = ?");
		$req->execute(array($immatricule));
		if($data = $req->fetch())
		{
			$vehicule["immatricule"] = $data["immatricule"];
			$vehicule["description"] = $data["description"];
			$vehicule["kilometrage"] = $data["kilometrage"];
			$vehicule["disponible"] = $data["disponible"];
			$vehicule["equipement"] = $data["equipement"];
			
			$req2 = $bdd->prepare("SELECT * FROM energie WHERE id = ?");
			$req2->execute(array($data["id_energie"]));
			if($data2 = $req2->fetch())
			{
				$vehicule["energie"]["id"] = $data2["id"];
				$vehicule["energie"]["libelle"] = $data2["libelle"];
				$vehicule["energie"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM type_vehicule WHERE id = ?");
			$req2->execute(array($data["id_type_vehicule"]));
			if($data2 = $req2->fetch())
			{
				$vehicule["type_vehicule"]["id"] = $data2["id"];
				$vehicule["type_vehicule"]["libelle"] = $data2["libelle"];
				$vehicule["type_vehicule"]["description"] = $data2["description"];
			}
			
			$req2 = $bdd->prepare("SELECT * FROM parc_automobile WHERE id = ?");
			$req2->execute(array($data["id_parc_automobile"]));
			if($data2 = $req2->fetch())
			{
				$vehicule["parc_automobile"]["id"] = $data2["id"];
				$vehicule["parc_automobile"]["libelle"] = $data2["libelle"];
				
				include("getLieu.php");
				$vehicule["parc_automobile"]["lieu"] = json_decode(getLieuById($data2["id_lieu"]));
			}
		}
		
		return json_encode($vehicule);
	}
	
?>