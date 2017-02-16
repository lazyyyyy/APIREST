<?php
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
	
	echo getRegionByLibelle($_POST["libelle_region"]);
?>