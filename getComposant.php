<?php
	function getComposantById($id)
	{
		include("connexionBdd.php");
		$composant = null;
		
		$req = $bdd->prepare("SELECT * FROM composant WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$composant["id"] = $data["id"];
			$composant["libelle"] = $data["libelle"];
			$composant["description"] = $data["description"];
		}
		
		return json_encode($composant);
	}
?>