<?php
	function addCompteRendu($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("INSERT INTO compte_rendu_visite(date, bilan, coefficient_confiance, coefficient_notoriete, coefficient_prescription, id_motif, id_praticien, id_produit, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$data = $req->execute(array($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur));
		
		return json_encode($data); //retourne true ou false
	}
	
	function removeCompteRenduById($id)
	{
		include("connexionBdd.php");
		
		$req = $bdd->prepare("DELETE FROM compte_rendu_visite WHERE id = ?");
		$data = $req->execute(array($id));
		
		return json_encode($data); //retourne true ou false
	}
?>