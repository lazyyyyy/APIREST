<?php
	function getLieuById($id)
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
			
			include("getRegion.php");
			$lieu["region"] = json_decode(getRegionById($data["id"]));
		}
		
		return json_encode($lieu);
	}
	
	function getLieu($mot)
	{
		include("connexionBdd.php");
		$mot = "%".strtoupper($mot)."%";
		$i = 0;
		$lieu = null;
		
		$req = $bdd->prepare("SELECT * FROM lieu WHERE UCASE(libelle) LIKE ? OR UCASE(ville) LIKE ? OR UCASE(pays) LIKE ? OR UCASE(adresse) LIKE ? OR UCASE(cp) LIKE ?");
		$req->execute(array($mot, $mot, $mot, $mot, $mot));
		while($data = $req->fetch())
		{
			$lieu[$i]["libelle"] = $data["libelle"];
			$lieu[$i]["adresse"] = $data["adresse"];
			$lieu[$i]["cp"] = $data["cp"];
			$lieu[$i]["ville"] = $data["ville"];
			$lieu[$i]["pays"] = $data["pays"];
			
			include("getRegion.php");
			$lieu[$i]["region"] = json_decode(getRegionById($data["id"]));
			
			$i++;
		}
		
		return json_encode($lieu);
	}
?>