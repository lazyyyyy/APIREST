<?php
	function getCompteRenduById($id)
	{
		include("connexionBdd.php");
		
		$compteRendu = null;
		
		$req = $bdd->prepare("SELECT * FROM compte_rendu_visite WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$compteRendu["id"] = $data["id"];
			$compteRendu["date"] = $data["date"];
			$compteRendu["bilan"] = $data["bilan"];
			$compteRendu["coef_confiance"] = $data["coefficient_confiance"];
			$compteRendu["coef_notoriete"] = $data["coefficient_notoriete"];
			$compteRendu["coef_prescription"] = $data["coefficient_prescription"];
			
			require_once("fonctions.php");
			$compteRendu["motif"] = json_decode(getMotifById($data["id_motif"]));
			
			require_once("fonctions.php");
			$compteRendu["echantillons"] = json_decode(getEchantillonByCompteRenduId($data["id"]));
			
			require_once("fonctions.php");
			$compteRendu["praticien"] = json_decode(getPraticienById($data["id_praticien"]));
			
			require_once("fonctions.php");
			$compteRendu["produits"] = json_decode(getProduitByCompteRenduId($data["id"]));
			
			require_once("fonctions.php");
			$compteRendu["utilisateur"] = json_decode(getUtilisateurById($data["id_utilisateur"]));
		}
		
		return json_encode($compteRendu);
	}
	
	echo getCompteRenduById($_POST["compte_rendu_id"]);
?>