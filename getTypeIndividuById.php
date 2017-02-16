<?php
	function getTypeIndividuById($id)
	{
		include("connexionBdd.php");
		$typeIndividu = null;
		
		$req = $bdd->prepare("SELECT * FROM type_individu WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$typeIndividu["id"] = $data["id"];
			$typeIndividu["libelle"] = $data["libelle"];
			$typeIndividu["description"] = $data["description"];
		}
		
		return json_encode($typeIndividu);
	}
	
	echo getTypeIndividuById($_POST["id_type_individu"]);
?>