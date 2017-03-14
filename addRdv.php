<?php
	/*function addRdv($date, $commentaire, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO rdv(date, commentaire, id_praticien, id_visiteur, id_lieu, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($date, $commentaire, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur));
		}
		catch(Exception $e)
		{
			$data = false;
		}
		
		return json_encode($data); //retourne "true" ou "false"
	}*/
	require_once("fonctions.php");
	
	echo addRdv($_POST["date"], $_POST["commentaire"], $_POST["id_praticien"], $_POST["id_visiteur"], $_POST["id_lieu"], $_POST["id_utilisateur"]);
?>