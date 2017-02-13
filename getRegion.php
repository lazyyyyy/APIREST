<?php
	function getRegionById($id)
	{
		include("connexionBdd.php");
		$region = null;
		
		$req = $bdd->prepare("SELECT * FROM region WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$region["id"] = $data["id"];
			$region["libelle"] = $data["libelle"];
		}
		return json_encode($region);
	}
	
	function getRegionByLibelle($libelle)
	{
		include("connexionBdd.php");
		
		$libelle = "%".strtoupper($libelle)."%";
		$i = 0;
		$region = null;
		
		$req = $bdd->prepare("SELECT * FROM region WHERE libelle LIKE ?");
		$req->execute(array($libelle));
		while($data = $req->fetch())
		{
			$region[$i]["id"] = $data["id"];
			$region[$i]["libelle"] = $data["libelle"];
			
			$i++;
		}
		return json_encode($region);
	}
?>