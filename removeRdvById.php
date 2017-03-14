<?php
	/*function removeRdvById($id)
	{
		include ("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM rdv WHERE id = ?");
		$data = $req->execute(array($id));
		
		return json_encode($data); //retourne "true" ou "false"
	}*/
	require_once("fonctions.php");
	
	echo removeRdvById($_POST["id_rdv"]);
?>