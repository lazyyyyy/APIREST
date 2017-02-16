<?php
	function getProduitById($id)
	{
		include("connexionBdd.php");
		
		$produit = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT * FROM produit WHERE id = ?");
		$req->execute(array($id));
		if($data = $req->fetch())
		{
			$produit["id"] = $data["id"];
			$produit["libelle"] = $data["libelle"];
			$produit["effets"] = $data["effets"];
			$produit["contre_indications"] = $data["contre_indications"];
			
			require_once("getTypeIndividuById.php");
			$produit["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			require_once("getComposantById.php");
			$produit["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			require_once("getLaboratoireById.php");
			$produit["laboratoire"] = json_decode(getLaboratoireById($data["id_laboratoire"]));
			
			require_once("getDosageById.php");
			$produit["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			require_once("getFamilleProduitById.php");
			$produit["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
		}
		return json_encode($produit);
	}
	
	echo getProduitById($_POST["id_produit"]);
?>