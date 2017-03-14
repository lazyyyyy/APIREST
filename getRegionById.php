<?php
	/*function getRegionById($id)
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
	}*/
	require_once("fonctions.php");
	
	echo getRegionById($_POST["id_region"]);
?>