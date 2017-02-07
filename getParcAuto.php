<?php
	function getParcAutoById($id)
	{
		require_once("connexionBdd.php");
		require_once("getLieu.php");
		$parc_auto = null;
		
		$req = $bdd->prepare("SELECT * FROM parc_automobile WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parc_auto["id"] = $data["id"];
			$parc_auto["libelle"] = $data["libelle"];
			
			$parc_auto["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($parc_auto);
	}
	
	function getInfosParcAuto($id)
	{
		require_once("getVehicule.php");
		require_once("connexionBdd.php");
		$parc_auto = null;
		
		$req = $bdd->prepare("SELECT COUNT(*) nb_vehicule FROM vehicule WHERE id_parc_automobile = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parc_auto["nb_total_vehicules"] = $data["nb_vehicule"];
		}
		
		$i = 0;
		$req = $bdd->prepare("SELECT immatricule, disponible FROM vehicule WHERE id_parc_automobile = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$parc_auto["liste_vehicules"][$i] = json_decode(getVehicule($data["immatricule"]));
			$i++;
		}
		
		$req = $bdd->prepare("SELECT COUNT(*) nb_vehicule FROM vehicule WHERE id_parc_automobile = ? AND disponible = 1");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$parc_auto["nb_vehicules_dispo"] = $data["nb_vehicule"];
		}
		
		$parc_auto["liste_vehicules_dispo"] = null;
		$j = 0;
		for($i = 0; $i < sizeof($parc_auto["liste_vehicules"]); $i++)
		{
			if($parc_auto["liste_vehicules"][$i]["disponible"] == 1)
			{
				$parc_auto["liste_vehicules_dispo"][$j] = $parc_auto["liste_vehicules"][$i];
				$j++;
			}
		}
		
		return json_encode($parc_auto);
	}
?>