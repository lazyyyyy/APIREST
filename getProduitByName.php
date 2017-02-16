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
			
			require_once("fonctions.php");
			$produit[$i]["type_individu"] = json_decode(getTypeIndividuById($data["id_type_individu"]));
			
			$produit[$i]["composant"] = json_decode(getComposantById($data["id_composant"]));
			
			$produit[$i]["laboratoire"] = json_decode(getLaboById($data["id_laboratoire"]));
			
			$produit[$i]["dosage"] = json_decode(getDosageById($data["id_dosage"]));
			
			$produit[$i]["famille"] = json_decode(getFamilleProduitById($data["id_famille_produit"]));
			
			$i++;
		}
		return json_encode($produit);
	}
	
	echo getProduitByName($_POST["nom_produit"]);
?>