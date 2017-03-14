<?php
	/*function getProduitByCompteRenduId($id) // id du compte rendu
	{
		include("connexionBdd.php");
		$produits = null;
		$i = 0;
		
		$req = $bdd->prepare("SELECT id_produit FROM compte_rendu_produit WHERE id_compte_rendu = ?");
		$req->execute(array($id));
		while($data = $req->fetch())
		{
			require_once("fonctions.php");
			$produits[$i] = json_decode(getProduitById($data["id_produit"]));
			
			$i++;
		}
		
		return json_encode($produits);
	}*/
	require_once("fonctions.php");
	echo getProduitByCompteRenduId($_POST["id_compte_rendu"]);
?>