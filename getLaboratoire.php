<?php
	function getLaboById($id)
	{
		include("connexionBdd.php");
		$labo = null;
		
		$req = $bdd->prepare("SELECT * FROM laboratoire WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$labo["id"] = $data["id"];
			$labo["nom"] = $data["nom"];
			
			include("getLieu.php");
			$labo["lieu"] = json_decode(getLieuById($data["id_lieu"]));
		}
		
		return json_encode($labo);
	}
?>