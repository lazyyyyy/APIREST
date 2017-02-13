<?php
	function addRdv($date, $commentaire, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("INSERT INTO rdv(date, commentaire, id_praticien, id_visiteur, id_lieu, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?)");
		$data = $req->execute(array($date, $commentaire, $id_praticien, $id_visiteur, $id_lieu, $id_utilisateur));
		
		return json_encode($data); //retourne "true" ou "false"
	}
	
	function removeRdvById($id)
	{
		include ("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM rdv WHERE id = ?");
		$data = $req->execute(array($id));
		
		return json_encode($data); //retourne "true" ou "false"
	}
	
?>