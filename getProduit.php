<?php
	function getProduitByName($nom)
	{
		include("connexionBdd.php");
		
		$produit = null;
		$i = 0;
		$nom = "%".strtoupper($nom)."%";
		
		$req = $bdd->prepare("SELECT * FROM produit WHERE UCASE(libelle) LIKE ? ORDER BY libelle");
		$req->execute(array($nom));
		while($data = $req->fetch())
		{
			$produit[$i]["id"] = $data["id"];
			$produit[$i]["libelle"] = $data["libelle"];
			$produit[$i]["effets"] = $data["effets"];
			$produit[$i]["contre_indications"] = $data["contre_indications"];
			
			require_once("getTypeIndividu.php");
			$produit[$i]["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			require_once("getComposant.php");
			$produit[$i]["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			require_once("getLaboratoire.php");
			$produit[$i]["laboratoire"] = json_decode(getLaboById($data["id_laboratoire"]));
			
			require_once("getDosage.php");
			$produit[$i]["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			require_once("getFamilleProduit.php");
			$produit[$i]["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
			
			$i++;
		}
		return json_encode($produit);
	}
	
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
			
			require_once("getTypeIndividu.php");
			$produit["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			require_once("getComposant.php");
			$produit["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			require_once("getLaboratoire.php");
			$produit["laboratoire"] = json_decode(getLaboById($data["id_laboratoire"]));
			
			require_once("getDosage.php");
			$produit["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			require_once("getFamilleProduit.php");
			$produit["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
		}
		return json_encode($produit);
	}
	
	function getProduitByCompteRenduId($id) // id du compte rendu
	{
		include("connexionBdd.php");
		$produits = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT id_produit FROM compte_rendu_produit WHERE id_compte_rendu = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			$produits[$i] = json_decode(getProduitById($data["id_produit"]));
			
			$i++;
		}
		
		return json_encode($produits);
	}
	
	var_dump(json_decode(getProduitByCompteRenduId(1)));
?>