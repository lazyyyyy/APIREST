<?php
	function getParcAutoById($id)
	{
		include("connexionBdd.php");
		include("getLieu.php");
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
		include("getVehicule.php");
		include("connexionBdd.php");
		$parc_auto;
		
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
		
		$req = $bdd->prepare("SELECT immatricule FROM vehicule WHERE id_parc_automobile = ? AND disponible = 1");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$parc_auto["liste_vehicules_dispo"] = json_decode(getVehicule($data["immatricule"]));
		}
		return json_encode($parc_auto);
	}
	
	$parc_auto = json_decode(getInfosParcAuto(1));
	var_dump($parc_auto);
?>