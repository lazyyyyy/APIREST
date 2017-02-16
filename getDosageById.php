<?php
	function getDosageById($id)
	{
		include("connexionBdd.php");
		$dosage = null;
		
		$req = $bdd->prepare("SELECT * FROM dosage WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$dosage["id"] = $data["id"];
			$dosage["quantite"] = $data["quantite"];
			$dosage["unite"] = $data["unite"];
			$dosage["description"] = $data["description"];
		}
		
		return json_encode($dosage);
	}
	
	echo getDosageById($_POST["dosage_id"]);
?>