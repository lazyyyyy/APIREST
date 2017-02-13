<?php
	function getEchantillonById($id)
	{
		include("connexionBdd.php");
		
		$echantillon = null;
		
		$req = $bdd->prepare("SELECT * FROM echantillon WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$echantillon["id"] = $data["id"];
			$echantillon["qte"] = $data["qte"];
			
			
		}
	}
	
	function getEchantillonByCompteRenduId($id) // id du compte rendu
	{
		include("connexionBdd.php");
		
		$echantillons = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT * FROM compte_rendu_echantillon WHERE compte_rendu_id = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$echantillons[$i] = json_decode(getEchantillonById($data["echantillon_id"]));
			$i++;
		}
		
		return json_encode($echantillons);
	}
?>