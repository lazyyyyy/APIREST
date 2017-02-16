<?php
	function addCompteRendu($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur)
	{
		include("connexionBdd.php");
		
		try
		{
			$req = $bdd->prepare("INSERT INTO compte_rendu_visite(date, bilan, coefficient_confiance, coefficient_notoriete, coefficient_prescription, id_motif, id_praticien, id_produit, id_utilisateur) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$data = $req->execute(array($date, $bilan, $coef_confiance, $coef_notoriete, $coef_prescription, $id_motif, $id_praticien, $id_produit, $id_utilisateur));
		}
		catch(Exception $e){
			$data = false;
		}
				
		return json_encode($data); //retourne true ou false
	}
	
	echo addCompteRendu($_POST["date"], $_POST["bilan"], $_POST["coef_confiance"]; $_POST["coef_notoriete"], $_POST["coef_prescription"], $_POST["id_motif"], $_POST["id_praticien"], $_POST["id_produit"], $_POST["id_utilisateur"]);
	
?>