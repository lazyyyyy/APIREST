<?php
	/*function getFonctionPraticienById($id)
	{
		include("connexionBdd.php");
		$fonction = null;
		
		$req = $bdd->prepare("SELECT * FROM fonction_praticien WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$fonction["id"] = $data["id"];
			$fonction["libelle"] = $data["libelle"];
			$fonction["description"] = $data["description"];
		}
		
		return json_encode($fonction);
	}*/
	
	require_once("fonctions.php");
	echo getFonctionPraticienById($_POST["fonction_praticien_id"]);
?>