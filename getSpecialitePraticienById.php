<?php
	/*function getSpecialitePraticienById($id)
	{
		include("connexionBdd.php");
		$specialite = null;
		
		$req = $bdd->prepare("SELECT *FROM specialite WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$specialite["id"] = $data["id"];
			$specialite["libelle"] = $data["libelle"];
			$specialite["description"] = $data["description"];
		}
		
		return json_encode($specialite);
	}*/
	
	require_once("fonctions.php");
	
	echo getSpecialitePraticienById($_POST["id_specialite_praticien"]);
?>