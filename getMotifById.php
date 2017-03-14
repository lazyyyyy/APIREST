<?php
	/*function getMotifById($id)
	{
		include("connexionBdd.php");
		$motif = null;
		
		$req = $bdd->prepare("SELECT * FROM motif WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$motif["id"] = $data["id"];
			$motif["libelle"] = $data["libelle"];
			$motif["description"] = $data["description"];
		}
		
		return json_encode($motif);
	}*/
	include("fonctions.php");
	
	echo getMotifById($_POST["id_motif"]);
?>