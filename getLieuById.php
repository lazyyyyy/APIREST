<?php
	/*function getLieuById($id)
	{
		include("connexionBdd.php");
		$lieu = null;
		
		$req = $bdd->prepare("SELECT * FROM lieu WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$lieu["libelle"] = $data["libelle"];
			$lieu["adresse"] = $data["adresse"];
			$lieu["cp"] = $data["cp"];
			$lieu["ville"] = $data["ville"];
			$lieu["pays"] = $data["pays"];
			
			require_once("fonctions.php");
			$lieu["region"] = json_decode(getRegionById($data["id"]));
		}
		
		return json_encode($lieu);
	}*/
	include("fonctions.php");
	
	echo getLieuById($_POST["id_lieu"]);
	
?>