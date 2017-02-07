<?php
	function getTypePraticienById($id)
	{
		include("connexionBdd.php");
		$type = null;
		
		$req = $bdd->prepare("SELECT * FROM type_praticien WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$type["id"] = $data["id"];
			$type["libelle"] = $data["libelle"];
			$type["description"] = $data["description"];
		}
		
		return json_encode($type);
	}
?>