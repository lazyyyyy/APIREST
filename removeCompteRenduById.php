<?php
	/*function removeCompteRenduById($id)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM compte_rendu_visite WHERE id = ?");
		$data = $req->execute(array($id));
		
		return json_encode($data); //retourne true ou false
	}*/
	include("fonctions.php");
	
	echo removeCompteRenduById($_POST["id_compte_rendu"]);
?>