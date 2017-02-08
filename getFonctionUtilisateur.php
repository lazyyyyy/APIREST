<?php
	function getFonctionUtilisateurById($id)
	{
		require_once("connexionBdd.php");
		$fonction = null;
		
		$req = $bdd->prepare("SELECT * FROM fonction_utilisateur WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$fonction["id"] = $data["id"];
			$fonction["libelle"] = $data["libelle"];
			$fonction["description"] = $data["description"];
		}
		
		return json_encode($fonction);
	}

?>