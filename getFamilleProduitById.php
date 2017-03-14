<?php
	/*function getFamilleProduitById($id)
	{
		include("connexionBdd.php");
		$famille = null;
		
		$req = $bdd->prepare("SELECT * FROM famille_produit WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$famille["id"] = $data["id"];
			$famille["libelle"] = $data["libelle"];
			$famille["description"] = $data["description"];
		}
		
		return json_encode($famille);
	}*/
	include("fonctions.php");
	
	echo getFamilleProduitById($_POST["id_famille_produit"]);
?>