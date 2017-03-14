<?php
	/*function getProduitById($id)
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
			
			require_once("fonctions.php");
			$produit["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			$produit["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			$produit["laboratoire"] = json_decode(getLaboratoireById($data["id_laboratoire"]));
			
			$produit["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			$produit["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
		}
		return json_encode($produit);
	}*/
	include("fonctions.php");
	
	echo getProduitById($_POST["id_produit"]);
?>